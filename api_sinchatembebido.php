<?php
// Configuración de CORS (Permite solicitudes desde cualquier origen)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de solicitudes OPTIONS (preflight en CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuración de conexión a la base de datos
$db_host = "localhost";
$db_user = "u352160281_pruebasgpt";
$db_pass = "sereP_123";
$db_name = "u352160281_pruebasgpt";

// Conectar a la base de datos
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

// Establecer el encabezado de respuesta como JSON
header("Content-Type: application/json; charset=UTF-8");

// Obtener la ruta y el método de la solicitud
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Extraer endpoint (usuarios)
$parsed_url = parse_url($request_uri, PHP_URL_PATH);
$segments = explode("/", trim($parsed_url, "/"));
$endpoint = end($segments);

// Verificar si el endpoint es "usuarios"
if ($endpoint === "usuarios") {
    switch ($method) {
        case "GET":
            listarUsuarios($conn);
            break;
        case "POST":
            crearUsuario($conn);
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint no encontrado"]);
}

$conn->close();

// --------------------------
// FUNCIONES PARA MANEJAR SOLICITUDES
// --------------------------

function listarUsuarios($conn) {
    $sql = "SELECT id, nombre, email, fecha_creacion FROM usuarios ORDER BY id DESC";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode(["error" => "Error al obtener usuarios: " . $conn->error]);
        return;
    }

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    echo json_encode($usuarios);
}

function crearUsuario($conn) {
    // Leer el JSON de la solicitud
    $inputJSON = file_get_contents('php://input');
    error_log("Datos recibidos: " . $inputJSON); // Log para depuración

    $inputData = json_decode($inputJSON, true);

    // Validar que el JSON sea válido
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error en json_decode: " . json_last_error_msg()); // 👈 Depuración
        http_response_code(400);
        echo json_encode(["error" => "JSON mal formado"]);
        return;
    }

    // Validar que los datos requeridos existen
    if (!isset($inputData['nombre']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(["error" => "El nombre y el email son obligatorios"]);
        return;
    }

    $nombre = trim($inputData['nombre']);
    $email = trim($inputData['email']);

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Formato de email inválido"]);
        return;
    }

    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error en la consulta de verificación: " . $conn->error]);
        return;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409); // Código 409 = Conflicto
        echo json_encode(["error" => "El email ya está registrado"]);
        $stmt->close();
        return;
    }
    $stmt->close();

    // Insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error en la consulta de inserción: " . $conn->error]);
        return;
    }

    $stmt->bind_param("ss", $nombre, $email);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "id" => $stmt->insert_id,
            "nombre" => $nombre,
            "email" => $email,
            "fecha_creacion" => date('Y-m-d H:i:s')
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al insertar usuario: " . $stmt->error]);
    }

    $stmt->close();
}

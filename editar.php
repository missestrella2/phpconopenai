<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        die("Usuario no encontrado.");
    }
} else {
    die("ID no proporcionado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    $update_sql = "UPDATE usuarios SET nombre='$nombre', email='$email' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar usuario: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Editar Usuario</h2>
        <form action="" method="POST" class="flex flex-col gap-4">
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required 
                   class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required 
                   class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Actualizar</button>
        </form>
    </div>
</body>
</html>

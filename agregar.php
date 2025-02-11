<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los datos fueron enviados
    if (!isset($_POST['nombre']) || !isset($_POST['email']) || empty($_POST['nombre']) || empty($_POST['email'])) {
        die("Error: Nombre y correo son obligatorios.");
    }

    // Limpiar y preparar los datos
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    // Depuración: Mostrar datos antes de la inserción
    echo "Datos recibidos:<br>";
    echo "Nombre: $nombre<br>";
    echo "Email: $email<br>";

    // Consulta para insertar datos
    $sql = "INSERT INTO usuarios (nombre, email, fecha_creacion) VALUES ('$nombre', '$email', NOW())";

    // Ejecutar la consulta y verificar errores
    if ($conn->query($sql) === TRUE) {
        echo "Usuario agregado correctamente.";
        // header("Location: index.php"); // Puedes descomentar esto si deseas redirigir
    } else {
        echo "Error al agregar usuario: " . $conn->error;
    }
}

?>

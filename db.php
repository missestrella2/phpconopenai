<?php
$host = "localhost";
$user = "u352160281_pruebasgpt";
$pass = "sereP_123";
$dbname = "u352160281_pruebasgpt";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

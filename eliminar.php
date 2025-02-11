<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al eliminar usuario: " . $conn->error;
    }
} else {
    echo "ID no proporcionado.";
}
?>

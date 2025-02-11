<?php 
include "db.php";

$sql = "SELECT id, nombre, email FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<nav class="bg-blue-600 p-4 text-white flex justify-between">
    <div class="text-lg font-semibold">Gestión de Usuarios</div>
    <div>
        <a href="index.php" class="mx-2 hover:underline">Inicio</a>
        <a href="privacidad.php" class="mx-2 hover:underline">Política de Privacidad</a>
        <a href="https://chatgpt.com/g/g-679c50f5b1e08191b12fd03add69094d-ingresar-usuarios-a-bbdd" 
           target="_blank" class="mx-2 hover:underline">Chatbot</a>
    </div>
</nav>

<div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Lista de Usuarios</h2>
    <input type="text" id="search" placeholder="Buscar usuario..." class="p-2 border border-gray-300 rounded-md w-full mb-4" onkeyup="filterTable()">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg" id="userTable">
        <thead>
            <tr class="bg-blue-500 text-white">
                <th class="py-2 px-4 border">ID</th>
                <th class="py-2 px-4 border">Nombre</th>
                <th class="py-2 px-4 border">Email</th>
                <th class="py-2 px-4 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border text-center">
                <td class="py-2 px-4 border"> <?php echo $row['id']; ?> </td>
                <td class="py-2 px-4 border"> <?php echo $row['nombre']; ?> </td>
                <td class="py-2 px-4 border"> <?php echo $row['email']; ?> </td>
                <td class="py-2 px-4 border">
                    <a href="editar.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600">Editar</a>
                    <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Agregar Usuario</h2>
    <form action="agregar.php" method="POST" class="flex flex-col gap-4">
        <input type="text" name="nombre" placeholder="Nombre" required 
               class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="email" name="email" placeholder="Email" required 
               class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Agregar</button>
    </form>
</div>

<script>
function filterTable() {
    let input = document.getElementById("search");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("userTable");
    let tr = table.getElementsByTagName("tr");
    
    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td");
        let showRow = false;
        for (let j = 0; j < td.length - 1; j++) {
            if (td[j] && td[j].innerText.toLowerCase().indexOf(filter) > -1) {
                showRow = true;
            }
        }
        tr[i].style.display = showRow ? "" : "none";
    }
}
</script>

</body>
</html>

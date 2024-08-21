<?php
session_start();
require 'config.php';

// Verificar que el usuario es administrador
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Productos</title>
</head>
<body>
    <h1>Lista de Productos</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id'] ?></td>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $producto['descripcion'] ?></td>
                <td><?= $producto['precio'] ?></td>
                <td><?= $producto['stock'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $producto['id'] ?>">Editar</a>
                    <a href="delete_product.php?id=<?= $producto['id'] ?>" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="index.php">Volver</a></p>
</body>
</html>

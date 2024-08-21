<?php
session_start();
require 'config.php';

// Verificar que el usuario es administrador
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?");
    if ($stmt->execute([$nombre, $descripcion, $precio, $stock, $id])) {
        echo 'Producto actualizado';
    } else {
        echo 'Error al actualizar producto';
    }
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required>
        <textarea name="descripcion"><?= $producto['descripcion'] ?></textarea>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required>
        <button type="submit">Actualizar Producto</button>
    </form>
    <p><a href="list_products.php">Volver</a></p>
</body>
</html>

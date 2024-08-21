<?php
session_start();
require 'config.php';

// Redirigir si no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h1>Bienvenido a la Licorería</h1>
    <p><a href="logout.php">Cerrar sesión</a></p>

    <?php if ($user_role == 'admin'): ?>
        <h2>Administración</h2>
        <ul>
            <li><a href="create_product.php">Agregar Producto</a></li>
            <li><a href="list_products.php">Ver Productos</a></li>
            <li><a href="manage_orders.php">Gestionar Órdenes</a></li>
        </ul>
    <?php elseif ($user_role == 'cliente'): ?>
        <h2>Compras</h2>
        <ul>
            <li><a href="view_products.php">Ver Productos</a></li>
            <li><a href="create_order.php">Hacer Pedido</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>

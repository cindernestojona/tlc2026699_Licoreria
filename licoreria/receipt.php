<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM ordenes WHERE id = ?");
$stmt->execute([$id]);
$orden = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM detalles_orden WHERE orden_id = ?");
$stmt->execute([$id]);
$detalles = $stmt->fetchAll();

$total = array_sum(array_column($detalles, 'subtotal'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
</head>
<body>
    <h1>Recibo de la Orden #<?= $orden['id'] ?></h1>
    <p>Fecha: <?= $orden['fecha'] ?></p>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($detalles as $detalle): ?>
            <tr>
                <?php
                $stmt = $pdo->prepare("SELECT nombre FROM productos WHERE id = ?");
                $stmt->execute([$detalle['producto_id']]);
                $producto = $stmt->fetch();
                ?>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $detalle['cantidad'] ?></td>
                <td><?= $detalle['subtotal'] ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2">Total</td>
            <td><?= $total ?></td>
        </tr>
    </table>
    <p><a href="index.php">Volver</a></p>
</body>
</html>

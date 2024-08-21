<?php
session_start();
require 'config.php';

// Verificar que el usuario está logueado
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
    <title>Detalles de la Orden</title>
</head>
<body>
    <h1>Detalles de la Orden #<?= $orden['id'] ?></h1>
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
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td><?= htmlspecialchars($detalle['cantidad']) ?></td>
                <td>$<?= number_format($detalle['subtotal'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2">Total</td>
            <td>$<?= number_format($total, 2) ?></td>
        </tr>
    </table>
    <p><a href="index.php">Volver</a></p>
</body>
</html>

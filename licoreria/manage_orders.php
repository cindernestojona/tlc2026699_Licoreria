<?php
session_start();
require 'config.php';

// Verificar que el usuario es administrador
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM ordenes");
$ordenes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Órdenes</title>
</head>
<body>
    <h1>Órdenes</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Detalles</th>
        </tr>
        <?php foreach ($ordenes as $orden): ?>
            <tr>
                <td><?= $orden['id'] ?></td>
                <td><?= $orden['usuario_id'] ?></td>
                <td><?= $orden['fecha'] ?></td>
                <td>
                    <a href="view_order.php?id=<?= $orden['id'] ?>">Ver Detalles</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="index.php">Volver</a></p>
</body>
</html>

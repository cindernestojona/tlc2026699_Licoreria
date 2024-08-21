<?php
session_start();
require 'config.php';

// Verificar que el usuario es administrador
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
if ($stmt->execute([$id])) {
    header('Location: list_products.php');
    exit;
} else {
    echo 'Error al eliminar producto';
}
?>

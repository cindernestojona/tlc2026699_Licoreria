<?php
session_start();
require 'config.php';

// Verificar que el usuario está logueado y es administrador
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$total = 0;
$cambio = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $productos = $_POST['productos']; // Array de productos con cantidad
    $monto_recibido = isset($_POST['monto_recibido']) ? floatval($_POST['monto_recibido']) : 0;

    // Validar que se han seleccionado productos
    if (empty($productos)) {
        echo "<script>alert('No se ha seleccionado ningún producto.'); window.location.href='create_order.php';</script>";
        exit;
    }

    // Validar el monto recibido
    if ($monto_recibido <= 0) {
        echo "<script>alert('El monto recibido debe ser mayor que cero.'); window.location.href='create_order.php';</script>";
        exit;
    }

    $pdo->beginTransaction();

    try {
        // Crear una nueva orden
        $stmt = $pdo->prepare("INSERT INTO ordenes (usuario_id, fecha) VALUES (?, NOW())");
        $stmt->execute([$usuario_id]);
        $orden_id = $pdo->lastInsertId();

        // Insertar detalles de la orden y calcular el total
        foreach ($productos as $producto_id => $cantidad) {
            if ($cantidad > 0) {
                $stmt = $pdo->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
                $stmt->execute([$producto_id]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    $subtotal = $producto['precio'] * $cantidad;
                    $total += $subtotal;

                    $stmt = $pdo->prepare("INSERT INTO detalles_orden (orden_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$orden_id, $producto_id, $cantidad, $subtotal]);
                } else {
                    throw new Exception("Producto no encontrado con ID: $producto_id");
                }
            }
        }

        // Calcular el cambio
        if ($monto_recibido >= $total) {
            $cambio = $monto_recibido - $total;

            $stmt = $pdo->prepare("UPDATE ordenes SET monto_recibido = ?, cambio = ? WHERE id = ?");
            $stmt->execute([$monto_recibido, $cambio, $orden_id]);

            $pdo->commit();

            // Mostrar recibo en una ventana emergente
            echo "<script>
                alert('Pedido realizado con éxito. Total: $$total\\nMonto recibido: $$monto_recibido\\nCambio: $$cambio');
                window.location.href = 'view_order.php?id=$orden_id';
            </script>";
        } else {
            throw new Exception("El monto recibido es menor que el total del pedido.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='create_order.php';</script>";
    }
    exit;
}

// Obtener productos disponibles para el formulario
$stmt = $pdo->query("SELECT * FROM productos");
$productos_lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hacer Pedido</title>
</head>
<body>
    <h1>Hacer Pedido</h1>
    <form method="post" action="">
        <h2>Selecciona los productos</h2>
        <?php foreach ($productos_lista as $producto): ?>
            <div>
                <label>
                    <?= htmlspecialchars($producto['nombre']) ?> - $<?= number_format($producto['precio'], 2) ?> (Stock: <?= htmlspecialchars($producto['stock']) ?>)
                    <input type="number" name="productos[<?= htmlspecialchars($producto['id']) ?>]" min="0" value="0" style="width: 50px;">
                </label>
            </div>
        <?php endforeach; ?>
        <div>
            <label>
                Monto recibido:
                <input type="number" name="monto_recibido" step="0.01" min="0" required>
            </label>
        </div>
        <button type="submit">Realizar Pedido</button>
    </form>
    <p><a href="index.php">Volver</a></p>
</body>
</html>

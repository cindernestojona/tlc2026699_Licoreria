<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        echo 'Registro exitoso';
    } else {
        echo 'Error en el registro';
    }
}
?>

<form method="post" action="">
    <input type="text" name="username" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <select name="role">
        <option value="admin">Administrador</option>
        <option value="cliente">Cliente</option>
    </select>
    <button type="submit">Registrar</button>
</form>

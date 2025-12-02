<?php
include("database.php");

$user = "Kensoldier";
$pass = "Holamundonuevo7$";
$hash = password_hash($pass, PASSWORD_DEFAULT);
$nombre = "Super Admin";
$correo = "yo@taqueria.com";
$rol = "admin";


$sql = "INSERT INTO usuarios (username, password, nombre, correo, rol) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $user, $hash, $nombre, $correo, $rol);

if ($stmt->execute()) {
    echo "<h1>¡Éxito! Usuario creado.</h1>";
    echo "<p>Usuario: <b>$user</b></p>";
    echo "<p>Contraseña: <b>$pass</b></p>";
    echo "<br><a href='login.php'>Ir a Iniciar Sesión</a>";
} else {
    echo "Error: " . $stmt->error;
}
?>
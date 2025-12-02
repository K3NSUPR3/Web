<?php
include("database.php");

$username = 'admin';
$nombre = 'Administrador';
$correo = 'admin@taqueria.local';
$password_plain = 'Admin1234'; 

$hash = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (username,nombre,correo,password,rol) VALUES (?,?,?,?,?)");
$rol = 'admin';
$stmt->bind_param("sssss", $username, $nombre, $correo, $hash, $rol);
$ok = $stmt->execute();

if($ok) echo "Admin creado. Usuario: $username Contraseña: $password_plain";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?> 

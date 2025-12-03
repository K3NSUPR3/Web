<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../database.php");
session_start();


if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ 
    header("Location: ../login.php"); 
    exit; 
}


if(isset($_POST['crear'])){

    $username = $conn->real_escape_string(trim($_POST['username']));
    $nombre   = $conn->real_escape_string(trim($_POST['nombre']));
    $correo   = $conn->real_escape_string(trim($_POST['correo']));
    $password = $_POST['password'];
    $rol      = $conn->real_escape_string($_POST['rol']);

    if(strlen($username) < 3 || !filter_var($correo, FILTER_VALIDATE_EMAIL)){ 
        die("Error: Datos inválidos. <a href='usuarios_crear.php'>Volver</a>"); 
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (username, nombre, correo, password, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $nombre, $correo, $hash, $rol);

    try {
        $stmt->execute();
        // Si no falló, redirigir a la lista
        header("Location: usuarios_listar.php");
        exit;
    } catch (mysqli_sql_exception $e) {
   
        if ($e->getCode() == 1062) {
            echo "<h1> Error: Usuario duplicado</h1>";
            echo "<p>El usuario <b>'$username'</b> o el correo <b>'$correo'</b> ya están registrados.</p>";
            echo "<a href='usuarios_crear.php'>Intenta con otros datos</a>";
        } else {
            
            echo "<h1>Error de Base de Datos:</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    $stmt->close();
}

if(isset($_POST['actualizar'])){
    $id = intval($_POST['id']);
    $username = $conn->real_escape_string(trim($_POST['username']));
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $correo = $conn->real_escape_string(trim($_POST['correo']));
    $rol = $conn->real_escape_string($_POST['rol']);

    if(!empty($_POST['password'])){
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET username=?, nombre=?, correo=?, password=?, rol=? WHERE id=?");
        $stmt->bind_param("sssssi", $username, $nombre, $correo, $hash, $rol, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET username=?, nombre=?, correo=?, rol=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $nombre, $correo, $rol, $id);
    }

    try {
        $stmt->execute();
        header("Location: usuarios_listar.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<h1> Error: Datos duplicados al actualizar</h1>";
            echo "<p>Ese nombre de usuario o correo ya pertenece a otra persona.</p>";
            echo "<a href='usuarios_editar.php?id=$id'>Volver a intentar</a>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
    $stmt->close();
}
?>
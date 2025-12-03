<?php
session_start();
include("database.php");
if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ 

    die("
        <div style='text-align:center; padding:50px; font-family: Arial, sans-serif;'>
            <h2 style='color:red;'> Acceso Denegado</h2>
            <p style='font-size: 18px;'>No tienes permisos para modificar productos.</p>
            <p>Contacta al administrador si necesitas hacer cambios.</p>
            <br>
            <a href='productos.php' style='background: #d94f1f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Volver a Productos</a>
        </div>
    ");
}

if (isset($_POST['guardar'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $imagen = '';

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = time() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "img/" . $imagen);
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $imagen);
    $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit;
}


if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = $conn->real_escape_string($_POST['categoria']);

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = time() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "img/" . $imagen);
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $categoria, $imagen, $id);
    } else {
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=? WHERE id=?");
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $categoria, $id);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit;
}


if (isset($_POST['eliminar_id'])) {
    $id = intval($_POST['eliminar_id']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: productos.php");
    exit;
}
?>

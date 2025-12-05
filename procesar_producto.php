<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("database.php");


if(!isset($_SESSION['user']) || 
   ($_SESSION['user']['rol'] !== 'admin')){ 
    die("
        <div style='text-align:center; padding:50px; font-family: Arial, sans-serif;'>
            <h2 style='color:red;'>❌ Acceso Denegado</h2>
            <p style='font-size: 18px;'>No tienes permisos para modificar productos.</p>
            <p>Contacta al administrador si necesitas hacer cambios.</p>
            <br>
            <a href='productos.php' style='background: #d94f1f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Volver a Productos</a>
        </div>
    ");
}

if (isset($_POST['guardar'])) {
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $descripcion = $conn->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);
    $categoria = $conn->real_escape_string(trim($_POST['categoria']));
    $imagen = '';

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = time() . '_' . basename($_FILES['imagen']['name']);
        $upload_dir = "img/";
        
        // Crear directorio si no existe
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $imagen);
    }

    try {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $imagen);
        
        if($stmt->execute()) {
            $stmt->close();
            header("Location: productos.php");
            exit;
        } else {
            throw new Exception("Error al insertar: " . $stmt->error);
        }
    } catch (Exception $e) {
        die("Error al guardar producto: " . $e->getMessage() . "<br><a href='formulario.php'>Volver</a>");
    }
}


if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $descripcion = $conn->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);
    $categoria = $conn->real_escape_string(trim($_POST['categoria']));

    try {
        if (!empty($_FILES['imagen']['name'])) {
            $imagen = time() . '_' . basename($_FILES['imagen']['name']);
            $upload_dir = "img/";
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $imagen);
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, imagen=? WHERE id=?");
            $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $categoria, $imagen, $id);
        } else {
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=? WHERE id=?");
            $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $categoria, $id);
        }
        
        if($stmt->execute()) {
            $stmt->close();
            header("Location: productos.php");
            exit;
        } else {
            throw new Exception("Error al actualizar: " . $stmt->error);
        }
    } catch (Exception $e) {
        die("Error al actualizar producto: " . $e->getMessage() . "<br><a href='productos.php'>Volver</a>");
    }
}


if (isset($_POST['eliminar_id'])) {
    $id = intval($_POST['eliminar_id']);
    if ($id > 0) {
        try {
            $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if($stmt->execute()) {
                $stmt->close();
                header("Location: productos.php");
                exit;
            } else {
                throw new Exception("Error al eliminar: " . $stmt->error);
            }
        } catch (Exception $e) {
            die("Error al eliminar producto: " . $e->getMessage() . "<br><a href='productos.php'>Volver</a>");
        }
    }
}
?>
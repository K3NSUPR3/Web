<?php
// --- MODO DEPURACIÓN (Activalo si sigue fallando para ver el error real) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("../database.php");
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ 
    header("Location: ../login.php"); 
    exit; 
}

// CREAR USUARIO
if(isset($_POST['crear'])){
   
    $username = $conn->real_escape_string(trim($_POST['username']));
    $nombre   = $conn->real_escape_string(trim($_POST['nombre']));
    $correo   = $conn->real_escape_string(trim($_POST['correo']));
    $password = $_POST['password'];
    $rol      = $conn->real_escape_string($_POST['rol']);

    
    if(strlen($username) < 3 || !filter_var($correo, FILTER_VALIDATE_EMAIL)){ 
        die("Error: Datos inválidos (Usuario muy corto o correo mal formado). <a href='usuarios_crear.php'>Volver</a>"); 
    }

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO usuarios (username, nombre, correo, password, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $nombre, $correo, $hash, $rol);

    
    try {
        $stmt->execute();
        // Si funcionó, redirige
        header("Location: usuarios_listar.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<h1>Error: El usuario o el correo YA EXISTEN.</h1>";
            echo "<p>No se puede crear duplicados.</p>";
            echo "<a href='usuarios_crear.php'>Volver a intentar</a>";
        } else {
            // Otro error (conexión, permisos, etc.)
            echo "<h1>Ocurrió un error en la base de datos:</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    $stmt->close();
}
?>
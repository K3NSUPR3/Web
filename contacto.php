<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("database.php");
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Taquería</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>

    <?php include("header.php"); ?>
    <?php include("cabecera.php"); ?>

    <main class="contacto-section">
        <h1>Contáctanos</h1>
        <p>¿Tienes alguna duda o quieres hacer un pedido? ¡Escríbenos!</p>

        <?php
        if(isset($_POST['enviar'])){
            $nombre = trim($_POST['nombre']);
            $correo = trim($_POST['correo']);
            $mensaje = trim($_POST['mensaje']);

            if(empty($nombre) || empty($correo) || empty($mensaje)){
                echo "<p class='error'>⚠️ Por favor completa todos los campos</p>";
            } else {
                try {
                    // Preparar consulta para evitar inyección SQL
                    $stmt = $conn->prepare("INSERT INTO contacto(nombre, correo, mensaje, fecha) VALUES (?, ?, ?, NOW())");
                    
                    if(!$stmt){
                        throw new Exception("Error al preparar consulta: " . $conn->error);
                    }
                    
                    $stmt->bind_param("sss", $nombre, $correo, $mensaje);

                    if($stmt->execute()){
                        echo "<p class='success'> Mensaje enviado correctamente</p>";
                    } else {
                        throw new Exception("Error al ejecutar: " . $stmt->error);
                    }

                    $stmt->close();
                } catch(Exception $e) {
                    echo "<p class='error'> Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        }
        ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contacto-form">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="enviar">Enviar Mensaje</button>
            </div>
        </form>
    </main>
    
    <?php include("footer.php"); ?>
</body>
</html>
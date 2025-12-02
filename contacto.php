<?php include("database.php");
require_once 'auth_check.php';
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Taquería</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <?php include("header.php"); ?>

    <main class="contacto-section">
        <h1>📩 Contáctanos</h1>
        <p>¿Tienes alguna duda o quieres hacer un pedido? ¡Escríbenos!</p>

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

        <?php
        if(isset($_POST['enviar'])){
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $mensaje = $_POST['mensaje'];

            // Preparar consulta para evitar inyección SQL
            $stmt = $conn->prepare("INSERT INTO contacto(nombre, correo, mensaje, fecha) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $nombre, $correo, $mensaje);

            if($stmt->execute()){
                echo "<p class='success'> Mensaje enviado correctamente</p>";
            } else {
                echo "<p class='error'> Error: ".htmlspecialchars($stmt->error)."</p>";
            }

            $stmt->close();
        }
        ?>
    </main>

    <?php include("footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>

</body>
</html>

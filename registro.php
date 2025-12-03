<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

include("database.php");

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($username) || empty($correo) || empty($password)) {
        $msg = 'Por favor completa todos los campos.';
        $msg_type = 'error';
    } else {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE username = ? OR correo = ?");
        $check->bind_param("ss", $username, $correo);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = 'El usuario o correo ya están registrados.';
            $msg_type = 'error';
        } else {
            // Crear usuario
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $rol = 'usuario'; // Rol de cliente por defecto

            $stmt = $conn->prepare("INSERT INTO usuarios (username, password, nombre, correo, rol) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $hash, $nombre, $correo, $rol);

            if ($stmt->execute()) {
                $msg = '¡Cuenta creada con éxito! <br><a href="login.php">Inicia sesión aquí</a>';
                $msg_type = 'success';
            } else {
                $msg = 'Error: ' . $conn->error;
                $msg_type = 'error';
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Registro - Taquería</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="stylesheet" href="css/registro.css">
</head>
<body>
  <main class="login-page">
    <div class="login-card">
      <h2>Crear Cuenta</h2>
      <?php if($msg): ?>
        <div class="<?= $msg_type == 'error' ? 'msg-error' : 'msg-success' ?>"><?= $msg ?></div>
      <?php endif; ?>
      
      <?php if($msg_type !== 'success'): ?>
      <form method="POST" action="registro.php">
        <div class="form-row"><label>Nombre Completo</label><input type="text" name="nombre" required></div>
        <div class="form-row"><label>Usuario</label><input type="text" name="username" required></div>
        <div class="form-row"><label>Correo</label><input type="email" name="correo" required></div>
        <div class="form-row"><label>Contraseña</label><input type="password" name="password" required></div>
        <button class="btn-login" type="submit">Registrarse</button>
      </form>
      <?php endif; ?>
      
      <p style="margin-top: 15px; font-size: 14px;">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
      </p>
    </div>
  </main>
  <?php include("footer.php"); ?>
</body>
</html>
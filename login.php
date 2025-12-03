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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = 'Introduce usuario y contraseña.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, nombre, password, rol FROM usuarios WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $u = $res->fetch_assoc();
            if (password_verify($password, $u['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $u['id'],
                    'username' => $u['username'],
                    'nombre' => $u['nombre'],
                    'rol' => $u['rol']
                ];
                $stmt->close();
                header('Location: index.php');
                exit;
            } else {
                $msg = 'Usuario o contraseña incorrectos.';
            }
        } else {
            $msg = 'Usuario o contraseña incorrectos.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Iniciar sesión - Taquería El Buen Taco</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

  <main class="login-page">
    <div class="login-card" role="region" aria-label="Formulario de inicio de sesión">
      <div class="login-logo"> Taquería El Buen Taco</div>
      <h2>Iniciar sesión</h2>
      <p class="lead">Accede al panel para gestionar tu taquería</p>

      <?php if($msg): ?>
        <div class="msg" role="alert"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php" novalidate>
        <div class="form-row">
          <label for="username">Usuario</label>
          <input id="username" name="username" type="text" required autocomplete="username">
        </div>

        <div class="form-row">
          <label for="password">Contraseña</label>
          <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>

        <div class="form-row">
          <button class="btn-login" type="submit">Entrar</button>
        </div>
      </form>

     <div class="login-footer">
        <div style="margin-bottom: 15px; font-size: 14px;">
            ¿No tienes cuenta? <a href="registro.php" style="font-weight:bold;">Regístrate aquí</a>
        </div>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
        
        <small>¿Olvidaste tu contraseña? Contacta al administrador.</small>
        <div style="margin-top:8px;"><a href="index.php">Volver al inicio</a></div>
      </div> 
    </div>
  </main>

  <?php include("footer.php"); ?>

</body>
</html>
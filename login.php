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
  <link rel="stylesheet" href="estilos.css">
  <style>
    .login-page {
      min-height: calc(100vh - 100px);
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 30px 12px;
      background: linear-gradient(180deg, #fffef6 0%, #fff 100%);
    }
    .login-card {
      width: 360px;
      max-width: 96%;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      padding: 22px;
      text-align:center;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    .login-logo { font-size:22px; margin-bottom:10px; color:#d94f1f; font-weight:700; }
    .login-card h2 { margin:8px 0 14px; font-size:20px; color:#222; }
    .login-card p.lead { margin:0 0 14px; color:#555; font-size:14px; }
    .login-card form { text-align:left; }
    .form-row { margin-bottom:12px; }
    .form-row label { display:block; font-size:13px; margin-bottom:6px; color:#444; }
    .form-row input[type="text"],
    .form-row input[type="password"]{
      width:100%; padding:10px 12px; border:1px solid #e2e2e2; border-radius:8px; font-size:14px;
      box-sizing:border-box;
    }
    .btn-login {
      display:inline-block;
      width:100%;
      background:#d94f1f; color:#fff; border:none; padding:10px 12px; border-radius:8px; cursor:pointer;
      font-weight:600;
      box-shadow: 0 6px 18px rgba(217,79,31,0.18);
      transition: background .2s;
    }
    .btn-login:hover { background:#c64014; }
    .msg { color:#b00020; font-size:13px; margin:10px 0; text-align:center; }
    .login-footer { margin-top:12px; font-size:13px; color:#666; text-align:center; }
    .login-footer a { color:#d94f1f; text-decoration:none; font-weight:600; }
  </style>
</head>
<body>

  <main class="login-page">
    <div class="login-card" role="region" aria-label="Formulario de inicio de sesión">
      <div class="login-logo">🌮 Taquería El Buen Taco</div>
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
        <small>¿Olvidaste tu contraseña? Contacta al administrador.</small>
        <div style="margin-top:8px;"><a href="index.php">Volver al inicio</a></div>
      </div>
    </div>
  </main>

  <?php include("footer.php"); ?>

</body>
</html>
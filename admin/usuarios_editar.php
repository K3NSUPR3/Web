<?php
include("../database.php");
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ header("Location: ../login.php"); exit; }
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM usuarios WHERE id = $id");
if(!$res || $res->num_rows==0){ echo "Usuario no encontrado"; exit; }
$u = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Editar Usuario</title>
  <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
  <h1>Editar Usuario</h1>
  <form method="POST" action="usuarios_procesar.php">
    <input type="hidden" name="id" value="<?= $u['id'] ?>">
    <label>Usuario</label><br><input name="username" value="<?=htmlspecialchars($u['username'])?>" required><br>
    <label>Nombre</label><br><input name="nombre" value="<?=htmlspecialchars($u['nombre'])?>"><br>
    <label>Correo</label><br><input name="correo" type="email" value="<?=htmlspecialchars($u['correo'])?>" required><br>
    <label>Nueva contraseña (opcional)</label><br><input name="password" type="password"><br>
    <label>Rol</label><br>
    <select name="rol">
      <option value="usuario" <?= $u['rol']=='usuario' ? 'selected' : '' ?>>Usuario</option>
      <option value="empleado" <?= $u['rol']=='empleado' ? 'selected' : '' ?>>Empleado</option>
      <option value="admin" <?= $u['rol']=='admin' ? 'selected' : '' ?>>Administrador</option>
    </select><br><br>
    <button type="submit" name="actualizar">Actualizar</button>
  </form>
  <?php include("../footer.php"); ?>
</body>
</html>

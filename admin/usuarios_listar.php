<?php
include("../database.php");
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ header("Location: ../login.php"); exit; }

$res = $conn->query("SELECT id, username, nombre, correo, rol FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Usuarios</title>
  <link rel="stylesheet" href="../css/usuarios.css">
  <link rel="stylesheet" href="../css/tablaadmi.css">
</head>
<body>
  <h1>Usuarios</h1>
  <a href="usuarios_crear.php">Crear usuario</a> | <a href="../productos.php">Volver</a> | <a href="../logout.php">Cerrar sesión</a>
  <table cellpadding="6">
    <tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr>
    <?php while($u = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['correo']) ?></td>
        <td><?= $u['rol'] ?></td>
        <td>
          <a href="usuarios_editar.php?id=<?= $u['id'] ?>">Editar</a>
          <a href="usuarios_eliminar.php?id=<?= $u['id'] ?>" onclick="return confirm('Eliminar usuario?');">Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
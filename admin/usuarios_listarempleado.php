<?php
include("../database.php");
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'empleado'){ 
    header("Location: ../login.php"); 
    exit; 
}

$res = $conn->query("SELECT id, username, nombre, correo, rol, created_at FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Usuarios - Empleado</title>
  <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
  <h1>Lista de Usuarios</h1>
  <p style="margin: 10px 0; font-size: 14px; color: #666;">
    <strong>Rol:</strong> Empleado (solo lectura)
  </p>
  
  <div style="margin: 20px 0;">
    <a href="../productos.php" style="padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">← Volver</a>
    <a href="../logout.php" style="padding: 8px 16px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px;">Cerrar Sesión</a>
  </div>

  <table cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead style="background-color: #f8f9fa;">
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Fecha Registro</th>
      </tr>
    </thead>
    <tbody>
    <?php while($u = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['correo']) ?></td>
        <td>
          <span style="
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 12px;
            background-color: <?= $u['rol'] === 'admin' ? '#dc3545' : ($u['rol'] === 'empleado' ? '#ffc107' : '#28a745') ?>;
            color: <?= $u['rol'] === 'empleado' ? '#000' : '#fff' ?>;
          ">
            <?= ucfirst($u['rol']) ?>
          </span>
        </td>
        <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px;">
    <strong>ℹ️ Información:</strong> Como empleado, puedes ver la lista de usuarios pero no puedes crear, editar o eliminar usuarios.
  </div>
</body>
</html>
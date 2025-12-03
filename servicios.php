<?php
include("database.php");
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Servicios - Taquería</title>
  <link rel="stylesheet" href="css/estilos.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/main.js"></script>
</head>
<body>
  <?php include("header.php"); ?>
  <?php include("cabecera.php"); ?>

  <main>
    <h1>Servicios</h1>
    <section id="servicios-container" class="contenedor">
      <h2>Lista de Servicios</h2>
      <?php
      $sql = "SELECT * FROM servicios ORDER BY id DESC";
      $res = $conn->query($sql);
      if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $id = (int)$row['id'];
          $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');
          $descripcion = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
          $costo = number_format($row['costo'],2);
          echo "<article class='servicio' data-id='{$id}'>";
          echo "<h3 class='nombre'>{$nombre}</h3>";
          echo "<p class='descripcion'>{$descripcion}</p>";
          echo "<p class='precio'>\${$costo}</p>";
          echo "</article>";
        }
      } else {
        echo "<p>No hay servicios disponibles en este momento.</p>";
      }
      ?>
    </section>
  </main>

  <?php include("footer.php"); ?>
  
</body>
</html>

<?php
include("database.php");
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú - Taquería El Buen Taco</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>

  <?php include("header.php"); ?>

  <main>
    <section class="Menu-section">
      <h1>Menú</h1>

      <div class="contenedor" id="productos-container">
        <?php
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $id = (int)$row['id'];
                $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');
                $categoria = htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8');
                $descripcion = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
                $precio = number_format($row['precio'], 2);
                $imagen = htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8');
        ?>
                <article class="producto" data-id="<?= $id ?>">
                    <img src="img/<?= $imagen ?>" alt="<?= $nombre ?>" class="producto-img" width="180">
                    <h2 class="nombre"><?= $nombre ?></h2>
                    <p class="categoria"><b>Categoría:</b> <?= $categoria ?></p>
                    <p class="descripcion"><?= $descripcion ?></p>
                    <p class="precio"><b>Precio:</b> $<?= $precio ?></p>

                    <div class="acciones">
                        <a href="formularioEditar_producto.php?id=<?= $id ?>" class="btn btn-editar">✏️ Editar</a>

                        <form method="POST" action="procesar_producto.php" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');" style="display:inline;">
                            <input type="hidden" name="eliminar_id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-eliminar">🗑 Eliminar</button>
                        </form>
                    </div>
                </article>
        <?php
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        ?>
      </div>

      <div style="text-align:center; margin: 20px;">
        <a href="formulario.php" class="btn btn-agregar">➕ Agregar Producto</a>
      </div>
    </section>
  </main>

  <?php include("footer.php"); ?>

</body>
</html>

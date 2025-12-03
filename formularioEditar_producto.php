<?php
session_start(); 
include("database.php");
$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id=$id";
$res = $conn->query($sql);
$producto = $res->fetch_assoc();

$esAdmin = isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/formularios.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="js/validaciones.js"></script>
</head>
<body>
    <h1>Editar Producto</h1>
    
    
    <?php if (!$esAdmin): ?>
    <div class="advertencia" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 15px 0; border-radius: 8px; color: #856404; font-weight: bold;">
         <strong>MODO SOLO LECTURA</strong> - No tienes permisos para modificar productos
    </div>
    <?php endif; ?>
 
    
    <form method="POST" action="procesar_producto.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" <?php if (!$esAdmin) echo 'readonly'; ?> required>

        <label>Descripción:</label>
        <textarea name="descripcion" <?php if (!$esAdmin) echo 'readonly'; ?> required><?php echo $producto['descripcion']; ?></textarea>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" <?php if (!$esAdmin) echo 'readonly'; ?> required>

        <label>Categoría:</label>
        <input type="text" name="categoria" value="<?php echo $producto['categoria']; ?>" <?php if (!$esAdmin) echo 'readonly'; ?> required>

        <label>Imagen:</label>
        <input type="file" name="imagen" <?php if (!$esAdmin) echo 'disabled'; ?>>

        <button type="submit" name="actualizar" <?php if (!$esAdmin) echo 'disabled'; ?>>
            <?php echo $esAdmin ? 'Actualizar' : ' Sin permisos'; ?>
        </button>
    </form>
    <footer>
        <p style="text-align:center; margin-top:10px;">
            <a href="https://validator.w3.org/check?uri=referer">
                <img src="https://www.w3.org/Icons/valid-html401"
                    alt="¡HTML Válido!" style="border:0; width:88px; height:31px;">
            </a> 
        </p>
        <p>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px"
                    src="https://jigsaw.w3.org/css-validator/images/vcss"
                    alt="¡CSS Válido!">
            </a>
        </p>
        <p>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px"
                    src="https://jigsaw.w3.org/css-validator/images/vcss-blue"
                    alt="¡CSS Válido!">
            </a>
        </p>
    </footer>
</body>
</html>
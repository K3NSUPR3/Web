<?php
// AJAX para sugerencias de platillos
if (isset($_GET['q'])) {
    $platillos = ['Taco al pastor', 'Taco de suadero', 'Taco de tripa', 'Gringa', 'Quesadilla', 'Torta de chorizo', 'Burrito', 'Taco de carnitas', 'Taco de barbacoa', 'Taco de lengua'];
    $q = strtolower($_GET['q']);
    $sugerencias = [];

    foreach ($platillos as $p) {
        if (strpos(strtolower($p), $q) !== false) {
            $sugerencias[] = $p;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($sugerencias);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Productos</title>
    <link rel="stylesheet" href="css/formularios.css">
</head>
<body>
    <?php include("cabecera.php"); ?>
    <h1>Formulario de Alta de Producto</h1>

    <form method="POST" action="procesar_producto.php" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" id="nombre" required autocomplete="off">
        <div id="sugerencias" class="sugerencias-box"></div><br><br>

        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" required>

        <label>Categoría:</label>
        <input type="text" name="categoria" required>

        <label>Imagen:</label>
        <input type="file" name="imagen">

        <div class="acciones-form" style="margin-top: 20px;">
            <button type="submit" name="guardar">Guardar</button>
            
            <a href="productos.php" style="
                display: inline-block;
                padding: 10px 20px;
                background-color: #6c757d;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-left: 10px;
                font-family: Arial, sans-serif;
            ">Cancelar / Volver</a>
        </div>
    </form>
    <script>
     document.getElementById('nombre').addEventListener('input', function() {
        const query = this.value;
        if (query.length < 2) {
            document.getElementById('sugerencias').innerHTML = '';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', '?q=' + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const resultados = JSON.parse(xhr.responseText);
                let html = '';
                resultados.forEach(function(p) {
                    html += '<div class="sugerencia-item">' + p + '</div>';
                });
                document.getElementById('sugerencias').innerHTML = html;

                document.querySelectorAll('.sugerencia-item').forEach(function(item) {
                    item.addEventListener('click', function() {
                        document.getElementById('nombre').value = this.textContent;
                        document.getElementById('sugerencias').innerHTML = '';
                    });
                });
            }
        };
        xhr.send();
     });
    </script>
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
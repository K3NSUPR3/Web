<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Taquería El Buen Taco</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .barra-sesion {
            text-align: right;
            background-color: #f4f4f4;
            padding: 10px 20px;
            font-family: Arial, sans-serif;
            font-size: 15px;
            border-bottom: 1px solid #ddd;
        }
        .barra-sesion a {
            color: #d94f1f;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
            padding: 4px 8px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        .barra-sesion a:hover {
            text-decoration: none;
            background-color: #e8e8e8;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="barra-sesion">
            Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']['nombre']) ?></strong> |
            
            <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                <a href="admin/usuarios_listar.php">Administración</a> |
            <?php endif; ?>

            <?php if ($_SESSION['user']['rol'] === 'empleado'): ?>
                <a href="admin/usuarios_listarempleado.php">Ver tablas</a> |
            <?php endif; ?>
            
            <a href="logout.php">Cerrar sesión</a>
        </div>
    <?php endif; ?>

<main>
    <h1>Bienvenido a la Taquería El Buen Taco</h1>

    <section class="contenedor-tarjetas">
        <h2>Nuestra Información</h2> 
        <article class="tarjeta">
            <h3>Misión</h3>
            <p>Ofrecer los mejores tacos con ingredientes frescos y auténticos.</p>
        </article>

        <article class="tarjeta">
            <h3>Visión</h3>
            <p>Ser la taquería número uno en la ciudad.</p>
        </article>

        <article class="tarjeta">
            <h3>Contacto</h3>
            <p>📍 Dirección: Av. Madero #123, CDMX</p>
            <p>Tel: 55-1234-5678 | ✉ contacto@elbuentaco.com</p>
        </article>
    </section>
</main>

    <?php include("footer.php"); ?>
</body>
</html>
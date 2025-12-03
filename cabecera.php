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
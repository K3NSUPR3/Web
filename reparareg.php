<?php
// Activar reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);


include("database.php");

echo "<h1>🛠️ Reparando Base de Datos...</h1>";

// Lista de tablas que deben tener auto-increment
$tablas = ['usuarios', 'productos', 'servicios', 'clientes', 'contacto'];

foreach ($tablas as $tabla) {
    // Comando para activar AUTO_INCREMENT en la columna 'id'
    $sql = "ALTER TABLE `$tabla` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Tabla <b>$tabla</b>: Auto-increment ACTIVADO correctamente.</p>";
    } else {
        echo "<p>❌ Tabla <b>$tabla</b>: Error - " . $conn->error . "</p>";
    }
}

echo "<hr><h3>¡Listo! Ahora intenta registrarte de nuevo.</h3>";
echo "<a href='registro.php'>Ir a Registro</a>";
?>
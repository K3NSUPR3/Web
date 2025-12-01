<?php
if (empty($_SERVER['HTTPS']) && $_SERVER['HTTP_HOST'] == 'localhost') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}


$servername = getenv('DB_HOST') !== false ? getenv('DB_HOST') : "localhost";
$username   = getenv('DB_USER') !== false ? getenv('DB_USER') : "root";
$password   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$dbname     = getenv('DB_NAME') !== false ? getenv('DB_NAME') : "taqueria";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>

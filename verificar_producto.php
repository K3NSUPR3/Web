<?php
include("database.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if(isset($_POST['nombre'])) {
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    
    $stmt = $conn->prepare("SELECT id FROM productos WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $stmt->store_result();
    
    $existe = $stmt->num_rows > 0;
    $stmt->close();
    
    echo json_encode(['existe' => $existe]);
    exit;
}

echo json_encode(['existe' => false]);
?>
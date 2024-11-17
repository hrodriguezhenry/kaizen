<?php

$host = "localhost";
$dbname = "kaizen_dev";
$username = "root";
$password = "admin";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Establece el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

?>
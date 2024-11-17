<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $active = $_POST["active"];
    $created_by = 2;
    $updated_by = 2;

    try {
        $sql = "INSERT INTO habit (name, active, created_by, updated_by) VALUES (:name, :active, :created_by, :updated_by);";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':active', $active);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->bindParam(':updated_by', $updated_by);
        $stmt->execute();
        // Redirigir a leer.php después de guardar
        header("Location: read.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
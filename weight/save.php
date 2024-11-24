<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $weight = $_POST["weight"];
    $unit_detail_id = $_POST["unit_detail"];
    $active = $_POST["active"];
    $created_by = 2;
    $updated_by = 2;

    try {
        $sql = 
            "INSERT INTO weight (name, weight, unit_detail_id, active, created_by, updated_by)
            VALUES (:name, :weight, :unit_detail_id, :active, :created_by, :updated_by);
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':unit_detail_id', $unit_detail_id);
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
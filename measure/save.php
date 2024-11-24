<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 2;
    $body_measure_id = $_POST["body_measure"];
    $unit_detail_id = $_POST["unit_detail"];
    $value = $_POST["value"];
    $date = $_POST["date"];
    $created_by = 2;
    $updated_by = 2;

    try {
        // Verificar si ya existe un registro con la misma combinación de usuario, medida del cuerpo y fecha
        $checkSql =
            "SELECT COUNT(*) as count 
            FROM measure 
            WHERE user_id = :user_id 
            AND body_measure_id = :body_measure_id 
            AND date = :date
            AND deleted_at IS NULL;
        ";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->bindParam(':body_measure_id', $body_measure_id);
        $checkStmt->bindParam(':date', $date);
        $checkStmt->execute();

        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Si existe un registro duplicado, redirigir con un mensaje de error
            header("Location: create.php?error=duplicate");
            exit();
        }

        // Insertar los datos si no hay duplicado
        $sql = 
            "INSERT INTO measure (user_id, body_measure_id, unit_detail_id, value, date, created_by, updated_by)
            VALUES (:user_id, :body_measure_id, :unit_detail_id, :value, :date, :created_by, :updated_by);
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':body_measure_id', $body_measure_id);
        $stmt->bindParam(':unit_detail_id', $unit_detail_id);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->bindParam(':updated_by', $updated_by);
        $stmt->execute();

        // Redirigir después de guardar
        header("Location: read.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
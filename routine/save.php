<?php
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 2; 
    $exercises = $_POST["exercise"];
    $weights = $_POST["weight"];
    $sets = $_POST["sets"];
    $repetitions = $_POST["repetitions"];
    $date = $_POST["date"];
    $created_by = 2; 
    $updated_by = 2; 

    try {
        $conn->beginTransaction(); // Start a transaction

        for ($i = 0; $i < count($exercises); $i++) {
            $exercise_id = $exercises[$i];
            $weight_id = $weights[$i];
            $set = $sets[$i];
            $repetition = $repetitions[$i];

            // Verificar si ya existe un registro con la misma combinación de usuario, ejercicio y fecha
            $checkSql = "SELECT COUNT(*) as count 
                         FROM routine 
                         WHERE user_id = :user_id 
                         AND exercise_id = :exercise_id 
                         AND date = :date
                         AND deleted_at IS NULL;";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':user_id', $user_id);
            $checkStmt->bindParam(':exercise_id', $exercise_id);
            $checkStmt->bindParam(':date', $date);
            $checkStmt->execute();

            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // If a duplicate is found, rollback the transaction and redirect with an error
                $conn->rollBack();
                header("Location: create.php?error=duplicate&exercise_id=" . $exercise_id . "&date=" . $date); 
                exit();
            }

            // Insertar los datos si no hay duplicado
            $sql = "INSERT INTO routine (user_id, exercise_id, weight_id, sets, repetitions, date, created_by, updated_by) 
                    VALUES (:user_id, :exercise_id, :weight_id, :sets, :repetitions, :date, :created_by, :updated_by);";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':exercise_id', $exercise_id);
            $stmt->bindParam(':weight_id', $weight_id);
            $stmt->bindParam(':sets', $set);
            $stmt->bindParam(':repetitions', $repetition);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':created_by', $created_by);
            $stmt->bindParam(':updated_by', $updated_by);
            $stmt->execute();
        }

        $conn->commit(); // Commit the transaction if all insertions were successful

        // Redirigir después de guardar
        header("Location: read.php?filter_date=" . $date);
        exit();
    } catch (PDOException $e) {
        $conn->rollBack(); // Rollback the transaction in case of an error
        echo "Error: " . $e->getMessage();
    }
}
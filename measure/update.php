<?php include '../includes/header.php';
if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    echo '<div class="alert alert-danger" role="alert">Ya existe una medida para esta fecha y tipo.</div>';
}
?>
<div class="container">
    <h1>Editar Medida</h1>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $sql =
                "SELECT m.id,
                    bm.id AS body_measure_id,
                    bm.`name` AS body_measure_name,
                    ud.id AS unit_detail_id,
                    ud.`name` AS unit_detail_name,
                    m.`value`,
                    m.date
                FROM measure AS m
                LEFT JOIN body_measure AS bm
                ON m.body_measure_id = bm.id
                AND bm.deleted_at IS NULL
                LEFT JOIN unit_detail AS ud
                ON m.unit_detail_id = ud.id
                AND ud.deleted_at IS NULL
                WHERE bm.deleted_at IS NULL
                AND m.id = :id;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        $user_id = 2;
        $body_measure_id = $_POST["body_measure"];
        $unit_detail_id = $_POST["unit_detail"];
        $value = $_POST["value"];
        $date = $_POST["date"];
        $current_date = $_POST["current_date"];
        $updated_by = 2;

        try {
            // Verificar si ya existe un registro con la misma combinación de usuario, medida del cuerpo y fecha
            $checkSql =
                "SELECT COUNT(*) as count 
                FROM measure 
                WHERE user_id = :user_id 
                AND body_measure_id = :body_measure_id 
                AND date = :date
                AND id != :id
                AND deleted_at IS NULL;
            ";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->bindParam(':user_id', $user_id);
            $checkStmt->bindParam(':body_measure_id', $body_measure_id);
            $checkStmt->bindParam(':date', $date);
            $checkStmt->execute();

            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // Si existe un registro duplicado, redirigir con un mensaje de error
                header("Location: update.php?id=" . $id . "&error=duplicate");
                exit();
            }

            $sql =
                "UPDATE measure
                SET user_id = :user_id,
                body_measure_id = :body_measure_id,
                unit_detail_id = :unit_detail_id,
                value = :value,
                date = :date,
                updated_by = :updated_by
                WHERE id = :id;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':body_measure_id', $body_measure_id);
            $stmt->bindParam(':unit_detail_id', $unit_detail_id);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':updated_by', $updated_by);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: read.php");
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <div class="form-group mb-1">
            <label for="body_measure">Unidad:</label>
            <select class="form-control form-select" id="body_measure" name="body_measure" required>
                <?php
                try {
                    $sql = "SELECT id, name FROM body_measure WHERE deleted_at IS NULL AND active = 1;"; 
                    $stmt = $conn->query($sql);

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($row['id'] == $data['body_measure_id']) ? 'selected' : ''; 
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>"; 
                        }
                    } else {
                        echo "<option value='' disabled selected>Sin opciones</option>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>
        </div>
        <div class="form-group mb-1">
            <label for="unit_detail">Unidad de Medida Detalle:</label>
            <select class="form-control form-select" id="unit_detail" name="unit_detail" required>
                <?php
                try {
                    $sql = "SELECT id, name FROM unit_detail WHERE deleted_at IS NULL AND active = 1;"; 
                    $stmt = $conn->query($sql);

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($row['id'] == $data['unit_detail_id']) ? 'selected' : ''; 
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>"; 
                        }
                    } else {
                        echo "<option value='' disabled selected>Sin opciones</option>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>
        </div>
        <div class="form-group mb-1">
            <label for="value">Valor:</label>
            <input type="number" class="form-control" id="value" name="value" step="0.01" min="1" value="<?php echo $data['value']; ?>" required>
        </div>
        <div class="form-group mb-1">
            <label for="date">Fecha:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $data['date']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
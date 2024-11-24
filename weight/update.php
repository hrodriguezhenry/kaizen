<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Editar Peso</h1>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $sql =
                "SELECT w.id,
                    w.`name`,
                    w.weight,
                    ud.id AS unit_detail_id,
                    ud.`name` AS unit_name,
                    w.`active`
                FROM weight AS w
                LEFT JOIN unit_detail AS ud
                ON w.unit_detail_id = ud.id
                AND ud.deleted_at IS NULL
                WHERE w.deleted_at IS NULL
                AND w.id = :id;
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
        $name = $_POST["name"];
        $weight = $_POST["weight"];
        $unit_detail_id = $_POST["unit_detail"];
        $active = $_POST["active"];
        $updated_by = 2;

        try {
            $sql = "UPDATE weight SET name = :name, weight = :weight, unit_detail_id = :unit_detail_id, active = :active, updated_by = :updated_by WHERE id = :id;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':unit_detail_id', $unit_detail_id);
            $stmt->bindParam(':active', $active);
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
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>" required>
        </div>
        <div class="form-group mb-1">
            <label for="weight">Peso:</label>
            <input type="number" class="form-control" id="weight" name="weight" value="<?php echo $data['weight']; ?>" step="0.01" min="1" required>
        </div>
        <div class="form-group mb-1">
            <label for="unit_detail">Unidad:</label>
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
            <label for="active">Estado:</label>
            <select class="form-control form-select" id="active" name="active" required>
                <option value="1" <?php echo ($data['active'] == 1) ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo ($data['active'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
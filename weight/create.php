<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Crear Peso</h1>
    <form action="<?php echo htmlspecialchars('save.php'); ?>" method="post">
        <div class="form-group mb-1">
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group mb-1">
            <label for="weight">Peso:</label>
            <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="1" required>
        </div>
        <div class="form-group mb-1">
            <label for="unit_detail">Unidad Detalle:</label>
            <select class="form-control form-select" id="unit_detail" name="unit_detail" required>
                <?php
                try {
                    $sql = "SELECT id, name FROM unit_detail WHERE deleted_at IS NULL AND active = 1;";
                    $stmt = $conn->query($sql);
                    
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>"; 
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
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
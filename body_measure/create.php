<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Crear Medida de Cuerpo</h1>
    <form action="<?php echo htmlspecialchars('save.php'); ?>" method="post">
        <div class="form-group mb-1">
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group mb-1">
            <label for="unit">Unidad:</label>
            <select class="form-control form-select" id="unit" name="unit" required>
                <?php
                try {
                    $sql = "SELECT id, name FROM unit WHERE deleted_at IS NULL;";
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
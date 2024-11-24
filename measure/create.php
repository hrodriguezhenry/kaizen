<?php include '../includes/header.php';
if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    echo '<div class="alert alert-danger" role="alert">Ya existe una medida para esta fecha y tipo.</div>';
}
?>
<div class="container">
    <h1>Ingresar Medida</h1>
    <form action="<?php echo htmlspecialchars('save.php'); ?>" method="post">
        <div class="form-group mb-1">
            <label for="body_measure">Medida del Cuerpo:</label>
            <select class="form-control form-select" id="body_measure" name="body_measure" required>
                <?php
                try {
                    $sql = "SELECT id, name FROM body_measure WHERE deleted_at IS NULL AND active = 1;";
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
            <label for="unit_detail">Unidad de Medida Detalle:</label>
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
            <label for="value">Valor:</label>
            <input type="number" class="form-control" id="value" name="value" step="0.01" min="1" required>
        </div>
        <div class="form-group mb-1">
            <label for="date">Fecha:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
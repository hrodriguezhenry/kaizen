<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Editar Unidad de Medida</h1>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $sql = "SELECT * FROM unit WHERE deleted_at IS NULL AND id = :id;";
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
        $active = $_POST["active"];
        $updated_by = 2;

        try {
            $sql = "UPDATE unit SET name = :name, active = :active, updated_by = :updated_by WHERE id = :id;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
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
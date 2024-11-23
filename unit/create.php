<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Crear Unidad de Medida</h1>
    <form action="<?php echo htmlspecialchars('save.php'); ?>" method="post">
        <div class="form-group mb-1">
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
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
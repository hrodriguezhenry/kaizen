    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Crear Usuario</h1>
        <form action="<?php echo htmlspecialchars('guardar.php'); ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
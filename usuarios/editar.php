    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Editar Usuario</h1>
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            try {
                $sql = "SELECT * FROM usuarios WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
      	}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$id = $_POST["id"];
			$nombre = $_POST["nombre"];
			$email = $_POST["email"];

			try {
				$sql = "UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':nombre', $nombre);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':id', $id);
				$stmt->execute();
				header("Location: leer.php");
				exit();
			} catch(PDOException $e) {
				echo "Error: " . $e->getMessage();
			}
		}
		?>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
			<div class="form-group">
				<label for="nombre">Nombre:</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
			</div>
			<button type="submit" class="btn btn-primary mt-2">Actualizar</button>
		</form>
    </div>

    <?php include '../includes/footer.php'; ?>
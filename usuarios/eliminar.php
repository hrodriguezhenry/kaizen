<?php
require_once '../includes/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar si se ha enviado el formulario de confirmación
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
			$sql = "DELETE FROM usuarios WHERE id = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			// Redirigir a leer.php después de eliminar
			header("Location: leer.php");
			exit();
      } catch(PDOException $e) {
          	echo "Error: " . $e->getMessage();
      }
    } else { 
      // Mostrar formulario de confirmación
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
            <?php include '../includes/header.php'; ?>  

            <div class="container">
                <h1>Eliminar Usuario</h1>
                <p>¿Estás seguro de que quieres eliminar al usuario: <?php echo $usuario['nombre']; ?>?</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=".$id; ?>" method="post">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <a href="leer.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <?php include '../includes/footer.php'; ?>
        <?php
    }
}
?>
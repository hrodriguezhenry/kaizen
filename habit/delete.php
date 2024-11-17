<?php
require_once '../includes/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleted_by = 2;

    // Verificar si se ha enviado el formulario de confirmación
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
			$sql = "UPDATE habit SET deleted_at = CURRENT_TIMESTAMP(), deleted_by = :deleted_by WHERE id = :id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':deleted_by', $deleted_by);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			// Redirigir a leer.php después de eliminar
			header("Location: read.php");
			exit();
      } catch(PDOException $e) {
          	echo "Error: " . $e->getMessage();
      }
    } else { 
      // Mostrar formulario de confirmación
        try {
            $sql = "SELECT * FROM habit WHERE id = :id";
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
                <h1>Eliminar Hábito</h1>
                <p>¿Estás seguro de que quieres eliminar el ejercicio: <?php echo $usuario['name']; ?>?</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=".$id; ?>" method="post">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <a href="read.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <?php include '../includes/footer.php'; ?>
        <?php
    }
}
?>
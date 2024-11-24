<?php
require_once '../includes/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleted_by = 2;

    // Verificar si se ha enviado el formulario de confirmación
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
			$sql = "UPDATE measure SET deleted_at = CURRENT_TIMESTAMP(), deleted_by = :deleted_by WHERE id = :id;";
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
            $sql =
                "SELECT m.id,
                    bm.id AS body_measure_id,
                    bm.`name` AS body_measure_name,
                    ud.id AS unit_detail_id,
                    ud.`name` AS unit_detail_name,
                    m.`value`,
                    m.date
                FROM measure AS m
                LEFT JOIN body_measure AS bm
                ON m.body_measure_id = bm.id
                AND bm.deleted_at IS NULL
                LEFT JOIN unit_detail AS ud
                ON m.unit_detail_id = ud.id
                AND ud.deleted_at IS NULL
                WHERE bm.deleted_at IS NULL
                AND m.id = :id;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
            <?php include '../includes/header.php'; ?>  

            <div class="container">
                <h1>Eliminar Medida</h1>
                <p>¿Estás seguro de que quieres eliminar la medida?</p>
                <span>Medida del Cuerpo: <strong><?php echo $data['body_measure_name']; ?></strong></span><br>
                <span>Unidad de Medida Det: <strong><?php echo $data['unit_detail_name']; ?></strong></span><br>
                <span>Valor: <strong><?php echo $data['value']; ?></strong></span><br>
                <span>Fecha: <strong><?php echo $data['date']; ?></strong></span><br><br>
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
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Listar Usuarios</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
			<tbody>
				<?php
				try {
					$sql = "SELECT * FROM usuarios";
					$stmt = $conn->query($sql);
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						echo "<tr>";
						echo "<td>" . $row['id'] . "</td>";
						echo "<td>" . $row['nombre'] . "</td>";
						echo "<td>" . $row['email'] . "</td>";
						echo "<td>";
						echo "<a href='editar.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
						echo "<a href='eliminar.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>";
						echo "</td>";
						echo "</tr>";
					}
				} catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
				?>
			</tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
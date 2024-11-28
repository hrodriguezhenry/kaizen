<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Listar Rutina</h1>
    
    <!-- Filtro por fecha -->
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="filter_date">Filtrar por fecha:</label>
            <input type="date" name="filter_date" id="filter_date" class="form-control" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>"">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
        <a href="read.php" class="btn btn-secondary mt-2">Limpiar</a> 
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ejercicio</th>
                <th>Peso</th>
                <th>Series</th>
                <th>Repeticiones</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                // Obtenemos el valor de la fecha si está presente en el filtro
                $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

                $sql = "SELECT r.id,
                            e.id AS exercise_id,
                            e.`name` AS exercise_name,
                            w.id AS weight_id,
                            w.`name` AS weight_name,
                            r.sets,
                            r.repetitions,
                            r.date
                        FROM routine AS r
                        LEFT JOIN exercise AS e
                        ON r.exercise_id = e.id
                        AND e.deleted_at IS NULL
                        LEFT JOIN weight AS w
                        ON r.weight_id = w.id
                        AND w.deleted_at IS NULL
                        WHERE r.deleted_at IS NULL";

                // Si se ha seleccionado una fecha, agregamos la condición al WHERE
                if (!empty($filterDate)) {
                    $sql .=
                        " AND r.date = :filter_date
                        ORDER BY r.date DESC
                        LIMIT 100";
                } else {
                    $sql .= " ORDER BY r.date DESC LIMIT 100";
                }

                $stmt = $conn->prepare($sql);

                // Si se aplica el filtro, lo vinculamos
                if (!empty($filterDate)) {
                    $stmt->bindParam(':filter_date', $filterDate);
                }

                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['exercise_name'] . "</td>";
                    echo "<td>" . $row['weight_name'] . "</td>";
                    echo "<td>" . $row['sets'] . "</td>";
                    echo "<td>" . $row['repetitions'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>";
                    echo "<a href='update.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
                    echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>";
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
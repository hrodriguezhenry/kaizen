<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Listar Medidas</h1>
    
    <!-- Filtro por fecha -->
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="filter_date">Filtrar por fecha:</label>
            <input type="date" name="filter_date" id="filter_date" class="form-control" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>"">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Medida del Cuerpo</th>
                <th>Unidad de Medida Det</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                // Obtenemos el valor de la fecha si está presente en el filtro
                $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

                $sql = "SELECT m.id,
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
                        WHERE m.deleted_at IS NULL";

                // Si se ha seleccionado una fecha, agregamos la condición al WHERE
                if (!empty($filterDate)) {
                    $sql .=
                        " AND m.date = :filter_date
                        ORDER BY m.date DESC";
                } else {
                    $sql .= " ORDER BY m.date DESC";
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
                    echo "<td>" . $row['body_measure_name'] . "</td>";
                    echo "<td>" . $row['unit_detail_name'] . "</td>";
                    echo "<td>" . $row['value'] . "</td>";
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
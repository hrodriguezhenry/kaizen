<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Listar Pesos</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Peso</th>
                <th>Unidad Detalle</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql =
                    "SELECT w.id,
                        w.`name`,
                        w.weight,
                        ud.id AS unit_detail_id,
                        ud.`name` AS unit_detail_name,
                        w.`active`
                    FROM weight AS w
                    LEFT JOIN unit_detail AS ud
                    ON w.unit_detail_id = ud.id
                    AND ud.deleted_at IS NULL
                    WHERE w.deleted_at IS NULL;
                ";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['unit_detail_name'] . "</td>";
                    echo "<td>" . (($row['active'] == 1) ? 'Activo' : 'Inactivo') . "</td>";
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
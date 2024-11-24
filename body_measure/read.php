<?php include '../includes/header.php'; ?>
<div class="container">
    <h1>Listar Medidas de Cuerpo</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql =
                    "SELECT bm.id,
                        bm.`name`,
                        u.id AS unit_id,
                        u.`name` AS unit_name,
                        bm.`active`
                    FROM body_measure AS bm
                    LEFT JOIN unit AS u
                    ON bm.unit_id = u.id
                    AND u.deleted_at IS NULL
                    WHERE bm.deleted_at IS NULL;
                ";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['unit_name'] . "</td>";
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
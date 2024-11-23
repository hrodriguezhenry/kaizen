<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Listar Unidades de Medida Detalle</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Abreviatura</th>
                <th>Unidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql =
                    "SELECT ud.id,
                        ud.`name`,
                        ud.abbreviation,
                        u.id AS unit_id,
                        u.`name` AS unit_name,
                        ud.`active`
                    FROM unit_detail AS ud
                    LEFT JOIN unit AS u
                    ON ud.unit_id = u.id
                    AND u.deleted_at IS NULL
                    WHERE ud.deleted_at IS NULL;
                ";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['abbreviation'] . "</td>";
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
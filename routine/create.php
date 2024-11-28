<?php include '../includes/header.php';

if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    $duplicateExerciseId = $_GET['exercise_id'];
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    try {
        $sql =
            "SELECT e.`name`
            FROM exercise AS e
            WHERE e.deleted_at IS NULL
            AND e.id = :id;
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $duplicateExerciseId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo '<div class="alert alert-danger" role="alert">
            Ya existe una rutina para el ejercicio "' . $data['name'] . '" en esta fecha: ' . $date . '
        </div>';
}
?>
<div class="container">
    <h1>Ingresar Rutina</h1>
    <form action="<?php echo htmlspecialchars('save.php'); ?>" method="post">
        <div class="row">
            <div class="col-auto"> 
                <div class="form-group mb-1">
                    <label for="date">Fecha:</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= $date; ?>" required>
                </div>
            </div>
        </div>
        <div id="exerciseRows">
            <div class="row mb-1 align-items-end">
                <div class="col-md-3">
                    <label for="exercise">Ejercicio:</label>
                    <select class="form-control form-select exercise-select" name="exercise[]" required>
                        <option value="" disabled selected>Selecciona un ejercicio</option>
                        <?php
                        try {
                            $sql = "SELECT id, name FROM exercise WHERE deleted_at IS NULL AND active = 1;";
                            $stmt = $conn->query($sql);

                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            } 
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="weight">Peso:</label>
                    <select class="form-control form-select" name="weight[]" required>
                        <option value="" disabled selected>Selecciona un peso</option>
                        <?php
                        try {
                            $sql = "SELECT id, name FROM weight WHERE deleted_at IS NULL AND active = 1;";
                            $stmt = $conn->query($sql);

                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            } 
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sets">Series:</label>
                    <input type="number" class="form-control" name="sets[]" min="1" required>
                </div>
                <div class="col-md-2">
                    <label for="repetitions">Repeticiones:</label>
                    <input type="number" class="form-control" name="repetitions[]" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeRow">Eliminar</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mt-2" id="addRow">Agregar</button>
        <button type="submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
</div>

<script>
    document.getElementById('addRow').addEventListener('click', function() {
        let exerciseRows = document.getElementById('exerciseRows');
        let newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-1', 'align-items-end');
        newRow.innerHTML = `
            <div class="col-md-3">
                <select class="form-control form-select exercise-select" name="exercise[]" required>
                    <option value="" disabled selected>Selecciona un ejercicio</option>
                    <?php
                    try {
                        $sql = "SELECT id, name FROM exercise WHERE deleted_at IS NULL AND active = 1;";
                        $stmt = $conn->query($sql);

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                        } 
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control form-select" name="weight[]" required>
                    <option value="" disabled selected>Selecciona un peso</option>
                    <?php
                    try {
                        $sql = "SELECT id, name FROM weight WHERE deleted_at IS NULL AND active = 1;";
                        $stmt = $conn->query($sql);

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                        } 
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="sets[]" min="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="repetitions[]" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeRow">Eliminar</button>
            </div>
        `;
        exerciseRows.appendChild(newRow);
        updateExerciseOptions(); // Update options after adding a new row
    });

    document.getElementById('exerciseRows').addEventListener('click', function(event) {
        if (event.target.classList.contains('removeRow')) {
            event.target.closest('.row').remove();
            updateExerciseOptions(); // Update options after removing a row
        }
    });

    document.getElementById('exerciseRows').addEventListener('change', function(event) {
        if (event.target.classList.contains('exercise-select')) {
            updateExerciseOptions();
        }
    });

    function updateExerciseOptions() {
        let exerciseSelects = document.querySelectorAll('.exercise-select');
        let selectedExercises = [];

        exerciseSelects.forEach(select => {
            if (select.value) {
                selectedExercises.push(select.value);
            }
        });

        exerciseSelects.forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value && selectedExercises.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else if (option.value) { 
                    option.disabled = false;
                }
            });
        });
    }
</script>

<?php include '../includes/footer.php'; ?>
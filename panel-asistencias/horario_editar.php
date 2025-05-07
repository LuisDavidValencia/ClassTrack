<?php
require_once 'config.php';
require_once 'includes/header.php';

$id_horario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_horario <= 0) {
    header("Location: horarios.php");
    exit;
}

$horario = $conn->query("SELECT * FROM horarios WHERE id_horario = $id_horario")->fetch_assoc();

if (!$horario) {
    echo "<div class='alert alert-danger'>Horario no encontrado</div>";
    require_once 'includes/footer.php';
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = intval($_POST['id_materia']);
    $id_salon = intval($_POST['id_salon']);
    $dia = $conn->real_escape_string($_POST['dia']);
    $hora_inicio = $conn->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $conn->real_escape_string($_POST['hora_fin']);

    $sql = "UPDATE horarios SET 
            id_materia = $id_materia,
            id_salon = $id_salon,
            dia = '$dia',
            hora_inicio = '$hora_inicio',
            hora_fin = '$hora_fin'
            WHERE id_horario = $id_horario";

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Horario actualizado correctamente</div>";
        $horario = $conn->query("SELECT * FROM horarios WHERE id_horario = $id_horario")->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
}

// Obtener opciones para selects
$materias = $conn->query("SELECT * FROM materias ORDER BY nombre");
$salones = $conn->query("SELECT * FROM salones ORDER BY nombre");
$dias = ['Lu' => 'Lunes', 'Ma' => 'Martes', 'Mi' => 'Miércoles', 
         'Ju' => 'Jueves', 'Vi' => 'Viernes', 'Sa' => 'Sábado', 'Do' => 'Domingo'];
?>

<h2><i class="fas fa-clock"></i> Editar Horario</h2>
<hr>

<form method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="id_materia" class="form-label">Materia</label>
            <select class="form-select" id="id_materia" name="id_materia" required>
                <?php while ($m = $materias->fetch_assoc()): ?>
                    <option value="<?= $m['id_materia'] ?>" <?= $m['id_materia'] == $horario['id_materia'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="id_salon" class="form-label">Salón</label>
            <select class="form-select" id="id_salon" name="id_salon" required>
                <?php while ($s = $salones->fetch_assoc()): ?>
                    <option value="<?= $s['id_salon'] ?>" <?= $s['id_salon'] == $horario['id_salon'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nombre']) ?> (Capacidad: <?= $s['capacidad'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="dia" class="form-label">Día</label>
            <select class="form-select" id="dia" name="dia" required>
                <?php foreach ($dias as $clave => $valor): ?>
                    <option value="<?= $clave ?>" <?= $clave == $horario['dia'] ? 'selected' : '' ?>>
                        <?= $valor ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="hora_inicio" class="form-label">Hora Inicio</label>
            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                   value="<?= substr($horario['hora_inicio'], 0, 5) ?>" required>
        </div>
        
        <div class="col-md-3">
            <label for="hora_fin" class="form-label">Hora Fin</label>
            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                   value="<?= substr($horario['hora_fin'], 0, 5) ?>" required>
        </div>
        
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="horarios.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
<?php
require_once 'config.php';
require_once 'includes/header.php';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_materia = $_POST['id_materia'] ?? '';
    $id_salon = $_POST['id_salon'] ?? '';
    $dia = $_POST['dia'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';

    // Validar los datos
    if (!empty($id_materia) && !empty($id_salon) && !empty($dia) && !empty($hora_inicio) && !empty($hora_fin)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO horarios (id_materia, id_salon, dia, hora_inicio, hora_fin) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$id_materia, $id_salon, $dia, $hora_inicio, $hora_fin])) {
            header('Location: horarios.php?success=1');
            exit;
        } else {
            $error = "Error al guardar el horario";
        }
    } else {
        $error = "Por favor complete todos los campos obligatorios";
    }
}

// Obtener listas para los selects
$materias = $conn->query("SELECT id_materia, nombre FROM materias ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$salones = $conn->query("SELECT id_salon, nombre FROM salones ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>

<h2><i class="fas fa-clock"></i> Nuevo Horario</h2>
<hr>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="horario_nuevo.php" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_materia">Materia:</label>
                        <select name="id_materia" id="id_materia" class="form-control" required>
                            <option value="">Seleccione una materia</option>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?php echo $materia['id_materia']; ?>">
                                    <?php echo htmlspecialchars($materia['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_salon">Salón:</label>
                        <select name="id_salon" id="id_salon" class="form-control" required>
                            <option value="">Seleccione un salón</option>
                            <?php foreach ($salones as $salon): ?>
                                <option value="<?php echo $salon['id_salon']; ?>">
                                    Salón <?php echo htmlspecialchars($salon['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dia">Día de la semana:</label>
                        <select name="dia" id="dia" class="form-control" required>
                            <option value="">Seleccione un día</option>
                            <option value="Lu">Lunes</option>
                            <option value="Ma">Martes</option>
                            <option value="Mi">Miércoles</option>
                            <option value="Ju">Jueves</option>
                            <option value="Vi">Viernes</option>
                            <option value="Sa">Sábado</option>
                            <option value="Do">Domingo</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora_inicio">Hora de inicio:</label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora_fin">Hora de fin:</label>
                                <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Horario
                </button>
                <a href="horarios.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
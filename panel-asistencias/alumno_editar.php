<?php
require_once 'config.php';
require_once 'includes/header.php';

// Verificar si se recibió un ID válido
$id_alumno = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_alumno <= 0) {
    header("Location: alumnos.php");
    exit;
}

// Obtener datos del alumno
$alumno = $conn->query("SELECT * FROM alumnos WHERE id_alumno = $id_alumno")->fetch_assoc();

if (!$alumno) {
    echo "<div class='alert alert-danger'>Alumno no encontrado</div>";
    require_once 'includes/footer.php';
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conn->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = $conn->real_escape_string($_POST['apellido_materno']);
    $matricula = $conn->real_escape_string($_POST['matricula']);
    $id_huella = intval($_POST['id_huella']);
    $carrera = $conn->real_escape_string($_POST['carrera']);
    $cuatrimestre = intval($_POST['cuatrimestre']);
    $grupo = $conn->real_escape_string($_POST['grupo']);
    $correo_institucional = $conn->real_escape_string($_POST['correo_institucional']);
    $correo_personal = $conn->real_escape_string($_POST['correo_personal']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $modalidad = $conn->real_escape_string($_POST['modalidad']);
    $deuda = isset($_POST['deuda']) ? 1 : 0;
    $estado = $conn->real_escape_string($_POST['estado']);

    $sql = "UPDATE alumnos SET 
            nombre = '$nombre',
            apellido_paterno = '$apellido_paterno',
            apellido_materno = '$apellido_materno',
            matricula = '$matricula',
            id_huella = $id_huella,
            carrera = '$carrera',
            cuatrimestre = $cuatrimestre,
            grupo = '$grupo',
            correo_institucional = '$correo_institucional',
            correo_personal = '$correo_personal',
            telefono = '$telefono',
            modalidad = '$modalidad',
            deuda = $deuda,
            estado = '$estado'
            WHERE id_alumno = $id_alumno";

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Alumno actualizado correctamente</div>";
        // Actualizar datos mostrados
        $alumno = $conn->query("SELECT * FROM alumnos WHERE id_alumno = $id_alumno")->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
}
?>

<h2><i class="fas fa-user-edit"></i> Editar Alumno</h2>
<hr>

<form method="POST">
    <div class="row g-3">
        <div class="col-md-4">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($alumno['apellido_paterno']) ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($alumno['apellido_materno']) ?>">
        </div>
        
        <div class="col-md-6">
            <label for="matricula" class="form-label">Matrícula</label>
            <input type="text" class="form-control" id="matricula" name="matricula" value="<?= htmlspecialchars($alumno['matricula']) ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="id_huella" class="form-label">ID de Huella</label>
            <input type="number" class="form-control" id="id_huella" name="id_huella" value="<?= $alumno['id_huella'] ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="carrera" class="form-label">Carrera</label>
            <input type="text" class="form-control" id="carrera" name="carrera" value="<?= htmlspecialchars($alumno['carrera']) ?>" required>
        </div>
        
        <div class="col-md-3">
            <label for="cuatrimestre" class="form-label">Cuatrimestre</label>
            <select class="form-select" id="cuatrimestre" name="cuatrimestre" required>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $alumno['cuatrimestre'] ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="grupo" class="form-label">Grupo</label>
            <input type="text" class="form-control" id="grupo" name="grupo" value="<?= htmlspecialchars($alumno['grupo']) ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="correo_institucional" class="form-label">Correo Institucional</label>
            <input type="email" class="form-control" id="correo_institucional" name="correo_institucional" value="<?= htmlspecialchars($alumno['correo_institucional']) ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="correo_personal" class="form-label">Correo Personal</label>
            <input type="email" class="form-control" id="correo_personal" name="correo_personal" value="<?= htmlspecialchars($alumno['correo_personal']) ?>">
        </div>
        
        <div class="col-md-6">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($alumno['telefono']) ?>">
        </div>
        
        <div class="col-md-6">
            <label for="modalidad" class="form-label">Modalidad</label>
            <select class="form-select" id="modalidad" name="modalidad" required>
                <option value="Escolarizada" <?= $alumno['modalidad'] == 'Escolarizada' ? 'selected' : '' ?>>Escolarizada</option>
                <option value="Ejecutiva" <?= $alumno['modalidad'] == 'Ejecutiva' ? 'selected' : '' ?>>Ejecutiva</option>
                <option value="Dominical" <?= $alumno['modalidad'] == 'Dominical' ? 'selected' : '' ?>>Dominical</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" id="deuda" name="deuda" <?= $alumno['deuda'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="deuda">Tiene deuda</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="Alta" <?= $alumno['estado'] == 'Alta' ? 'selected' : '' ?>>Alta</option>
                <option value="Baja" <?= $alumno['estado'] == 'Baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Indeterminado" <?= $alumno['estado'] == 'Indeterminado' ? 'selected' : '' ?>>Indeterminado</option>
            </select>
        </div>
        
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="alumnos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
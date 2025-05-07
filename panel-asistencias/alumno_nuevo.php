<?php
require_once 'config.php';
require_once 'includes/header.php';

// Procesar creación de nuevo alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger y sanitizar todos los datos del formulario
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

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido_paterno) || empty($matricula) || empty($id_huella)) {
        echo "<div class='alert alert-danger'>Los campos nombre, apellido paterno, matrícula e ID de huella son obligatorios</div>";
    } else {
        // Insertar en la base de datos
        $sql = "INSERT INTO alumnos (
                nombre, apellido_paterno, apellido_materno, matricula, id_huella,
                carrera, cuatrimestre, grupo, correo_institucional,
                correo_personal, telefono, modalidad, deuda, estado
                ) VALUES (
                '$nombre', '$apellido_paterno', '$apellido_materno', '$matricula', $id_huella,
                '$carrera', $cuatrimestre, '$grupo', '$correo_institucional',
                '$correo_personal', '$telefono', '$modalidad', $deuda, '$estado'
                )";

        if ($conn->query($sql)) {
            echo "<div class='alert alert-success'>Alumno registrado correctamente</div>";
            // Limpiar el formulario después de un registro exitoso
            $_POST = array();
        } else {
            echo "<div class='alert alert-danger'>Error al registrar: " . $conn->error . "</div>";
        }
    }
}
?>

<h2><i class="fas fa-user-plus"></i> Registrar Nuevo Alumno</h2>
<hr>

<form method="POST">
    <div class="row g-3">
        <!-- Sección de Información Personal -->
        <div class="col-md-4">
            <label for="nombre" class="form-label">Nombre(s)*</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="apellido_paterno" class="form-label">Apellido Paterno*</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" 
                   value="<?= isset($_POST['apellido_paterno']) ? htmlspecialchars($_POST['apellido_paterno']) : '' ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" 
                   value="<?= isset($_POST['apellido_materno']) ? htmlspecialchars($_POST['apellido_materno']) : '' ?>">
        </div>
        
        <!-- Sección de Datos Académicos -->
        <div class="col-md-6">
            <label for="matricula" class="form-label">Matrícula*</label>
            <input type="text" class="form-control" id="matricula" name="matricula" 
                   value="<?= isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="id_huella" class="form-label">ID de Huella Digital*</label>
            <input type="number" class="form-control" id="id_huella" name="id_huella" 
                   value="<?= isset($_POST['id_huella']) ? htmlspecialchars($_POST['id_huella']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="carrera" class="form-label">Carrera*</label>
            <input type="text" class="form-control" id="carrera" name="carrera" 
                   value="<?= isset($_POST['carrera']) ? htmlspecialchars($_POST['carrera']) : '' ?>" required>
        </div>
        
        <div class="col-md-3">
            <label for="cuatrimestre" class="form-label">Cuatrimestre*</label>
            <select class="form-select" id="cuatrimestre" name="cuatrimestre" required>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($_POST['cuatrimestre']) && $_POST['cuatrimestre'] == $i) ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="grupo" class="form-label">Grupo*</label>
            <input type="text" class="form-control" id="grupo" name="grupo" 
                   value="<?= isset($_POST['grupo']) ? htmlspecialchars($_POST['grupo']) : '' ?>" required>
        </div>
        
        <!-- Sección de Contacto -->
        <div class="col-md-6">
            <label for="correo_institucional" class="form-label">Correo Institucional*</label>
            <input type="email" class="form-control" id="correo_institucional" name="correo_institucional" 
                   value="<?= isset($_POST['correo_institucional']) ? htmlspecialchars($_POST['correo_institucional']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
            <label for="correo_personal" class="form-label">Correo Personal</label>
            <input type="email" class="form-control" id="correo_personal" name="correo_personal" 
                   value="<?= isset($_POST['correo_personal']) ? htmlspecialchars($_POST['correo_personal']) : '' ?>">
        </div>
        
        <div class="col-md-6">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" 
                   value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>">
        </div>
        
        <!-- Sección de Configuración -->
        <div class="col-md-3">
            <label for="modalidad" class="form-label">Modalidad*</label>
            <select class="form-select" id="modalidad" name="modalidad" required>
                <option value="Escolarizada" <?= (isset($_POST['modalidad']) && $_POST['modalidad'] == 'Escolarizada') ? 'selected' : '' ?>>Escolarizada</option>
                <option value="Ejecutiva" <?= (isset($_POST['modalidad']) && $_POST['modalidad'] == 'Ejecutiva') ? 'selected' : '' ?>>Ejecutiva</option>
                <option value="Dominical" <?= (isset($_POST['modalidad']) && $_POST['modalidad'] == 'Dominical') ? 'selected' : '' ?>>Dominical</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="estado" class="form-label">Estado*</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="Alta" <?= (isset($_POST['estado']) && $_POST['estado'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                <option value="Baja" <?= (isset($_POST['estado']) && $_POST['estado'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
                <option value="Indeterminado" <?= (isset($_POST['estado']) && $_POST['estado'] == 'Indeterminado') ? 'selected' : '' ?>>Indeterminado</option>
            </select>
        </div>
        
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="deuda" name="deuda" <?= (isset($_POST['deuda']) && $_POST['deuda']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="deuda">El alumno tiene deuda</label>
            </div>
        </div>
        
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar Alumno</button>
            <a href="alumnos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
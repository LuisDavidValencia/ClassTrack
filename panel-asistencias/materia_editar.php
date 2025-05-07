<?php
require_once 'config.php';
require_once 'includes/header.php';

$id_materia = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_materia <= 0) {
    header("Location: horarios.php");
    exit;
}

$materia = $conn->query("SELECT * FROM materias WHERE id_materia = $id_materia")->fetch_assoc();

if (!$materia) {
    echo "<div class='alert alert-danger'>Materia no encontrada</div>";
    require_once 'includes/footer.php';
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $clave = $conn->real_escape_string($_POST['clave']);

    $sql = "UPDATE materias SET 
            nombre = '$nombre',
            clave = '$clave'
            WHERE id_materia = $id_materia";

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Materia actualizada correctamente</div>";
        $materia = $conn->query("SELECT * FROM materias WHERE id_materia = $id_materia")->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
}
?>

<h2><i class="fas fa-book-edit"></i> Editar Materia</h2>
<hr>

<form method="POST">
    <div class="row g-3">
        <div class="col-md-8">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($materia['nombre']) ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="clave" class="form-label">Clave</label>
            <input type="text" class="form-control" id="clave" name="clave" 
                   value="<?= htmlspecialchars($materia['clave']) ?>" required>
        </div>
        
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="horarios.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
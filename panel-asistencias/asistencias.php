<?php
require_once 'config.php';
require_once 'includes/header.php';

// Filtros
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$id_materia = isset($_GET['id_materia']) ? intval($_GET['id_materia']) : 0;
?>

<h2><i class="fas fa-clipboard-check"></i> Registro de Asistencias</h2>
<hr>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="<?= $fecha ?>">
            </div>
            <div class="col-md-4">
                <label for="id_materia" class="form-label">Materia</label>
                <select name="id_materia" id="id_materia" class="form-select">
                    <option value="0">Todas las materias</option>
                    <?php
                    $materias = $conn->query("SELECT * FROM materias ORDER BY nombre");
                    while ($m = $materias->fetch_assoc()) {
                        $selected = $id_materia == $m['id_materia'] ? 'selected' : '';
                        echo "<option value='{$m['id_materia']}' $selected>{$m['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Salón</th>
                        <th>Hora Entrada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $where = "WHERE a.fecha = '$fecha'";
                    if ($id_materia > 0) {
                        $where .= " AND a.id_materia = $id_materia";
                    }
                    
                    $query = "SELECT a.*, al.nombre, al.apellido_paterno, m.nombre as materia, s.nombre as salon
                              FROM asistencias a
                              JOIN alumnos al ON a.id_alumno = al.id_alumno
                              JOIN materias m ON a.id_materia = m.id_materia
                              JOIN salones s ON a.id_salon = s.id_salon
                              $where
                              ORDER BY a.hora_entrada DESC";
                    $result = $conn->query($query);
                    
                    while ($asistencia = $result->fetch_assoc()) {
                        $estado_class = $asistencia['estado_asistencia'] == 'Presente' ? 'success' : 
                                       ($asistencia['estado_asistencia'] == 'Tarde' ? 'warning' : 'danger');
                        
                        echo "<tr>
                                <td>{$asistencia['nombre']} {$asistencia['apellido_paterno']}</td>
                                <td>{$asistencia['materia']}</td>
                                <td>{$asistencia['salon']}</td>
                                <td>{$asistencia['hora_entrada']}</td>
                                <td><span class='badge bg-$estado_class'>{$asistencia['estado_asistencia']}</span></td>
                                <td>
                                    <button class='btn btn-sm btn-danger' onclick='eliminarAsistencia({$asistencia['id_asistencia']})'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function eliminarAsistencia(id) {
    if (confirm('¿Estás seguro de eliminar esta asistencia?')) {
        window.location.href = 'eliminar_asistencia.php?id=' + id;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
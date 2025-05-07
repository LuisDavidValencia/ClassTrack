<?php
require_once 'config.php';
require_once 'includes/header.php';
?>

<h2><i class="fas fa-clock"></i> Gestión de Horarios</h2>
<hr>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Horarios actuales</h5>
        <a href="horario_nuevo.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Nuevo Horario</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Salón</th>
                        <th>Día</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT h.*, m.nombre as materia, s.nombre as salon
                              FROM horarios h
                              JOIN materias m ON h.id_materia = m.id_materia
                              JOIN salones s ON h.id_salon = s.id_salon
                              ORDER BY h.dia, h.hora_inicio";
                    $result = $conn->query($query);
                    
                    $dias = ['Lu' => 'Lunes', 'Ma' => 'Martes', 'Mi' => 'Miércoles', 
                            'Ju' => 'Jueves', 'Vi' => 'Viernes', 'Sa' => 'Sábado', 'Do' => 'Domingo'];
                    
                    while ($horario = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$horario['materia']}</td>
                                <td>{$horario['salon']}</td>
                                <td>{$dias[$horario['dia']]}</td>
                                <td>{$horario['hora_inicio']}</td>
                                <td>{$horario['hora_fin']}</td>
                                <td>
                                    <a href='horario_editar.php?id={$horario['id_horario']}' class='btn btn-sm btn-primary'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <button class='btn btn-sm btn-danger' onclick='eliminarHorario({$horario['id_horario']})'>
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
function eliminarHorario(id) {
    if (confirm('¿Estás seguro de eliminar este horario?')) {
        window.location.href = 'eliminar_horario.php?id=' + id;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
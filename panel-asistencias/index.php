<?php
require_once 'config.php';
require_once 'includes/header.php';

// Obtener estadísticas
$total_alumnos = $conn->query("SELECT COUNT(*) as total FROM alumnos")->fetch_assoc()['total'];
$total_asistencias = $conn->query("SELECT COUNT(*) as total FROM asistencias WHERE fecha = CURDATE()")->fetch_assoc()['total'];
$total_materias = $conn->query("SELECT COUNT(*) as total FROM materias")->fetch_assoc()['total'];
?>

<h2><i class="fas fa-home"></i> Dashboard</h2>
<hr>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Alumnos</h5>
                <p class="card-text display-4"><?= $total_alumnos ?></p>
                <a href="alumnos.php" class="text-white">Ver detalles <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clipboard-check"></i> Asistencias hoy</h5>
                <p class="card-text display-4"><?= $total_asistencias ?></p>
                <a href="asistencias.php" class="text-white">Ver detalles <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-book"></i> Materias</h5>
                <p class="card-text display-4"><?= $total_materias ?></p>
                <a href="horarios.php" class="text-white">Ver detalles <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-history"></i> Últimas asistencias registradas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT a.nombre, a.apellido_paterno, m.nombre as materia, 
                             asi.fecha, asi.hora_entrada, asi.estado_asistencia
                             FROM asistencias asi
                             JOIN alumnos a ON asi.id_alumno = a.id_alumno
                             JOIN materias m ON asi.id_materia = m.id_materia
                             ORDER BY asi.fecha DESC, asi.hora_entrada DESC
                             LIMIT 10";
                    $result = $conn->query($query);
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nombre']} {$row['apellido_paterno']}</td>
                                <td>{$row['materia']}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['hora_entrada']}</td>
                                <td><span class='badge bg-" . ($row['estado_asistencia'] == 'Presente' ? 'success' : 'warning') . "'>{$row['estado_asistencia']}</span></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
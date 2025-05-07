<?php
require_once 'config.php';
require_once 'includes/header.php';

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

// Obtener asistencias del alumno con información de materias y salones
$query = "SELECT a.*, m.nombre as materia, s.nombre as salon
          FROM asistencias a
          JOIN materias m ON a.id_materia = m.id_materia
          JOIN salones s ON a.id_salon = s.id_salon
          WHERE a.id_alumno = $id_alumno
          ORDER BY a.fecha DESC, a.hora_entrada DESC";

$asistencias = $conn->query($query);
?>

<h2><i class="fas fa-clipboard-list"></i> Asistencias de <?= htmlspecialchars($alumno['nombre']) ?> <?= htmlspecialchars($alumno['apellido_paterno']) ?></h2>
<h5 class="text-muted mb-4">Matrícula: <?= htmlspecialchars($alumno['matricula']) ?> | Grupo: <?= htmlspecialchars($alumno['grupo']) ?></h5>

<div class="card mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Historial de Asistencias</h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="alumnos.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if ($asistencias->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Materia</th>
                            <th>Salón</th>
                            <th>Hora Entrada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($asistencia = $asistencias->fetch_assoc()): ?>
                            <tr>
                                <td><?= $asistencia['fecha'] ?></td>
                                <td><?= htmlspecialchars($asistencia['materia']) ?></td>
                                <td><?= htmlspecialchars($asistencia['salon']) ?></td>
                                <td><?= substr($asistencia['hora_entrada'], 0, 5) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $asistencia['estado_asistencia'] == 'Presente' ? 'success' : 
                                        ($asistencia['estado_asistencia'] == 'Tarde' ? 'warning' : 'danger') 
                                    ?>">
                                        <?= $asistencia['estado_asistencia'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No se encontraron registros de asistencia para este alumno.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resumen de Asistencias</h5>
                <?php
                $resumen = $conn->query("
                    SELECT estado_asistencia, COUNT(*) as total
                    FROM asistencias
                    WHERE id_alumno = $id_alumno
                    GROUP BY estado_asistencia
                ");
                
                if ($resumen->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($row = $resumen->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $row['estado_asistencia'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $row['total'] ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No hay datos de asistencia registrados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Alumno</h5>
                <p><strong>Carrera:</strong> <?= htmlspecialchars($alumno['carrera']) ?></p>
                <p><strong>Cuatrimestre:</strong> <?= $alumno['cuatrimestre'] ?></p>
                <p><strong>Estado:</strong> 
                    <span class="badge bg-<?= 
                        $alumno['estado'] == 'Alta' ? 'success' : 
                        ($alumno['estado'] == 'Baja' ? 'danger' : 'warning') 
                    ?>">
                        <?= $alumno['estado'] ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
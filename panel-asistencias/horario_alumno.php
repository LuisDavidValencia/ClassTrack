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

// CONSULTA OPTIMIZADA - Obtener horarios del alumno
$query = "SELECT h.*, m.nombre as materia, s.nombre as salon 
          FROM alumnos a
          JOIN salones s ON s.nombre = a.grupo
          JOIN horarios h ON h.id_salon = s.id_salon
          JOIN materias m ON m.id_materia = h.id_materia
          WHERE a.id_alumno = $id_alumno
          ORDER BY h.dia, h.hora_inicio";

$horarios = $conn->query($query);

// Organizar horarios por día
$horario_por_dia = [
    'Lu' => [], 'Ma' => [], 'Mi' => [], 
    'Ju' => [], 'Vi' => [], 'Sa' => [], 'Do' => []
];

while ($horario = $horarios->fetch_assoc()) {
    $horario_por_dia[$horario['dia']][] = $horario;
}

// Nombres completos de los días
$nombres_dias = [
    'Lu' => 'Lunes', 'Ma' => 'Martes', 'Mi' => 'Miércoles',
    'Ju' => 'Jueves', 'Vi' => 'Viernes', 'Sa' => 'Sábado', 'Do' => 'Domingo'
];
?>

<h2><i class="fas fa-calendar-alt"></i> Horario de <?= htmlspecialchars($alumno['nombre']) ?> <?= htmlspecialchars($alumno['apellido_paterno']) ?></h2>
<h5 class="text-muted mb-4">Grupo: <span class="badge bg-primary"><?= htmlspecialchars($alumno['grupo']) ?></span> | Carrera: <?= htmlspecialchars($alumno['carrera']) ?></h5>

<!-- Sección de Depuración -->
<div class="card mb-3 bg-light">
    <div class="card-body">
        <h6 class="text-muted">Información de Depuración:</h6>
        <p><strong>Consulta SQL:</strong> <code class="text-dark"><?= htmlspecialchars($query) ?></code></p>
        <p><strong>Horarios encontrados:</strong> <?= $horarios->num_rows ?></p>
    </div>
</div>

<?php if ($horarios->num_rows > 0): ?>
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Hora</th>
                        <?php foreach ($nombres_dias as $dia_nombre): ?>
                            <th><?= $dia_nombre ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Generar franjas horarias de 7:00 a 14:00
                    for ($hora = 7; $hora <= 13; $hora++):
                        $hora_actual = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
                        $hora_siguiente = str_pad($hora + 1, 2, '0', STR_PAD_LEFT) . ':00';
                    ?>
                        <tr>
                            <td class="table-secondary align-middle"><?= $hora_actual ?>-<?= $hora_siguiente ?></td>
                            <?php foreach (array_keys($nombres_dias) as $dia): ?>
                                <td class="align-middle">
                                    <?php
                                    $mostrado = false;
                                    foreach ($horario_por_dia[$dia] as $horario) {
                                        $hora_inicio = substr($horario['hora_inicio'], 0, 5);
                                        $hora_fin = substr($horario['hora_fin'], 0, 5);
                                        
                                        if ($hora_actual >= $hora_inicio && $hora_actual < $hora_fin) {
                                            echo "<div class='p-2 mb-2 bg-primary text-white rounded'>
                                                    <strong>{$horario['materia']}</strong><br>
                                                    <small>Salón: {$horario['salon']}</small><br>
                                                    <small>{$hora_inicio}-{$hora_fin}</small>
                                                  </div>";
                                            $mostrado = true;
                                            break;
                                        }
                                    }
                                    if (!$mostrado) {
                                        echo "<div class='p-2'>&nbsp;</div>";
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endfor; ?>
                    <!-- Última franja 13:00-14:00 -->
                    <tr>
                        <td class="table-secondary align-middle">13:00-14:00</td>
                        <?php foreach (array_keys($nombres_dias) as $dia): ?>
                            <td class="align-middle">
                                <?php
                                $mostrado = false;
                                foreach ($horario_por_dia[$dia] as $horario) {
                                    $hora_inicio = substr($horario['hora_inicio'], 0, 5);
                                    $hora_fin = substr($horario['hora_fin'], 0, 5);
                                    
                                    if ('13:00' >= $hora_inicio && '13:00' < $hora_fin) {
                                        echo "<div class='p-2 mb-2 bg-primary text-white rounded'>
                                                <strong>{$horario['materia']}</strong><br>
                                                <small>Salón: {$horario['salon']}</small><br>
                                                <small>{$hora_inicio}-{$hora_fin}</small>
                                              </div>";
                                        $mostrado = true;
                                        break;
                                    }
                                }
                                if (!$mostrado) {
                                    echo "<div class='p-2'>&nbsp;</div>";
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-warning">
    No se encontraron horarios para el grupo <?= htmlspecialchars($alumno['grupo']) ?>.
    <div class="mt-2">
        <a href="horarios.php" class="btn btn-sm btn-outline-primary">Ver todos los horarios</a>
    </div>
</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-book"></i> Materias Inscritas</h5>
    </div>
    <div class="card-body">
        <?php
        $materias_query = "SELECT DISTINCT m.id_materia, m.nombre 
                          FROM horarios h
                          JOIN materias m ON h.id_materia = m.id_materia
                          JOIN salones s ON h.id_salon = s.id_salon
                          WHERE s.nombre = '{$alumno['grupo']}'
                          ORDER BY m.nombre";
        
        $materias_result = $conn->query($materias_query);
        
        if ($materias_result->num_rows > 0): ?>
            <div class="row">
                <?php while ($materia = $materias_result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($materia['nombre']) ?></h6>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="asistencias.php?id_materia=<?= $materia['id_materia'] ?>&id_alumno=<?= $id_alumno ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    Ver asistencias
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No se encontraron materias para este grupo</div>
        <?php endif; ?>
    </div>
</div>

<div class="d-flex justify-content-between mb-4">
    <a href="alumnos.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver a Alumnos
    </a>
    <?php if ($horarios->num_rows > 0): ?>
    <button onclick="window.print()" class="btn btn-success">
        <i class="fas fa-print"></i> Imprimir Horario
    </button>
    <?php endif; ?>
</div>

<style>
    .table td {
        min-width: 150px;
        height: 80px;
        vertical-align: middle;
    }
    .bg-primary {
        background-color: #0d6efd !important;
    }
    .card-footer {
        border-top: none;
    }
    @media print {
        .card-header, .btn, .alert, .bg-light {
            display: none;
        }
        .table td {
            height: auto;
        }
    }
</style>

<?php require_once 'includes/footer.php'; ?>
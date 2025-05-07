<?php
require_once 'config.php';
require_once 'includes/header.php';

// Procesar búsqueda
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';
?>

<h2><i class="fas fa-users"></i> Gestión de Alumnos</h2>
<hr>

<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar alumno..." value="<?= htmlspecialchars($busqueda) ?>">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <a href="alumno_nuevo.php" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo Alumno</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Matrícula</th>
                        <th>Carrera</th>
                        <th>Cuatrimestre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $where = $busqueda ? "WHERE (nombre LIKE '%$busqueda%' OR 
                                           apellido_paterno LIKE '%$busqueda%' OR 
                                           matricula LIKE '%$busqueda%')" : "";
                    
                    $query = "SELECT * FROM alumnos $where ORDER BY apellido_paterno, nombre";
                    $result = $conn->query($query);
                    
                    while ($alumno = $result->fetch_assoc()) {
                        $estado_class = $alumno['estado'] == 'Alta' ? 'success' : 'danger';
                        $deuda_badge = $alumno['deuda'] ? '<span class="badge bg-danger ms-2">Deuda</span>' : '';
                        
                        echo "<tr>
                                <td>{$alumno['id_alumno']}</td>
                                <td>{$alumno['nombre']} {$alumno['apellido_paterno']} {$alumno['apellido_materno']}</td>
                                <td>{$alumno['matricula']}</td>
                                <td>{$alumno['carrera']}</td>
                                <td>{$alumno['cuatrimestre']}</td>
                                <td>
                                    <span class='badge bg-$estado_class'>{$alumno['estado']}</span>
                                    $deuda_badge
                                </td>
                                <td>
                                    <a href='alumno_editar.php?id={$alumno['id_alumno']}' class='btn btn-sm btn-primary' title='Editar'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <a href='alumno_asistencias.php?id={$alumno['id_alumno']}' class='btn btn-sm btn-info' title='Ver asistencias'>
                                        <i class='fas fa-clipboard-list'></i>
                                    </a>
                                    <a href='horario_alumno.php?id={$alumno['id_alumno']}' class='btn btn-sm btn-success' title='Ver horario'>
                                        <i class='fas fa-calendar-alt'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
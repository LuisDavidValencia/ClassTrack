<?php
require_once 'config.php';

// Verificar permisos y autenticación aquí (añadir según necesidades)

$id_horario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_horario <= 0) {
    header("Location: horarios.php?error=ID inválido");
    exit;
}

// Verificar si el horario tiene asistencias registradas
$tiene_asistencias = $conn->query("SELECT 1 FROM asistencias WHERE id_horario = $id_horario LIMIT 1")->num_rows > 0;

if ($tiene_asistencias) {
    header("Location: horarios.php?error=No se puede eliminar, el horario tiene asistencias registradas");
    exit;
}

// Eliminar horario
if ($conn->query("DELETE FROM horarios WHERE id_horario = $id_horario")) {
    header("Location: horarios.php?success=Horario eliminado correctamente");
} else {
    header("Location: horarios.php?error=Error al eliminar: " . urlencode($conn->error));
}
?>
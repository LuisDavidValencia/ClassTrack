<?php
require_once 'config.php';

// Verificar permisos y autenticación aquí (añadir según necesidades)

$id_asistencia = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_asistencia <= 0) {
    header("Location: asistencias.php?error=ID inválido");
    exit;
}

// Eliminar asistencia
if ($conn->query("DELETE FROM asistencias WHERE id_asistencia = $id_asistencia")) {
    header("Location: asistencias.php?success=Asistencia eliminada correctamente");
} else {
    header("Location: asistencias.php?error=Error al eliminar: " . urlencode($conn->error));
}
?>
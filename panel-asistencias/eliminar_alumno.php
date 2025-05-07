<?php
require_once 'config.php';

// Verificar permisos y autenticación aquí (añadir según necesidades)

$id_alumno = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_alumno <= 0) {
    header("Location: alumnos.php?error=ID inválido");
    exit;
}

// Verificar si el alumno tiene asistencias registradas
$tiene_asistencias = $conn->query("SELECT 1 FROM asistencias WHERE id_alumno = $id_alumno LIMIT 1")->num_rows > 0;

if ($tiene_asistencias) {
    // Opción 1: No permitir eliminar
    header("Location: alumnos.php?error=No se puede eliminar, el alumno tiene asistencias registradas");
    exit;
    
    // Opción 2: Eliminar en cascada (descomentar si se prefiere)
    // $conn->query("DELETE FROM asistencias WHERE id_alumno = $id_alumno");
}

// Eliminar alumno
if ($conn->query("DELETE FROM alumnos WHERE id_alumno = $id_alumno")) {
    header("Location: alumnos.php?success=Alumno eliminado correctamente");
} else {
    header("Location: alumnos.php?error=Error al eliminar: " . urlencode($conn->error));
}
?>
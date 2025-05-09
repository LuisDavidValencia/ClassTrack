<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id_horario = $_GET['id'];
    
    // Verificar si el horario existe
    $stmt = $conn->prepare("SELECT * FROM horarios WHERE id_horario = ?");
    $stmt->bind_param("i", $id_horario);
    $stmt->execute();
    $result = $stmt->get_result();
    $horario = $result->fetch_assoc();
    
    if ($horario) {
        // Eliminar el horario
        $stmt = $conn->prepare("DELETE FROM horarios WHERE id_horario = ?");
        $stmt->bind_param("i", $id_horario);
        
        if ($stmt->execute()) {
            header('Location: horarios.php?deleted=1');
        } else {
            header('Location: horarios.php?error=1');
        }
    } else {
        header('Location: horarios.php?error=2');
    }
} else {
    header('Location: horarios.php');
}
exit;
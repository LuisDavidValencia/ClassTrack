<?php
header('Content-Type: application/json');
date_default_timezone_set("America/Mexico_City");

$conn = new mysqli('localhost','root','','universidad');
if($conn->connect_error){
  die(json_encode(['status'=>'error','error'=>'BD connection failed']));
}

$id_huella = intval($_POST['id_huella'] ?? 0);
$id_salon  = intval($_POST['id_salon']  ?? 0);

$res = $conn->query("SELECT * FROM alumnos WHERE id_huella=$id_huella");
if(!$res || $res->num_rows==0){
  echo json_encode(['status'=>'denied','reason'=>'Alumno no encontrado']);
  exit;
}
$al = $res->fetch_assoc();
if($al['estado']!=='Alta'){
  echo json_encode(['status'=>'denied','reason'=>'Alumno no activo']);
  exit;
}
if($al['deuda']){
  echo json_encode(['status'=>'denied','reason'=>'Alumno con deuda']);
  exit;
}

$dia_map = ['Mon'=>'Lu','Tue'=>'Ma','Wed'=>'Mi','Thu'=>'Ju','Fri'=>'Vi','Sat'=>'Sa','Sun'=>'Do'];
$dia = $dia_map[date('D')] ?? '';
$hora_actual = date('H:i:s');
$fecha_actual = date('Y-m-d');

// Buscar horario activo
$sql = "SELECT id_materia, hora_inicio FROM horarios
        WHERE id_salon=$id_salon
          AND dia='$dia'
          AND '$hora_actual' BETWEEN hora_inicio AND hora_fin
        LIMIT 1";
$r2 = $conn->query($sql);
if(!$r2 || $r2->num_rows==0){
  echo json_encode(['status'=>'denied','reason'=>'Sin materia asignada en este horario']);
  exit;
}
$row_horario = $r2->fetch_assoc();
$id_materia = $row_horario['id_materia'];
$hora_inicio = $row_horario['hora_inicio'];

// Validar si ya se registró asistencia hoy para esa materia
$qcheck = "SELECT 1 FROM asistencias
           WHERE id_alumno = {$al['id_alumno']}
             AND id_materia = $id_materia
             AND fecha = '$fecha_actual'";
$check = $conn->query($qcheck);
if ($check && $check->num_rows > 0) {
  echo json_encode(['status'=>'denied','reason'=>'Asistencia ya registrada hoy']);
  exit;
}

// Calcular estado de asistencia según tiempo
$tiempo_inicio = strtotime($hora_inicio);
$tiempo_ahora = strtotime($hora_actual);
$diferencia_min = ($tiempo_ahora - $tiempo_inicio) / 60;

if ($diferencia_min <= 5) {
  $estado = 'Presente';
} elseif ($diferencia_min <= 10) {
  $estado = 'Tarde';
} else {
  echo json_encode(['status'=>'denied','reason'=>'Tiempo límite excedido']);
  exit;
}

// Insertar asistencia
$qinsert = "INSERT INTO asistencias 
            (id_alumno, id_materia, id_salon, fecha, hora_entrada, estado_asistencia)
            VALUES({$al['id_alumno']}, $id_materia, $id_salon, '$fecha_actual', '$hora_actual', '$estado')";
if ($conn->query($qinsert)) {
  echo json_encode([
    'status'=>'success',
    'message'=>'Asistencia registrada',
    'estado'=>$estado
  ]);
} else {
  echo json_encode(['status'=>'error','error'=>$conn->error]);
}

$conn->close();
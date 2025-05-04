<?php
header('Content-Type: application/json');
date_default_timezone_set("America/Mexico_City");

// Conexión a BD
$conn = new mysqli('localhost','root','','universidad');
if($conn->connect_error){
  die(json_encode(['status'=>'error','error'=>'BD connection failed']));
}

// Leer POST
$id_huella = intval($_POST['id_huella'] ?? 0);
$id_salon  = intval($_POST['id_salon']  ?? 0);

// Buscar alumno
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

// Día y hora
$dia_map = ['Mon'=>'Lu','Tue'=>'Ma','Wed'=>'Mi','Thu'=>'Ju','Fri'=>'Vi','Sat'=>'Sa','Sun'=>'Do'];
$d = date('D');
$dia = $dia_map[$d] ?? '';
$h = date('H:i:s');

// Consultar horario
$sql = "SELECT id_materia FROM horarios
        WHERE id_salon=$id_salon
          AND dia='$dia'
          AND '$h' BETWEEN hora_inicio AND hora_fin
        LIMIT 1";
$r2 = $conn->query($sql);
if(!$r2 || $r2->num_rows==0){
  echo json_encode(['status'=>'denied','reason'=>'Sin materia asignada en este horario']);
  exit;
}
$id_mat = $r2->fetch_assoc()['id_materia'];

// Insertar asistencia
$f = date('Y-m-d');
$q = "INSERT INTO asistencias 
      (id_alumno,id_materia,id_salon,fecha,hora_entrada)
      VALUES({$al['id_alumno']},$id_mat,$id_salon,'$f','$h')";
if($conn->query($q)){
  echo json_encode(['status'=>'success','message'=>'Asistencia registrada']);
} else {
  echo json_encode(['status'=>'error','error'=>$conn->error]);
}

$conn->close();

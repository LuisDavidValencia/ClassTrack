-- =========================================
-- 1. CREAR BASE DE DATOS Y TABLAS
-- =========================================
CREATE DATABASE IF NOT EXISTS universidad;
USE universidad;

CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    id_huella INT NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    cuatrimestre INT NOT NULL,
    grupo VARCHAR(10) NOT NULL,
    correo_institucional VARCHAR(100) NOT NULL,
    correo_personal VARCHAR(100),
    telefono VARCHAR(20),
    modalidad ENUM('Escolarizada','Ejecutiva','Dominical') NOT NULL DEFAULT 'Escolarizada',
    deuda BOOLEAN DEFAULT 0,
    estado ENUM('Alta','Baja','Indeterminado') DEFAULT 'Alta'
);

CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    clave VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE salones (
    id_salon INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL
);

CREATE TABLE horarios (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_materia INT NOT NULL,
    id_salon INT NOT NULL,
    dia ENUM('Lu','Ma','Mi','Ju','Vi','Sa','Do') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    FOREIGN KEY (id_salon)    REFERENCES salones(id_salon)
);

CREATE TABLE asistencias (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_materia INT NOT NULL,
    id_salon INT NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    estado_asistencia ENUM('Presente','Tarde','Ausente') DEFAULT 'Presente',
    FOREIGN KEY (id_alumno)  REFERENCES alumnos(id_alumno),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    FOREIGN KEY (id_salon)   REFERENCES salones(id_salon)
);

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    fecha_pago DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    comprobante VARCHAR(255),
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno)
);

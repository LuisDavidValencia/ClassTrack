-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2025 a las 23:33:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: universidad
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla alumnos
--

CREATE TABLE alumnos (
  id_alumno int(11) NOT NULL,
  nombre varchar(50) NOT NULL,
  apellido_paterno varchar(50) NOT NULL,
  apellido_materno varchar(50) NOT NULL,
  matricula varchar(30) NOT NULL,
  id_huella int(11) NOT NULL,
  carrera varchar(100) NOT NULL,
  cuatrimestre int(11) NOT NULL,
  grupo char(1) NOT NULL,
  correo_institucional varchar(100) NOT NULL,
  correo_personal varchar(100) DEFAULT NULL,
  telefono varchar(20) DEFAULT NULL,
  modalidad enum('Escolarizada','Ejecutiva','Dominical') NOT NULL DEFAULT 'Escolarizada',
  deuda tinyint(1) DEFAULT 0,
  estado enum('Alta','Baja','Indeterminado') DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla alumnos
--

INSERT INTO alumnos (id_alumno, nombre, apellido_paterno, apellido_materno, matricula, id_huella, carrera, cuatrimestre, grupo, correo_institucional, correo_personal, telefono, modalidad, deuda, estado) VALUES
(1, 'María', 'García', 'López', '20230001', 101, 'Ingeniería en Sistemas', 5, 'A', 'maria.garcia@uni.edu.mx', 'maria.garcia@gmail.com', '5512345678', 'Escolarizada', 0, 'Alta'),
(2, 'Juan', 'Martínez', 'Pérez', '20230002', 102, 'Ingeniería en Sistemas', 5, 'A', 'juan.martinez@uni.edu.mx', 'juan.mtz@gmail.com', '5512345679', 'Escolarizada', 1, 'Alta'),
(3, 'Ana', 'Rodríguez', 'Sánchez', '20230003', 103, 'Administración de Empresas', 3, 'B', 'ana.rodriguez@uni.edu.mx', 'ana.rod@gmail.com', '5512345680', 'Ejecutiva', 0, 'Alta'),
(4, 'Carlos', 'Hernández', 'Gómez', '20230004', 104, 'Administración de Empresas', 3, 'B', 'carlos.hernandez@uni.edu.mx', 'carlos.hdz@gmail.com', '5512345681', 'Ejecutiva', 0, 'Alta'),
(5, 'Laura', 'Díaz', 'Vázquez', '20230005', 105, 'Derecho', 7, 'C', 'laura.diaz@uni.edu.mx', 'laura.diaz@gmail.com', '5512345682', 'Dominical', 1, 'Alta'),
(6, 'Pedro', 'López', 'Fernández', '20230006', 106, 'Derecho', 7, 'C', 'pedro.lopez@uni.edu.mx', 'pedro.lpz@gmail.com', '5512345683', 'Dominical', 0, 'Alta'),
(7, 'Sofía', 'González', 'Ramírez', '20230007', 107, 'Psicología', 1, 'D', 'sofia.gonzalez@uni.edu.mx', 'sofia.glez@gmail.com', '5512345684', 'Escolarizada', 0, 'Alta'),
(8, 'Diego', 'Torres', 'Jiménez', '20230008', 108, 'Psicología', 1, 'D', 'diego.torres@uni.edu.mx', 'diego.trs@gmail.com', '5512345685', 'Escolarizada', 0, 'Baja'),
(9, 'Elena', 'Flores', 'Morales', '20230009', 109, 'Contaduría', 9, 'E', 'elena.flores@uni.edu.mx', 'elena.flr@gmail.com', '5512345686', 'Ejecutiva', 1, 'Alta'),
(10, 'Javier', 'Ruiz', 'Ortiz', '20230010', 110, 'Contaduría', 9, 'E', 'javier.ruiz@uni.edu.mx', 'javier.ruiz@gmail.com', '5512345687', 'Ejecutiva', 0, 'Alta'),
(11, 'Adriana', 'Mendoza', 'Castro', '20230011', 111, 'Ingeniería Industrial', 11, 'F', 'adriana.mendoza@uni.edu.mx', 'adriana.mdz@gmail.com', '5512345688', 'Escolarizada', 0, 'Alta'),
(12, 'Ricardo', 'Silva', 'Reyes', '20230012', 112, 'Ingeniería Industrial', 11, 'F', 'ricardo.silva@uni.edu.mx', 'ricardo.silva@gmail.com', '5512345689', 'Escolarizada', 1, 'Indeterminado'),
(13, 'Patricia', 'Castro', 'Guerrero', '20230013', 113, 'Comunicación', 5, 'G', 'patricia.castro@uni.edu.mx', 'paty.castro@gmail.com', '5512345690', 'Escolarizada', 0, 'Alta'),
(14, 'Oscar', 'Ortega', 'Navarro', '20230014', 114, 'Comunicación', 5, 'G', 'oscar.ortega@uni.edu.mx', 'oscar.ortega@gmail.com', '5512345691', 'Escolarizada', 0, 'Alta'),
(15, 'Lucía', 'Vargas', 'Medina', '20230015', 115, 'Arquitectura', 7, 'H', 'lucia.vargas@uni.edu.mx', 'lucia.vargas@gmail.com', '5512345692', 'Escolarizada', 1, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla asistencias
--

CREATE TABLE asistencias (
  id_asistencia int(11) NOT NULL,
  id_alumno int(11) NOT NULL,
  id_materia int(11) NOT NULL,
  id_salon int(11) NOT NULL,
  fecha date NOT NULL,
  hora_entrada time NOT NULL,
  estado_asistencia enum('Presente','Tarde','Ausente') DEFAULT 'Presente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla asistencias
--

INSERT INTO asistencias (id_asistencia, id_alumno, id_materia, id_salon, fecha, hora_entrada, estado_asistencia) VALUES
(1, 1, 1, 1, '2024-05-06', '07:05:00', 'Presente'),
(2, 1, 2, 1, '2024-05-07', '09:02:00', 'Presente'),
(3, 2, 1, 1, '2024-05-06', '07:15:00', 'Tarde'),
(4, 2, 2, 1, '2024-05-07', '09:20:00', 'Tarde'),
(5, 3, 3, 2, '2024-05-08', '11:05:00', 'Presente'),
(6, 3, 4, 2, '2024-05-09', '12:02:00', 'Presente'),
(7, 4, 3, 2, '2024-05-08', '11:15:00', 'Tarde'),
(8, 4, 4, 2, '2024-05-09', '12:20:00', 'Tarde'),
(9, 5, 5, 3, '2024-05-06', '12:05:00', 'Presente'),
(10, 5, 6, 3, '2024-05-10', '07:02:00', 'Presente'),
(11, 6, 5, 3, '2024-05-06', '12:15:00', 'Tarde'),
(12, 6, 6, 3, '2024-05-10', '07:20:00', 'Tarde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla horarios
--

CREATE TABLE horarios (
  id_horario int(11) NOT NULL,
  id_materia int(11) NOT NULL,
  id_salon int(11) NOT NULL,
  dia enum('Lu','Ma','Mi','Ju','Vi','Sa','Do') NOT NULL,
  hora_inicio time NOT NULL,
  hora_fin time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla horarios
--

INSERT INTO horarios (id_horario, id_materia, id_salon, dia, hora_inicio, hora_fin) VALUES
(1, 1, 1, 'Lu', '07:00:00', '09:00:00'),
(2, 1, 1, 'Ju', '07:00:00', '09:00:00'),
(3, 2, 1, 'Ma', '09:00:00', '11:00:00'),
(4, 2, 1, 'Vi', '09:00:00', '11:00:00'),
(5, 3, 2, 'Mi', '11:00:00', '13:00:00'),
(6, 3, 2, 'Sa', '11:00:00', '13:00:00'),
(7, 4, 2, 'Ju', '12:00:00', '14:00:00'),
(8, 4, 2, 'Do', '12:00:00', '14:00:00'),
(9, 5, 3, 'Lu', '12:00:00', '14:00:00'),
(10, 5, 3, 'Mi', '12:00:00', '14:00:00'),
(11, 6, 3, 'Vi', '07:00:00', '09:00:00'),
(12, 6, 3, 'Do', '07:00:00', '09:00:00'),
(13, 7, 4, 'Ma', '11:00:00', '13:00:00'),
(14, 7, 4, 'Ju', '11:00:00', '13:00:00'),
(15, 8, 4, 'Sa', '09:00:00', '11:00:00'),
(16, 8, 4, 'Do', '09:00:00', '11:00:00'),
(17, 1, 5, 'Lu', '09:00:00', '11:00:00'),
(18, 1, 5, 'Mi', '09:00:00', '11:00:00'),
(19, 2, 5, 'Vi', '12:00:00', '14:00:00'),
(20, 2, 5, 'Do', '12:00:00', '14:00:00'),
(21, 3, 6, 'Ma', '07:00:00', '09:00:00'),
(22, 3, 6, 'Ju', '07:00:00', '09:00:00'),
(23, 4, 6, 'Sa', '11:00:00', '13:00:00'),
(24, 4, 6, 'Do', '11:00:00', '13:00:00'),
(25, 5, 7, 'Lu', '11:00:00', '13:00:00'),
(26, 5, 7, 'Mi', '11:00:00', '13:00:00'),
(27, 6, 7, 'Vi', '09:00:00', '11:00:00'),
(28, 6, 7, 'Do', '09:00:00', '11:00:00'),
(29, 7, 8, 'Ma', '12:00:00', '14:00:00'),
(30, 7, 8, 'Ju', '12:00:00', '14:00:00'),
(31, 8, 8, 'Sa', '07:00:00', '09:00:00'),
(32, 8, 8, 'Do', '07:00:00', '09:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla materias
--

CREATE TABLE materias (
  id_materia int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  clave varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla materias
--

INSERT INTO materias (id_materia, nombre, clave) VALUES
(1, 'Inteligencia Artificial', 'IA501'),
(2, 'Redes de Computadoras', 'RC301'),
(3, 'Diseño de Bases de Datos', 'BD401'),
(4, 'Programación Avanzada', 'PA201'),
(5, 'Derecho Civil', 'DC701'),
(6, 'Psicología Organizacional', 'PO101'),
(7, 'Contabilidad de Costos', 'CC901'),
(8, 'Comunicación Digital', 'CD501');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla pagos
--

CREATE TABLE pagos (
  id_pago int(11) NOT NULL,
  id_alumno int(11) NOT NULL,
  fecha_pago date NOT NULL,
  monto decimal(10,2) NOT NULL,
  comprobante varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla pagos
--

INSERT INTO pagos (id_pago, id_alumno, fecha_pago, monto, comprobante) VALUES
(1, 1, '2024-04-15', 2500.00, 'pago_20230001_abr.pdf'),
(2, 2, '2024-04-16', 1500.00, 'pago_20230002_abr.pdf'),
(3, 3, '2024-04-10', 3200.00, 'pago_20230003_abr.pdf'),
(4, 5, '2024-04-18', 2800.00, 'pago_20230005_abr.pdf'),
(5, 7, '2024-04-20', 2000.00, 'pago_20230007_abr.pdf'),
(6, 9, '2024-04-22', 3500.00, 'pago_20230009_abr.pdf'),
(7, 11, '2024-04-25', 4000.00, 'pago_20230011_abr.pdf'),
(8, 13, '2024-04-28', 1800.00, 'pago_20230013_abr.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla salones
--

CREATE TABLE salones (
  id_salon int(11) NOT NULL,
  nombre char(1) NOT NULL,
  capacidad int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla salones
--

INSERT INTO salones (id_salon, nombre, capacidad) VALUES
(1, 'A', 30),
(2, 'B', 25),
(3, 'C', 40),
(4, 'D', 35),
(5, 'E', 20),
(6, 'F', 45),
(7, 'G', 30),
(8, 'H', 25);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla alumnos
--
ALTER TABLE alumnos
  ADD PRIMARY KEY (id_alumno),
  ADD UNIQUE KEY matricula (matricula);

--
-- Indices de la tabla asistencias
--
ALTER TABLE asistencias
  ADD PRIMARY KEY (id_asistencia),
  ADD KEY id_alumno (id_alumno),
  ADD KEY id_materia (id_materia),
  ADD KEY id_salon (id_salon);

--
-- Indices de la tabla horarios
--
ALTER TABLE horarios
  ADD PRIMARY KEY (id_horario),
  ADD KEY id_materia (id_materia),
  ADD KEY id_salon (id_salon);

--
-- Indices de la tabla materias
--
ALTER TABLE materias
  ADD PRIMARY KEY (id_materia),
  ADD UNIQUE KEY clave (clave);

--
-- Indices de la tabla pagos
--
ALTER TABLE pagos
  ADD PRIMARY KEY (id_pago),
  ADD KEY id_alumno (id_alumno);

--
-- Indices de la tabla salones
--
ALTER TABLE salones
  ADD PRIMARY KEY (id_salon),
  ADD UNIQUE KEY nombre (nombre);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla alumnos
--
ALTER TABLE alumnos
  MODIFY id_alumno int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla asistencias
--
ALTER TABLE asistencias
  MODIFY id_asistencia int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla horarios
--
ALTER TABLE horarios
  MODIFY id_horario int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla materias
--
ALTER TABLE materias
  MODIFY id_materia int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla pagos
--
ALTER TABLE pagos
  MODIFY id_pago int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla salones
--
ALTER TABLE salones
  MODIFY id_salon int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla asistencias
--
ALTER TABLE asistencias
  ADD CONSTRAINT asistencias_ibfk_1 FOREIGN KEY (id_alumno) REFERENCES alumnos (id_alumno),
  ADD CONSTRAINT asistencias_ibfk_2 FOREIGN KEY (id_materia) REFERENCES materias (id_materia),
  ADD CONSTRAINT asistencias_ibfk_3 FOREIGN KEY (id_salon) REFERENCES salones (id_salon);

--
-- Filtros para la tabla horarios
--
ALTER TABLE horarios
  ADD CONSTRAINT horarios_ibfk_1 FOREIGN KEY (id_materia) REFERENCES materias (id_materia),
  ADD CONSTRAINT horarios_ibfk_2 FOREIGN KEY (id_salon) REFERENCES salones (id_salon);

--
-- Filtros para la tabla pagos
--
ALTER TABLE pagos
  ADD CONSTRAINT pagos_ibfk_1 FOREIGN KEY (id_alumno) REFERENCES alumnos (id_alumno);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

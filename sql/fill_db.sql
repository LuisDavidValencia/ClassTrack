-- =========================================
-- 2. POBLADO DE DATOS DE EJEMPLO
-- =========================================

-- Alumnos
INSERT INTO alumnos (
  nombre, apellido_paterno, apellido_materno, matricula, id_huella,
  carrera, cuatrimestre, grupo, correo_institucional,
  correo_personal, telefono, modalidad, deuda, estado
) VALUES
('Juan', 'Perez', 'Gomez', '20230001', 1,
 'Ingenieria en Sistemas', 5, 'A', 'juan.perez@uni.edu.mx',
 'juanp@gmail.com', '5551234567', 'Escolarizada', 0, 'Alta'),
('Ana', 'Lopez', 'Martinez', '20230002', 2,
 'Administracion de Empresas', 3, 'B', 'ana.lopez@uni.edu.mx',
 'ana.lopez@gmail.com', '5559876543', 'Ejecutiva', 1, 'Alta');

-- Materias
INSERT INTO materias (nombre, clave) VALUES
('Inteligencia Artificial','IA101'),
('Redes de Computadora','RD201'),
('Tecnologias y Diseno Web','TDW301'),
('Principios de Arquitecturas Cloud','CLD401'),
('Ingles IV','ING401');

-- Salones
INSERT INTO salones (nombre, capacidad) VALUES
('A1',40),
('B2',35);

-- Horarios para A1
INSERT INTO horarios (id_materia,id_salon,dia,hora_inicio,hora_fin) VALUES
(1,1,'Lu','07:00:00','07:55:00'),
(2,1,'Lu','08:00:00','09:55:00'),
(3,1,'Ma','07:00:00','08:55:00'),
(4,1,'Ma','09:00:00','10:55:00'),
(5,1,'Vi','09:00:00','10:00:00'),
(2,1,'Vi','10:00:00','11:00:00');

-- Horarios para B2
INSERT INTO horarios (id_materia,id_salon,dia,hora_inicio,hora_fin) VALUES
(1,2,'Lu','11:00:00','12:00:00'),
(4,2,'Ju','07:00:00','08:55:00');

-- Pagos
INSERT INTO pagos (id_alumno,fecha_pago,monto,comprobante) VALUES
(1,'2024-04-20',1500.00,'comprobante_juan.pdf'),
(2,'2024-04-22',2000.00,'comprobante_ana.pdf');

-- Asistencias
INSERT INTO asistencias (id_alumno,id_materia,id_salon,fecha,hora_entrada,estado_asistencia) VALUES
(1,1,1,'2024-04-29','07:05:00','Presente'),
(2,2,1,'2024-04-29','08:10:00','Tarde');

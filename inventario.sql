-- Crear nueva base de datos
CREATE DATABASE sistema_integrado;
USE sistema_integrado;

-- Tablas de USUARIOS (de la primera BD)
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  dni VARCHAR(11) NOT NULL,
  nombres_apellidos VARCHAR(140) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  telefono VARCHAR(15) NOT NULL,
  estado INT DEFAULT 1,
  password VARCHAR(1000) NOT NULL,
  reset_password INT DEFAULT 0,
  token_password VARCHAR(30) DEFAULT '',
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sesiones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  fecha_hora_inicio DATETIME NOT NULL,
  fecha_hora_fin DATETIME NOT NULL,
  token VARCHAR(30) NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE
);

-- Tablas de CONTENIDO (de la segunda BD)
CREATE TABLE programas_estudio (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT
);

CREATE TABLE semestres_lista (
  id TINYINT UNSIGNED PRIMARY KEY,
  descripcion VARCHAR(50)
);

CREATE TABLE estudiantes (
  dni CHAR(8) PRIMARY KEY COMMENT 'DNI único (formato de 8 dígitos)',
  nombres VARCHAR(100) NOT NULL COMMENT 'Nombres completos del estudiante',
  apellido_paterno VARCHAR(50) NOT NULL COMMENT 'Apellido paterno',
  apellido_materno VARCHAR(50) NOT NULL COMMENT 'Apellido materno',
  estado ENUM('activo','inactivo','graduado','suspendido') DEFAULT 'activo',
  semestre TINYINT UNSIGNED NOT NULL COMMENT 'Semestre actual del estudiante',
  programa_id INT UNSIGNED NOT NULL COMMENT 'ID del programa de estudio',
  fecha_matricula DATE COMMENT 'Fecha de matrícula del estudiante',
  FOREIGN KEY (semestre) REFERENCES semestres_lista(id) ON UPDATE CASCADE,
  FOREIGN KEY (programa_id) REFERENCES programas_estudio(id) ON UPDATE CASCADE
);

-- Insertar datos de programas_estudio
INSERT INTO programas_estudio (id, nombre, descripcion) VALUES
(1, 'Diseño y Programación Web', NULL),
(2, 'Enfermería Técnica', NULL),
(3, 'Mecánica Automotriz', NULL),
(4, 'Producción Agropecuaria', NULL),
(5, 'Industrias de Alimentos y Bebidas', NULL);

-- Insertar datos de semestres_lista
INSERT INTO semestres_lista (id, descripcion) VALUES
(1, 'Primer Semestre'),
(2, 'Segundo Semestre'),
(3, 'Tercer Semestre'),
(4, 'Cuarto Semestre'),
(5, 'Quinto Semestre'),
(6, 'Sexto Semestre');


-- Insertar datos de estudiantes
INSERT INTO estudiantes (dni, nombres, apellido_paterno, apellido_materno, estado, semestre, programa_id, fecha_matricula) VALUES
('00000003', 'Pedro Antonio', 'Sánchez', 'Torres', 'inactivo', 5, 3, NULL),
('00000004', 'Ana Sofía', 'Martínez', 'García', 'activo', 7, 4, '2024-01-20'),
('00000005', 'Luis Fernando', 'Ramírez', 'Vega', 'graduado', 9, 5, '2023-03-05'),
('00000006', 'Carmen Rosa', 'Flores', 'Ríos', 'activo', 2, 1, '2024-07-12'),
('00000007', 'Diego Armando', 'Cruz', 'Mendoza', 'suspendido', 4, 2, NULL),
('00000008', 'Laura Isabel', 'Vargas', 'Castillo', 'activo', 6, 3, '2023-09-01'),
('00000009', 'Jorge Luis', 'Herrera', 'Morales', 'activo', 8, 4, '2024-02-10'),
('00000010', 'Sofía Alejandra', 'Díaz', 'Rojas', 'inactivo', 10, 5, NULL),
('00000011', 'Ricardo José', 'Ortiz', 'Cueva', 'activo', 12, 1, '2023-11-15'),
('00000012', 'Gabriela Lucía', 'Paredes', 'Salazar', 'activo', 1, 2, '2024-03-20'),
('00000013', 'Miguel Ángel', 'Campos', 'Núñez', 'graduado', 3, 3, '2023-06-25'),
('00000014', 'Valeria Paola', 'Reyes', 'Guzmán', 'activo', 5, 4, '2024-08-05'),
('00000015', 'Andrés Felipe', 'Molina', 'Chávez', 'activo', 7, 5, '2023-04-10'),
('00000016', 'Daniela Fernanda', 'Silva', 'Bazán', 'inactivo', 9, 1, NULL),
('00000017', 'Carlos Eduardo', 'Ramos', 'Vera', 'activo', 11, 2, '2024-01-15'),
('00000018', 'Patricia Elena', 'Suárez', 'León', 'activo', 2, 3, '2023-07-20'),
('00000019', 'Raúl Alejandro', 'Castro', 'Mejía', 'suspendido', 4, 4, NULL),
('00000020', 'Camila Andrea', 'Lara', 'Hurtado', 'activo', 6, 5, '2024-02-25'),
('00000021', 'Sebastián Ignacio', 'Pinto', 'Soto', 'activo', 8, 1, '2023-10-10'),
('00000022', 'Verónica Lucía', 'Aguilar', 'Cordero', 'graduado', 10, 2, '2023-05-15'),
('00000023', 'Felipe Andrés', 'Montoya', 'Rivas', 'activo', 12, 3, '2024-06-01'),
('00000024', 'Natalia Sofía', 'Espinoza', 'Tapia', 'activo', 1, 4, '2024-09-01'),
('77501319', 'yeferson', 'chimayco', 'carbajal', 'activo', 6, 1, '2023-04-01');

-- Insertar datos de usuarios (de la primera BD)
INSERT INTO usuarios (id, dni, nombres_apellidos, correo, telefono, estado, password, reset_password, token_password, fecha_registro) VALUES
(1, '11112222', 'admin', 'admin@gmail.comm', '987654321', 1, '$2y$10$IeNRkcso2I60YiFEo8gKmeQEyWhTVq9TETpTgSenx380IaeWOSbv6', 1, 'syF9RDJZr*$pk(hSZjfO}ALrmfB]E[', '2025-04-04 16:20:51'),
(2, '70198965', 'yucra curo ', 'yucrac@gmail.com', '12345611', 1, '$2y$10$eYm6sJB.gf6SWDfad1CDT.ZHcpTBI/3XfL/fA5KT4KXdv3ZgPSW6C', 0, '', '2025-04-04 16:54:14'),
(3, 'ss', 'ss', 'ss', 'ss', 1, '$2y$10$o4roS5UGJWwdbRqzLD7QYexqmtnZli9blSKQGfdAFXL6K7h0Ef1Bq', 0, '', '2025-04-04 21:20:33');

-- Crear índices para optimizar consultas
CREATE INDEX idx_usuarios_dni ON usuarios(dni);
CREATE INDEX idx_usuarios_correo ON usuarios(correo);
CREATE INDEX idx_estudiantes_apellido ON estudiantes(apellido_paterno);
CREATE INDEX idx_estudiantes_semestre ON estudiantes(semestre);
CREATE INDEX idx_estudiantes_programa ON estudiantes(programa_id);
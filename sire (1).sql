-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2025 a las 01:20:34
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
-- Base de datos: `sire`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `dni` char(8) NOT NULL COMMENT 'DNI único (formato de 8 dígitos)',
  `nombres` varchar(100) NOT NULL COMMENT 'Nombres completos del estudiante',
  `apellido_paterno` varchar(50) NOT NULL COMMENT 'Apellido paterno',
  `apellido_materno` varchar(50) NOT NULL COMMENT 'Apellido materno',
  `estado` enum('activo','inactivo','graduado','suspendido') NOT NULL DEFAULT 'activo' COMMENT 'Estado del estudiante',
  `semestre` tinyint(3) UNSIGNED NOT NULL COMMENT 'Semestre actual del estudiante',
  `programa_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del programa de estudio',
  `fecha_matricula` date DEFAULT NULL COMMENT 'Fecha de matrícula del estudiante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `estado`, `semestre`, `programa_id`, `fecha_matricula`) VALUES
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_estudio`
--

CREATE TABLE `programas_estudio` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del programa',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del programa (e.g., Ingeniería de Sistemas)',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción opcional del programa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_estudio`
--

INSERT INTO `programas_estudio` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Diseño y Programación Web', NULL),
(2, 'Enfermería Técnica', NULL),
(3, 'Mecánica Automotriz', NULL),
(4, 'Producción Agropecuaria', NULL),
(5, 'Industrias de Alimentos y Bebidas', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semestres_lista`
--

CREATE TABLE `semestres_lista` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador único del semestre (1-12)',
  `descripcion` varchar(50) DEFAULT NULL COMMENT 'Descripción opcional (e.g., "Primer Semestre")'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `semestres_lista`
--

INSERT INTO `semestres_lista` (`id`, `descripcion`) VALUES
(1, 'Primer Semestre'),
(2, 'Segundo Semestre'),
(3, 'Tercer Semestre'),
(4, 'Cuarto Semestre'),
(5, 'Quinto Semestre'),
(6, 'Sexto Semestre'),
(7, 'Séptimo Semestre'),
(8, 'Octavo Semestre'),
(9, 'Noveno Semestre'),
(10, 'Décimo Semestre'),
(11, 'Undécimo Semestre'),
(12, 'Duodécimo Semestre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del usuario',
  `username` varchar(50) NOT NULL COMMENT 'Nombre de usuario único para inicio de sesión',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña en texto plano (temporal)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin2025'),
(2, 'chima', 'chima2025');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `idx_apellido_paterno` (`apellido_paterno`),
  ADD KEY `idx_semestre` (`semestre`),
  ADD KEY `idx_programa_id` (`programa_id`);

--
-- Indices de la tabla `programas_estudio`
--
ALTER TABLE `programas_estudio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `semestres_lista`
--
ALTER TABLE `semestres_lista`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `programas_estudio`
--
ALTER TABLE `programas_estudio`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del programa', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario', AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`semestre`) REFERENCES `semestres_lista` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`programa_id`) REFERENCES `programas_estudio` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

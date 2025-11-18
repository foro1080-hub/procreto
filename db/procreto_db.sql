-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2025 a las 14:21:33
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
-- Base de datos: `procreto_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `descripcion_area` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id_area`, `nombre_area`, `descripcion_area`) VALUES
(1, 'Producción', 'Área encargada de la producción de los productos principales.'),
(2, 'Fundición', 'Área donde se realizan procesos de fundición y manejo de hornos.'),
(3, 'Acabados', NULL),
(4, 'Mantenimiento', NULL),
(5, 'RRHH', NULL),
(6, 'Logística', NULL),
(7, 'Almacén', NULL),
(8, 'Sistemas', NULL),
(9, 'Calidad', 'Área responsable del control y aseguramiento de la calidad.'),
(10, 'Contabilidad', NULL),
(11, 'SST', NULL),
(12, 'Ingeniería', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `nombre_cargo` varchar(100) NOT NULL,
  `descripcion_cargo` text DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `nombre_cargo`, `descripcion_cargo`, `id_area`) VALUES
(1, 'Operario 1', 'Trabajador encargado de operaciones de línea. Niveles 1-5 según experiencia.', 1),
(2, 'Operario 2', 'Trabajador encargado de operaciones de línea. Niveles 1-5 según experiencia.', 1),
(3, 'Jefe de Mantenimiento', 'Responsable del mantenimiento preventivo y correctivo de la planta.', 4),
(4, 'Supervisor Principal', NULL, 1),
(5, 'Auxiliar de Almacén', 'Soporte en recepción y despacho de mercancía del almacén.', 7),
(6, 'Ingeniero de Planta', NULL, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_usuario`
--

CREATE TABLE `estado_usuario` (
  `id_estado_usuario` int(11) NOT NULL,
  `nombre_estado_usuario` varchar(50) NOT NULL,
  `descripcion_estado_usuario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estado_usuario`
--

INSERT INTO `estado_usuario` (`id_estado_usuario`, `nombre_estado_usuario`, `descripcion_estado_usuario`) VALUES
(1, 'Activo', 'Empleado activo, en nómina y con permiso para acceder al sistema.'),
(2, 'Inactivo', NULL),
(3, 'Despedido', NULL),
(4, 'Retirado', 'Empleado fuera de nómina por retiro o retiro voluntario.'),
(5, 'Suspendido', 'Empleado suspendido temporalmente por motivos disciplinarios o médicos.'),
(6, 'Vacaciones', NULL),
(7, 'Pensionado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `limitacion`
--

CREATE TABLE `limitacion` (
  `id_limitacion` int(11) NOT NULL,
  `nombre_limitacion` varchar(100) NOT NULL,
  `descripcion_limitacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `limitacion`
--

INSERT INTO `limitacion` (`id_limitacion`, `nombre_limitacion`, `descripcion_limitacion`) VALUES
(1, 'Problemas de vista', 'Usa gafas permanentemente'),
(2, 'Lesión rodilla', 'Limitación parcial en movilidad'),
(3, 'Incapacidad temporal', 'Incapacidad médica por enfermedad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requerimiento`
--

CREATE TABLE `requerimiento` (
  `id_requerimiento` int(11) NOT NULL,
  `id_emisor_usuario` int(11) DEFAULT NULL,
  `id_receptor_usuario` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `titulo_requerimiento` varchar(150) DEFAULT NULL,
  `descripcion_requerimiento` text DEFAULT NULL,
  `tipo_requerimiento` varchar(100) DEFAULT NULL,
  `prioridad_requerimiento` enum('Alta','Media','Baja') DEFAULT NULL,
  `estado_requerimiento` enum('Pendiente','Completado','Cancelado') DEFAULT NULL,
  `fecha_creacion_requerimiento` datetime DEFAULT current_timestamp(),
  `fecha_fin_requerimiento` datetime DEFAULT NULL,
  `archivo_adjunto_requerimiento` varchar(255) DEFAULT NULL,
  `observaciones_requerimiento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `requerimiento`
--

INSERT INTO `requerimiento` (`id_requerimiento`, `id_emisor_usuario`, `id_receptor_usuario`, `id_area`, `titulo_requerimiento`, `descripcion_requerimiento`, `tipo_requerimiento`, `prioridad_requerimiento`, `estado_requerimiento`, `fecha_creacion_requerimiento`, `fecha_fin_requerimiento`, `archivo_adjunto_requerimiento`, `observaciones_requerimiento`) VALUES
(1, 1, 2, 4, 'Solicitud de mantenimiento', 'Reparación de máquina fundidora No.3', 'Mantenimiento', 'Alta', 'Pendiente', '2025-11-12 09:03:57', NULL, NULL, NULL),
(2, 2, 1, 1, 'Revisión de producción', 'Informe semanal de producción requerido', 'Administrativo', 'Media', 'Pendiente', '2025-11-12 09:03:57', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL,
  `descripcion_rol` text DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion_rol`, `imagen_url`) VALUES
(1, 'Super Admin', 'Cuenta con todos los permisos del sistema, incluyendo gestión de usuarios, configuración y control general.', 'https://images.pexels.com/photos/546819/pexels-photo-546819.jpeg'),
(2, 'Administración', 'Personal administrativo encargado de la gestión contable, financiera y documental de la empresa.', 'https://images.pexels.com/photos/53621/calculator-calculation-insurance-finance-53621.jpeg'),
(3, 'Jefe de Planta', 'Responsable técnico de la planta, supervisa operaciones, calidad del producto y cumplimiento de metas de producción.', 'https://images.pexels.com/photos/257700/pexels-photo-257700.jpeg'),
(4, 'Supervisor', NULL, NULL),
(5, 'Empleado', NULL, NULL),
(6, 'SST', 'Encargado de la seguridad y salud en el trabajo. Supervisa cumplimiento de normas y prevención de riesgos laborales.', 'https://images.pexels.com/photos/1108101/pexels-photo-1108101.jpeg'),
(7, 'RRHH', 'Gestión del talento humano: reclutamiento, capacitación y bienestar de los empleados.', 'https://images.pexels.com/photos/3747455/pexels-photo-3747455.jpeg'),
(8, 'Ingeniero', 'Encargado de los procesos técnicos y de innovación dentro de los proyectos de ingeniería de la empresa.', 'https://images.pexels.com/photos/256381/pexels-photo-256381.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_turno` int(11) NOT NULL,
  `nombre_turno` varchar(50) NOT NULL,
  `descripcion_turno` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `nombre_turno`, `descripcion_turno`) VALUES
(1, 'Turno 1', 'Lunes a viernes: 6am a 2pm (20 min descanso). Sábado: 6am a 12m (20 min descanso).'),
(2, 'Turno 2', 'Lunes a viernes: 2pm a 10pm (20 min descanso). Sábado: 12pm a 6pm (20 min descanso).'),
(3, 'Turno A', 'Lunes a viernes: 8am a 5pm (1h almuerzo + 20 min descanso). Sábado: 6am a 12pm (20 min descanso).');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `tipo_documento_usuario` varchar(10) DEFAULT NULL,
  `numero_documento_usuario` varchar(20) DEFAULT NULL,
  `nombres_usuario` varchar(100) DEFAULT NULL,
  `primer_apellido_usuario` varchar(50) DEFAULT NULL,
  `segundo_apellido_usuario` varchar(50) DEFAULT NULL,
  `fecha_nacimiento_usuario` date DEFAULT NULL,
  `genero_usuario` enum('Masculino','Femenino','Otro') DEFAULT NULL,
  `telefono_usuario` varchar(20) DEFAULT NULL,
  `correo_usuario` varchar(100) DEFAULT NULL,
  `direccion_usuario` varchar(150) DEFAULT NULL,
  `ciudad_usuario` varchar(100) DEFAULT NULL,
  `estado_civil_usuario` varchar(50) DEFAULT NULL,
  `id_estado_usuario` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `tipo_contrato_usuario` varchar(100) DEFAULT NULL,
  `fecha_ingreso_usuario` date DEFAULT NULL,
  `fecha_retiro_usuario` date DEFAULT NULL,
  `id_turno` int(11) DEFAULT NULL,
  `foto_usuario` varchar(255) DEFAULT NULL,
  `pensiones_usuario` varchar(100) DEFAULT NULL,
  `eps_usuario` varchar(100) DEFAULT NULL,
  `arl_usuario` varchar(100) DEFAULT NULL,
  `profesion_usuario` varchar(100) DEFAULT NULL,
  `nivel_estudio_usuario` varchar(100) DEFAULT NULL,
  `observaciones_usuario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `tipo_documento_usuario`, `numero_documento_usuario`, `nombres_usuario`, `primer_apellido_usuario`, `segundo_apellido_usuario`, `fecha_nacimiento_usuario`, `genero_usuario`, `telefono_usuario`, `correo_usuario`, `direccion_usuario`, `ciudad_usuario`, `estado_civil_usuario`, `id_estado_usuario`, `id_cargo`, `id_area`, `id_rol`, `tipo_contrato_usuario`, `fecha_ingreso_usuario`, `fecha_retiro_usuario`, `id_turno`, `foto_usuario`, `pensiones_usuario`, `eps_usuario`, `arl_usuario`, `profesion_usuario`, `nivel_estudio_usuario`, `observaciones_usuario`) VALUES
(1, 'CC', '1005288926', 'Yerson Manuel', 'Cardozo', 'Barajas', '1990-05-10', 'Masculino', '3105559999', 'yerson@procreto.com', 'Calle 12 #5-10', 'Bucaramanga', 'Soltero', 1, 1, 8, 1, 'Término Indefinido', '2021-01-10', NULL, 1, 'foto1.jpg', 'Colpensiones', 'Sura', 'ARL Sura', 'Ingeniero Industrial', 'Profesional', 'Empleado destacado.'),
(2, 'CC', '1109876543', 'María Fernanda', 'Gómez', 'Ruiz', '1988-11-23', 'Femenino', '3174448888', 'maria@procreto.com', 'Cra 45 #10-20', 'Bucaramanga', 'Casada', 1, 3, 4, 3, 'Término Fijo', '2022-03-15', NULL, 3, 'foto2.jpg', 'Porvenir', 'Coomeva', 'ARL Sura', 'Ingeniera Mecánica', 'Profesional', 'Responsable de mantenimiento.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_limitacion`
--

CREATE TABLE `usuario_limitacion` (
  `id_usuario` int(11) NOT NULL,
  `id_limitacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_limitacion`
--

INSERT INTO `usuario_limitacion` (`id_usuario`, `id_limitacion`) VALUES
(1, 1),
(2, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  ADD PRIMARY KEY (`id_estado_usuario`);

--
-- Indices de la tabla `limitacion`
--
ALTER TABLE `limitacion`
  ADD PRIMARY KEY (`id_limitacion`);

--
-- Indices de la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  ADD PRIMARY KEY (`id_requerimiento`),
  ADD KEY `id_emisor_usuario` (`id_emisor_usuario`),
  ADD KEY `id_receptor_usuario` (`id_receptor_usuario`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_turno`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `numero_documento_usuario` (`numero_documento_usuario`),
  ADD KEY `id_estado_usuario` (`id_estado_usuario`),
  ADD KEY `id_cargo` (`id_cargo`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_turno` (`id_turno`);

--
-- Indices de la tabla `usuario_limitacion`
--
ALTER TABLE `usuario_limitacion`
  ADD PRIMARY KEY (`id_usuario`,`id_limitacion`),
  ADD KEY `id_limitacion` (`id_limitacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  MODIFY `id_estado_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `limitacion`
--
ALTER TABLE `limitacion`
  MODIFY `id_limitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  MODIFY `id_requerimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD CONSTRAINT `cargo_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);

--
-- Filtros para la tabla `requerimiento`
--
ALTER TABLE `requerimiento`
  ADD CONSTRAINT `requerimiento_ibfk_1` FOREIGN KEY (`id_emisor_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `requerimiento_ibfk_2` FOREIGN KEY (`id_receptor_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `requerimiento_ibfk_3` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_estado_usuario`) REFERENCES `estado_usuario` (`id_estado_usuario`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `usuario_ibfk_5` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`);

--
-- Filtros para la tabla `usuario_limitacion`
--
ALTER TABLE `usuario_limitacion`
  ADD CONSTRAINT `usuario_limitacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `usuario_limitacion_ibfk_2` FOREIGN KEY (`id_limitacion`) REFERENCES `limitacion` (`id_limitacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

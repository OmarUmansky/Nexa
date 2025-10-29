-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 23:51:25
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
-- Base de datos: `nexaaa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `ci_usuario` int(8) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ci_usuario` int(8) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `ciudad` int(9) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ci_usuario`, `nombre`, `apellido`, `ciudad`, `correo`, `contraseña`, `telefono`, `tipo`) VALUES
(0, 'Root', 'Empresa', 9, 'admin@gmail.com', '$2y$10$Go4w.Xrqk40nHRVJsgePCeASD1LTtbPAILE.lmx2lpfNtlfDrs.yK', 123456, NULL),
(324723, 'Dani', 'daniel', 9, 'mani@gmail.com', '$2y$10$hYpp6egdJDyj5IR8Mf4Phu.yRVcbHIchxmn/ycuJkNt0huSScysSS', 12313, NULL),
(12323747, 'Meli', 'Male', 9, 'Meli@gmail.com', '$2y$10$aEWnHBEZYoIhmUYxn13zuehl5ASHLcyad/WDo2h7Zete3U9XCTNOy', 734748, NULL),
(55772929, 'Admin', 'Admin', 9, 'admin@gmail.com', '$2y$10$vF1w1cbsF2MDlxnERKnA0OhAptptptuwEthaB7PSSOR2BW5wn9KGC', 12345, NULL),
(74837623, 'Daji', 'Daji', 9, 'dea@gmail.com', '$2y$10$CSp6phU6N0Dy74FoRQ16pOpkdxfCVCqCTA7jZy2wwpvAMAfeLWlDC', 1234763, NULL),
(2147483647, 'Lupe', 'lupee', 9, 'lupe@gmail.com', '$2y$10$sx1MZeVsu/NRd7qLsRVpDegMOSmUA6DdGFq.m0.XQd5YAbSm.sQ/W', 1234773, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_departamento`, `nombre`) VALUES
(1, 'Artigas'),
(2, 'Canelones'),
(3, 'Cerro Largo'),
(4, 'Colonia'),
(5, 'Durazno'),
(6, 'Flores'),
(7, 'Florida'),
(8, 'Lavalleja'),
(9, 'Maldonado'),
(10, 'Montevideo'),
(11, 'Paysandú'),
(12, 'Río Negro'),
(13, 'Rivera'),
(14, 'Rocha'),
(15, 'Salto'),
(16, 'San José'),
(17, 'Soriano'),
(18, 'Tacuarembó'),
(19, 'Treinta y Tres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `ci_usuario` int(8) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `horario_inc` datetime DEFAULT NULL,
  `horario_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`ci_usuario`, `nombre`, `apellido`, `correo`, `contraseña`, `horario_inc`, `horario_fin`) VALUES
(6589748, 'Empleado', '1', 'empleado1@gmail.com', '$2y$10$aleH7Q5UukLPwOhdI5ok.Onq/otuPPVwZQpC.SCTW0OgCX/0yNxmq', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8748398, 'Lolo', 'Martinez', 'lolo@gmail.com', '$2y$10$EvLy5.r9Pjgiszbhn.s/buSx4Kw5COvjUeQZSdCG37ShzF3F0aGzq', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74863748, 'Empleado', '2', 'empleado2@gmail.com', '$2y$10$V27kcrmUIoD5m3shjnEeCOItW3XBIFsKIIcMsx3fOsrgB1bZ8sK.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(742374237, 'Pepito', 'Juarez', 'pepi@gmail.com', '$2y$10$UzIBFpYTQL9ngULBnBKjD.48QtQdc7XQQhjzzBm04lgyoK5rS/blO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(883274328, 'juan', 'juancito', 'juani@gmail.com', '$2y$10$/lkgtpBYHki.88ADh2.exOHqjZaXs7G8fdg3/Ek71D1ISoa4PbMc2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `herramienta`
--

CREATE TABLE `herramienta` (
  `id_herramienta` int(11) NOT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `tipo_maquina` varchar(255) DEFAULT NULL,
  `ci_usuario_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_de_pago`
--

CREATE TABLE `historial_de_pago` (
  `id_pago` int(11) NOT NULL,
  `ci_usuario_empleado` int(11) DEFAULT NULL,
  `ingreso` int(11) DEFAULT NULL,
  `egreso` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `Hora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `ci_usuario_empleado` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `nombre` varchar(255) DEFAULT NULL,
  `id_registro` int(11) NOT NULL,
  `accion_realizada` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `precio` int(11) DEFAULT NULL,
  `tipo_servicio` varchar(255) DEFAULT NULL,
  `ci_usuario_empleado` int(11) DEFAULT NULL,
  `nombre_servicio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `precio`, `tipo_servicio`, `ci_usuario_empleado`, `nombre_servicio`) VALUES
(1, 0, 'Unisex', NULL, 'Lavado de pelo'),
(2, 0, 'Unisex', NULL, 'Coloración'),
(3, 0, 'Unisex', NULL, 'Servicio de maquilladora'),
(4, 0, 'Peluqueria', NULL, 'Corte de cabello'),
(5, 0, 'Peluqueria', NULL, 'Brushing'),
(6, 0, 'Peluqueria', NULL, 'Claritos'),
(7, 0, 'Barberia', NULL, 'Arreglo de barba'),
(8, 0, 'Barberia', NULL, 'Arreglo de bigote'),
(9, 0, 'Barberia', NULL, 'Corte de cabello');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicita`
--

CREATE TABLE `solicita` (
  `fecha` date DEFAULT NULL,
  `hora` datetime DEFAULT NULL,
  `ci_usuario_cliente` int(11) DEFAULT NULL,
  `ci_usuario_empleado` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ci_usuario`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ci_usuario`),
  ADD KEY `cliente_departamento` (`ciudad`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`ci_usuario`);

--
-- Indices de la tabla `herramienta`
--
ALTER TABLE `herramienta`
  ADD PRIMARY KEY (`id_herramienta`),
  ADD KEY `ci_usuario_empleado` (`ci_usuario_empleado`);

--
-- Indices de la tabla `historial_de_pago`
--
ALTER TABLE `historial_de_pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `ci_usuario_empleado` (`ci_usuario_empleado`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `ci_usuario_empleado` (`ci_usuario_empleado`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_registro`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `ci_usuario_empleado` (`ci_usuario_empleado`);

--
-- Indices de la tabla `solicita`
--
ALTER TABLE `solicita`
  ADD KEY `ci_usuario_empleado` (`ci_usuario_empleado`),
  ADD KEY `ci_usuario_cliente` (`ci_usuario_cliente`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `herramienta`
--
ALTER TABLE `herramienta`
  MODIFY `id_herramienta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_de_pago`
--
ALTER TABLE `historial_de_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_departamento` FOREIGN KEY (`ciudad`) REFERENCES `departamento` (`id_departamento`);

--
-- Filtros para la tabla `herramienta`
--
ALTER TABLE `herramienta`
  ADD CONSTRAINT `herramienta_ibfk_1` FOREIGN KEY (`ci_usuario_empleado`) REFERENCES `empleado` (`ci_usuario`);

--
-- Filtros para la tabla `historial_de_pago`
--
ALTER TABLE `historial_de_pago`
  ADD CONSTRAINT `historial_de_pago_ibfk_1` FOREIGN KEY (`ci_usuario_empleado`) REFERENCES `empleado` (`ci_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`ci_usuario_empleado`) REFERENCES `empleado` (`ci_usuario`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_registro`) REFERENCES `administrador` (`ci_usuario`),
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`id_registro`) REFERENCES `empleado` (`ci_usuario`),
  ADD CONSTRAINT `reportes_ibfk_3` FOREIGN KEY (`id_registro`) REFERENCES `cliente` (`ci_usuario`);

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `servicio_ibfk_1` FOREIGN KEY (`ci_usuario_empleado`) REFERENCES `empleado` (`ci_usuario`);

--
-- Filtros para la tabla `solicita`
--
ALTER TABLE `solicita`
  ADD CONSTRAINT `solicita_ibfk_1` FOREIGN KEY (`ci_usuario_empleado`) REFERENCES `empleado` (`ci_usuario`),
  ADD CONSTRAINT `solicita_ibfk_2` FOREIGN KEY (`ci_usuario_cliente`) REFERENCES `cliente` (`ci_usuario`),
  ADD CONSTRAINT `solicita_ibfk_3` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

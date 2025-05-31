-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql307.infinityfree.com
-- Tiempo de generación: 31-05-2025 a las 18:11:25
-- Versión del servidor: 10.6.19-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_39086323_transmetro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bus`
--

CREATE TABLE `bus` (
  `ID_Bus` int(11) NOT NULL,
  `PLACA_BUS` varchar(50) DEFAULT NULL,
  `ID_Linea` int(11) DEFAULT NULL,
  `DPI_Empleado` bigint(20) DEFAULT NULL,
  `ID_Parqueo` int(11) DEFAULT NULL,
  `Capacidad_Total` int(11) DEFAULT NULL,
  `Capacidad_Ruta` int(11) DEFAULT NULL,
  `ID_Estacion` int(11) DEFAULT NULL,
  `Estado` varchar(50) DEFAULT 'en línea'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bus`
--

INSERT INTO `bus` (`ID_Bus`, `PLACA_BUS`, `ID_Linea`, `DPI_Empleado`, `ID_Parqueo`, `Capacidad_Total`, `Capacidad_Ruta`, `ID_Estacion`, `Estado`) VALUES
(3, 'C-334QQQ', 3, 0, 4, 300, 200, 5, 'en línea'),
(5, 'C-337LLK', 3, 2665577650101, 4, 400, 300, 5, 'en taller'),
(6, 'C-339CCO', 9, 2665577650101, 4, 400, 500, 7, 'en línea'),
(7, 'C-555QQQ', 10, 123456789, 4, 400, 300, 7, 'en línea');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogomunicipalidad`
--

CREATE TABLE `catalogomunicipalidad` (
  `ID_Municipalidad` int(11) NOT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogomunicipalidad`
--

INSERT INTO `catalogomunicipalidad` (`ID_Municipalidad`, `Nombre`, `Direccion`, `Telefono`) VALUES
(3, 'Municipalidad de Guatemala', '21A Calle 6-77, Cdad. de Guatemala', 22858000),
(5, 'Muni zona 3', '17 calle avenida 2 z 3', 8888);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `DPI_Empleado` bigint(20) NOT NULL,
  `NIT_Empleado` varchar(20) DEFAULT NULL,
  `No_Licencia` varchar(20) DEFAULT NULL,
  `Tipo_Licencia` varchar(50) DEFAULT NULL,
  `P_Nombre` varchar(50) DEFAULT NULL,
  `S_Nombre` varchar(50) DEFAULT NULL,
  `T_Nombre` varchar(50) DEFAULT NULL,
  `P_Apellido` varchar(50) DEFAULT NULL,
  `S_Apellido` varchar(50) DEFAULT NULL,
  `C_Apellido` varchar(50) DEFAULT NULL,
  `Fecha_Nacimiento` date DEFAULT NULL,
  `Edad` int(11) DEFAULT NULL,
  `Num_Telefono` bigint(20) DEFAULT NULL,
  `Escolaridad` varchar(50) DEFAULT NULL,
  `Contacto_Emergencia` varchar(50) DEFAULT NULL,
  `Num_Contacto_Emergencia` bigint(20) DEFAULT NULL,
  `Estado` varchar(10) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`DPI_Empleado`, `NIT_Empleado`, `No_Licencia`, `Tipo_Licencia`, `P_Nombre`, `S_Nombre`, `T_Nombre`, `P_Apellido`, `S_Apellido`, `C_Apellido`, `Fecha_Nacimiento`, `Edad`, `Num_Telefono`, `Escolaridad`, `Contacto_Emergencia`, `Num_Contacto_Emergencia`, `Estado`) VALUES
(0, NULL, NULL, NULL, 'Sin', NULL, NULL, 'Asignar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Inactivo'),
(123456789, '885599', '123456789', 'B', 'Juan', 'Perez', '', 'Juarez', NULL, 'Castro', '1990-02-02', 35, 22334455, 'Diversificado', 'Juana', 88779966, 'Inactivo'),
(2523577600101, '80263763', '2523577600101', 'A', 'Jose', 'Carlos', '', 'Velasquez', NULL, 'Juarez', '1994-03-02', 31, 24337260, 'Diversificado', 'Rolando Velasquez', 5556668, 'Activo'),
(2665577650101, '95978469', '264918365', 'B', 'Test', 'Test', 'Test', 'Test', NULL, 'Test', '1999-05-28', 35, 55553456, 'Presidente', 'Test', 55555437, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacion`
--

CREATE TABLE `estacion` (
  `ID_Estacion` int(11) NOT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `ID_Municipalidad` int(11) DEFAULT NULL,
  `Capacidad` int(11) DEFAULT NULL,
  `Cantidad_Usuarios` int(11) DEFAULT NULL,
  `Estado` enum('abierta','cerrada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estacion`
--

INSERT INTO `estacion` (`ID_Estacion`, `Nombre`, `Direccion`, `ID_Municipalidad`, `Capacidad`, `Cantidad_Usuarios`, `Estado`) VALUES
(5, 'San Sebastian', '3a calle y 6a avenida', 3, 300, 200, 'abierta'),
(7, 'El calvario', '6ta avenida y 24 calle zona 1', 3, 500, 500, 'abierta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacion_linea`
--

CREATE TABLE `estacion_linea` (
  `ID_Estacion` int(11) NOT NULL,
  `ID_Linea` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estacion_linea`
--

INSERT INTO `estacion_linea` (`ID_Estacion`, `ID_Linea`) VALUES
(5, 3),
(5, 4),
(7, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea`
--

CREATE TABLE `linea` (
  `ID_Linea` int(11) NOT NULL,
  `Nombre_Linea` varchar(255) DEFAULT NULL,
  `ID_Municipalidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `linea`
--

INSERT INTO `linea` (`ID_Linea`, `Nombre_Linea`, `ID_Municipalidad`) VALUES
(3, 'Línea 1 test', 3),
(4, 'Línea 2 ', 3),
(6, 'Línea 6', 3),
(7, 'Línea 7', 3),
(8, 'Línea 12', 3),
(9, 'Línea 13', 3),
(10, 'Línea 18', 3),
(11, 'Linea Test', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parqueo`
--

CREATE TABLE `parqueo` (
  `ID_Parqueo` int(11) NOT NULL,
  `Nombre_Parqueo` varchar(255) DEFAULT NULL,
  `Ubicacion` varchar(255) DEFAULT NULL,
  `Telefono` bigint(20) DEFAULT NULL,
  `Capacidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parqueo`
--

INSERT INTO `parqueo` (`ID_Parqueo`, `Nombre_Parqueo`, `Ubicacion`, `Telefono`, `Capacidad`) VALUES
(4, 'Paralela', '14C33', 11223344, 300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_viaje`
--

CREATE TABLE `registro_viaje` (
  `ID_Registro` int(11) NOT NULL,
  `ID_Bus` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `ID_Estacion` int(11) DEFAULT NULL,
  `Cantidad_Usuarios` int(11) DEFAULT NULL,
  `Fecha_Registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_viaje`
--

INSERT INTO `registro_viaje` (`ID_Registro`, `ID_Bus`, `ID_Usuario`, `ID_Estacion`, `Cantidad_Usuarios`, `Fecha_Registro`) VALUES
(1, 3, NULL, 5, 150, '2025-05-26 10:00:20'),
(2, 3, NULL, 5, 25, '2025-05-27 23:24:14'),
(3, 3, NULL, 5, 55, '2025-05-28 20:35:04'),
(4, 6, NULL, 7, 200, '2025-05-30 11:41:35'),
(5, 3, NULL, 7, 45, '2025-05-31 12:13:06'),
(6, 3, NULL, 7, 66, '2025-05-31 12:56:58'),
(7, 3, NULL, 5, 4, '2025-05-31 14:18:09'),
(8, 5, NULL, 7, 6, '2025-05-31 15:04:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramo`
--

CREATE TABLE `tramo` (
  `ID_Tramo` int(11) NOT NULL,
  `ID_Linea` int(11) DEFAULT NULL,
  `ID_Estacion_Origen` int(11) DEFAULT NULL,
  `ID_Estacion_Destino` int(11) DEFAULT NULL,
  `Kilometros` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tramo`
--

INSERT INTO `tramo` (`ID_Tramo`, `ID_Linea`, `ID_Estacion_Origen`, `ID_Estacion_Destino`, `Kilometros`) VALUES
(2, 3, 5, 7, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Correo` varchar(255) DEFAULT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `Rol` varchar(50) DEFAULT NULL,
  `DPI_Empleado` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_Usuario`, `Nombre`, `Correo`, `Contraseña`, `Rol`, `DPI_Empleado`) VALUES
(3, 'Administrador', 'admin@correo.com', '$2y$10$4E9AbkpiJY3fgjXq8b7Z0e8DNi7ci0lJ/Z5MGXb0o.Bs3VebhToz2', 'admin', 2523577600101),
(4, 'Usuario Prueba', 'prueba@correo.com', '$2y$10$bm0No1pQsDIo/qTjWGutYu99H7fjJa87Gv4dAeLVJQBDSYYnpHpou', 'admin', 2523577600101),
(7, 'usuario99', 'usuario99@correo.com', '$2y$10$z6tiMEMhjc8rk4NixN6l..DKiJGBqniXR7jeyz7RnUFSHlAOsrTh6', 'usuario', 123456789);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
(2, 'administrador', '$2y$10$ZLAivpS9/bUvUzsl6A2Qq.6j3.7opQ8o15grgtpv/1ZCgt.urVgDi', 'usuario'),
(3, 'admin', '$2y$10$ODg7qYexSZ7yzAaC4KHe8.0PHInRNNy9HY6SVHw2dILMWXHzOh64O', 'admin'),
(4, 'jvelasquez', '$2y$10$VtrOROh0qmXzcEHumt5EEO1rjiF9UFOktwgXHT75i8W4wUb6St.O.', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`ID_Bus`),
  ADD KEY `ID_Linea` (`ID_Linea`),
  ADD KEY `DPI_Empleado` (`DPI_Empleado`),
  ADD KEY `ID_Parqueo` (`ID_Parqueo`),
  ADD KEY `ID_Estacion` (`ID_Estacion`);

--
-- Indices de la tabla `catalogomunicipalidad`
--
ALTER TABLE `catalogomunicipalidad`
  ADD PRIMARY KEY (`ID_Municipalidad`);

--
-- Indices de la tabla `estacion`
--
ALTER TABLE `estacion`
  ADD PRIMARY KEY (`ID_Estacion`),
  ADD KEY `ID_Municipalidad` (`ID_Municipalidad`);

--
-- Indices de la tabla `estacion_linea`
--
ALTER TABLE `estacion_linea`
  ADD PRIMARY KEY (`ID_Estacion`,`ID_Linea`),
  ADD KEY `ID_Linea` (`ID_Linea`);

--
-- Indices de la tabla `linea`
--
ALTER TABLE `linea`
  ADD PRIMARY KEY (`ID_Linea`),
  ADD KEY `ID_Municipalidad` (`ID_Municipalidad`);

--
-- Indices de la tabla `parqueo`
--
ALTER TABLE `parqueo`
  ADD PRIMARY KEY (`ID_Parqueo`);

--
-- Indices de la tabla `registro_viaje`
--
ALTER TABLE `registro_viaje`
  ADD PRIMARY KEY (`ID_Registro`),
  ADD KEY `ID_Bus` (`ID_Bus`),
  ADD KEY `ID_Usuario` (`ID_Usuario`),
  ADD KEY `ID_Estacion` (`ID_Estacion`);

--
-- Indices de la tabla `tramo`
--
ALTER TABLE `tramo`
  ADD PRIMARY KEY (`ID_Tramo`),
  ADD KEY `ID_Linea` (`ID_Linea`),
  ADD KEY `ID_Estacion_Origen` (`ID_Estacion_Origen`),
  ADD KEY `ID_Estacion_Destino` (`ID_Estacion_Destino`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD KEY `DPI_Empleado` (`DPI_Empleado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bus`
--
ALTER TABLE `bus`
  MODIFY `ID_Bus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `catalogomunicipalidad`
--
ALTER TABLE `catalogomunicipalidad`
  MODIFY `ID_Municipalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estacion`
--
ALTER TABLE `estacion`
  MODIFY `ID_Estacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `linea`
--
ALTER TABLE `linea`
  MODIFY `ID_Linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `parqueo`
--
ALTER TABLE `parqueo`
  MODIFY `ID_Parqueo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registro_viaje`
--
ALTER TABLE `registro_viaje`
  MODIFY `ID_Registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tramo`
--
ALTER TABLE `tramo`
  MODIFY `ID_Tramo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_ibfk_1` FOREIGN KEY (`ID_Linea`) REFERENCES `linea` (`ID_Linea`),
  ADD CONSTRAINT `bus_ibfk_2` FOREIGN KEY (`DPI_Empleado`) REFERENCES `empleado` (`DPI_Empleado`),
  ADD CONSTRAINT `bus_ibfk_3` FOREIGN KEY (`ID_Parqueo`) REFERENCES `parqueo` (`ID_Parqueo`),
  ADD CONSTRAINT `bus_ibfk_4` FOREIGN KEY (`ID_Estacion`) REFERENCES `estacion` (`ID_Estacion`);

--
-- Filtros para la tabla `estacion`
--
ALTER TABLE `estacion`
  ADD CONSTRAINT `estacion_ibfk_2` FOREIGN KEY (`ID_Municipalidad`) REFERENCES `catalogomunicipalidad` (`ID_Municipalidad`);

--
-- Filtros para la tabla `estacion_linea`
--
ALTER TABLE `estacion_linea`
  ADD CONSTRAINT `estacion_linea_ibfk_1` FOREIGN KEY (`ID_Estacion`) REFERENCES `estacion` (`ID_Estacion`),
  ADD CONSTRAINT `estacion_linea_ibfk_2` FOREIGN KEY (`ID_Linea`) REFERENCES `linea` (`ID_Linea`);

--
-- Filtros para la tabla `linea`
--
ALTER TABLE `linea`
  ADD CONSTRAINT `linea_ibfk_1` FOREIGN KEY (`ID_Municipalidad`) REFERENCES `catalogomunicipalidad` (`ID_Municipalidad`);

--
-- Filtros para la tabla `registro_viaje`
--
ALTER TABLE `registro_viaje`
  ADD CONSTRAINT `registro_viaje_ibfk_1` FOREIGN KEY (`ID_Bus`) REFERENCES `bus` (`ID_Bus`),
  ADD CONSTRAINT `registro_viaje_ibfk_2` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`),
  ADD CONSTRAINT `registro_viaje_ibfk_3` FOREIGN KEY (`ID_Estacion`) REFERENCES `estacion` (`ID_Estacion`);

--
-- Filtros para la tabla `tramo`
--
ALTER TABLE `tramo`
  ADD CONSTRAINT `tramo_ibfk_1` FOREIGN KEY (`ID_Linea`) REFERENCES `linea` (`ID_Linea`),
  ADD CONSTRAINT `tramo_ibfk_2` FOREIGN KEY (`ID_Estacion_Origen`) REFERENCES `estacion` (`ID_Estacion`),
  ADD CONSTRAINT `tramo_ibfk_3` FOREIGN KEY (`ID_Estacion_Destino`) REFERENCES `estacion` (`ID_Estacion`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`DPI_Empleado`) REFERENCES `empleado` (`DPI_Empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

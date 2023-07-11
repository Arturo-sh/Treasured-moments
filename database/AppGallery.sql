-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-07-2023 a las 08:02:59
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `AppGallery`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albumes`
--

CREATE TABLE `albumes` (
  `id_album` int(11) NOT NULL,
  `nombre_album` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `albumes`
--

INSERT INTO `albumes` (`id_album`, `nombre_album`, `fecha_creacion`) VALUES
(1, 'Default', '2023-06-26 18:08:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id_imagen` int(11) NOT NULL,
  `id_subida` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  `nombre_imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Disparadores `imagenes`
--
DELIMITER $$
CREATE TRIGGER `eliminar_imagen` AFTER DELETE ON `imagenes` FOR EACH ROW INSERT INTO imagenes_eliminadas(id_respaldo, id_imagen, id_subida, id_album) VALUES(null, OLD.id_imagen, OLD.id_subida, OLD.id_album)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_eliminadas`
--

CREATE TABLE `imagenes_eliminadas` (
  `id_respaldo` int(11) NOT NULL,
  `id_imagen` int(11) NOT NULL,
  `id_subida` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  `fecha_eliminacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_acceso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visibilidad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id_mensaje`, `id_usuario`, `mensaje`, `fecha_publicacion`, `visibilidad`) VALUES
(1, 1, 'Hola, bienvenida a nuestra galeria de recuerdos. Antes de que subas alguna imagen quiero que sepas que tal vez este sea uno de mis últimos proyectos en un largo tiempo, por tanto este sistema queda a tu disposicion y realmente espero que tu estadia aquí sea agradable, y solo me queda agradecerte por todo lo bonito que vivimos, realmente esos recuerdos vivirán en mi mente, corazón y alma por el resto de mi vida, siempre vivirás en una parte de mi, gracias por todo Karen ❤️', '2023-07-06 02:32:17', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subidas`
--

CREATE TABLE `subidas` (
  `id_subida` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` text NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL,
  `telefono` text NOT NULL,
  `imagen_perfil` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rol` varchar(100) NOT NULL,
  `passcode` text NOT NULL,
  `owner` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `password`, `salt`, `telefono`, `imagen_perfil`, `fecha_creacion`, `rol`, `passcode`, `owner`) VALUES
(1, 'arturo', '1029a81bf57421aaa3106db2e2469666f09db0a2', 'sgdvasgnf', '924 143 5497', '6278854_64a5a53017c0c.jpg', '2023-07-05 17:15:28', 'admin', 'admin', 1),
(2, 'karen', '7db13ee03942086d0a5efeeecdd50c6d0a808121', 'sgdvasgnf', '', '6278818_649b63fe81b48.png', '2023-06-27 22:36:52', 'usuario', 'karencita', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `albumes`
--
ALTER TABLE `albumes`
  ADD PRIMARY KEY (`id_album`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_subida` (`id_subida`),
  ADD KEY `id_album` (`id_album`);

--
-- Indices de la tabla `imagenes_eliminadas`
--
ALTER TABLE `imagenes_eliminadas`
  ADD PRIMARY KEY (`id_respaldo`),
  ADD KEY `id_album` (`id_album`),
  ADD KEY `id_subida` (`id_subida`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Indices de la tabla `subidas`
--
ALTER TABLE `subidas`
  ADD PRIMARY KEY (`id_subida`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `albumes`
--
ALTER TABLE `albumes`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes_eliminadas`
--
ALTER TABLE `imagenes_eliminadas`
  MODIFY `id_respaldo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `subidas`
--
ALTER TABLE `subidas`
  MODIFY `id_subida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`id_subida`) REFERENCES `subidas` (`id_subida`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `imagenes_ibfk_2` FOREIGN KEY (`id_album`) REFERENCES `albumes` (`id_album`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenes_eliminadas`
--
ALTER TABLE `imagenes_eliminadas`
  ADD CONSTRAINT `imagenes_eliminadas_ibfk_1` FOREIGN KEY (`id_album`) REFERENCES `albumes` (`id_album`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `imagenes_eliminadas_ibfk_2` FOREIGN KEY (`id_subida`) REFERENCES `subidas` (`id_subida`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subidas`
--
ALTER TABLE `subidas`
  ADD CONSTRAINT `subidas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-03-2013 a las 16:19:02
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `innovat1_mensajeria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia`
--

CREATE TABLE IF NOT EXISTS `guia` (
  `idguia` int(11) NOT NULL AUTO_INCREMENT,
  `numero_guia` varchar(45) NOT NULL,
  `orden_servicio_idorden_servicio` int(11) NOT NULL,
  `zona_idzona` int(11) DEFAULT NULL,
  `causal_devolucion_idcausal_devolucion` int(11) DEFAULT NULL,
  `producto_idproducto` int(11) NOT NULL,
  `ciudad_iddestino` int(11) NOT NULL,
  `valor_declarado_guia` int(11) DEFAULT NULL,
  `nombre_destinatario_guia` varchar(45) DEFAULT NULL,
  `direccion_destinatario_guia` varchar(70) DEFAULT NULL,
  `telefono_destinatario_guia` varchar(45) DEFAULT NULL,
  `peso_guia` decimal(10,2) DEFAULT NULL COMMENT 'el peso de la guia en kg',
  `ciudad_idorigen` int(11) NOT NULL,
  `tercero_idremitente` int(11) NOT NULL,
  `tercero_iddestinatario` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'guarda la fecha de creacion de la guia',
  `remitenteInfo` varchar(50) DEFAULT NULL,
  `destinatarioInfo` varchar(50) DEFAULT NULL,
  `owner` int(11) NOT NULL COMMENT 'guarda al tercero que creo la guia',
  `flete` decimal(10,2) DEFAULT NULL COMMENT 'este es el valor del envio',
  `prima` int(11) DEFAULT NULL COMMENT 'es el total del seguro',
  `contenido` varchar(32) DEFAULT NULL,
  `referencia` varchar(30) DEFAULT NULL COMMENT 'es el consecutivo interno del cliente. algunos clientes quieren que salga en la guia',
  `largo` decimal(10,2) DEFAULT NULL,
  `ancho` decimal(10,2) DEFAULT NULL,
  `alto` decimal(10,2) DEFAULT NULL,
  `estado` set('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`idguia`),
  UNIQUE KEY `unq_numero_guia` (`numero_guia`),
  KEY `fk_guia_zona1` (`zona_idzona`),
  KEY `fk_guia_orden_servicio1` (`orden_servicio_idorden_servicio`),
  KEY `fk_guia_causal_devolucion1` (`causal_devolucion_idcausal_devolucion`),
  KEY `fk_guia_producto1` (`producto_idproducto`),
  KEY `fk_guia_ciudad1` (`ciudad_iddestino`),
  KEY `fk_guia_ciudad2` (`ciudad_idorigen`),
  KEY `fk_guia_tercero1` (`tercero_idremitente`),
  KEY `tercero_iddestinatario` (`tercero_iddestinatario`),
  KEY `fk_guia_tercero_creador` (`owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=176 ;

--
-- Volcado de datos para la tabla `guia`
--

INSERT INTO `guia` (`idguia`, `numero_guia`, `orden_servicio_idorden_servicio`, `zona_idzona`, `causal_devolucion_idcausal_devolucion`, `producto_idproducto`, `ciudad_iddestino`, `valor_declarado_guia`, `nombre_destinatario_guia`, `direccion_destinatario_guia`, `telefono_destinatario_guia`, `peso_guia`, `ciudad_idorigen`, `tercero_idremitente`, `tercero_iddestinatario`, `fecha`, `remitenteInfo`, `destinatarioInfo`, `owner`, `flete`, `prima`, `contenido`, `referencia`, `largo`, `ancho`, `alto`, `estado`) VALUES
(1, '19000001', 3, 1, 1, 1, 1002, 0, 'JAIRO ANDRES GALLEGO BOTIA', 'CRA 1 A 9 No 70 - 97 SAN LUIS', '6566555', 0.00, 17, 39, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(3, '19000002', 2, 1, 1, 1, 149, 0, 'JESUS GABRIEL CALLE GUTIERREZ', 'TRANSVERSAL 43 NO. 4 - 52 BARRIO NUEVA PRIMAVERA', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(4, '19000003', 2, 1, 1, 1, 713, 0, 'YOLANDA DEL CARMEN BENAVIDES ENRIQUEZ', 'MZ E CASA 27 VILLA SOL', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(5, '19000004', 2, 1, 1, 1, 149, 0, 'ODRA RUTH VANEGAS ESCAMILLA', 'CLL 11 SUR NO 7-18', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(6, '19000005', 2, 1, 1, 1, 149, 0, 'FREDY CASALLAS PINILLA', 'CLL 140 A # 105 - 11', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo,inactivo'),
(7, '19000006', 2, 1, 1, 1, 149, 0, 'HELBERTH JAVIER VIVAS ZUBIETA', 'CARRERA 100 NO. 16 C - 75 APTO. 202 BARRIO FONTIBON (nueva nomenclatur', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(8, '19000007', 2, 1, 1, 1, 571, 0, 'MARTHA ELENA ESPINOSA CACERES Y', 'CLL 36 SUR # 34 B - 13', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(9, '19000009', 2, 1, 1, 1, 574, 0, 'HUGO CORDOBA MORENO', 'CRA 26A 2 N 76--21', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(10, '19000008', 2, 1, 1, 8, 1, 0, 'ANDRES FELIPE RESTREPO ALVAREZ', 'CALLE 113AA NO 64BB-20 BARRIO LAS BRISAS', '6566555', 0.00, 17, 38, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(21, '10001', 1, 1, 1, 11, 640, 500, 'Abrahans alfonsos Montilla martinezs', 'Calle 7ma con 9na. Antonia Santoss', '7623456', 1.00, 17, 2, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(22, '2121212', 1, 1, 1, 14, 845, 567, 'ewew we', 'wewe', '23232', 23.00, 17, 2, 6, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(23, '1001', 1, 1, 1, 11, 149, 100, 'Carlos Andres Cuervo', 'Calle 83C # 17-41', '3831741', 50.00, 17, 6, 7, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(24, '15002', 1, 1, 1, 8, 3, 234, 'Abrahan alfonso Montilla martinez', 'Calle 7ma con 9na. Antonia Santos', '7623456', 1.00, 17, 2, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(25, '15003', 1, 1, 1, 8, 53, 234, 'Abrahan alfonso Montilla martinez', 'Calle 7ma con 9na. Antonia Santos', '7623456', 1.00, 17, 2, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(26, '15004', 1, 1, 1, 8, 19, 234, 'Abrahan alfonso Montilla martinez', 'Calle 7ma con 9na. Antonia Santos', '7623456', 1.00, 17, 2, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(27, '5000', 1, 1, 1, 13, 1014, 10000, 'LUIS OCTAVIO RINCON', 'AV DE LAS AMERICAS 45-78 BRR CRISTOBAL COLON CALI ', '3716677', 1.00, 1002, 55, 8, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(28, '5001', 1, 1, 1, 9, 1002, 100000, 'JOHN CARLOS  ACOSTA', 'AV MANUELA BELTRAN 45-89', '3716678', 1.00, 1002, 55, 9, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(29, '5003', 1, 1, 1, 9, 1002, 10000, 'JOHN CARLOS  ACOSTA', 'AV MANUELA BELTRAN 45-89', '3716678', 100.00, 1002, 55, 9, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(30, '5002', 1, 1, 1, 11, 979, 10000, 'JOHN CARLOS  ACOSTA', 'AV MANUELA BELTRAN 45-89', '3716678', 100.00, 1002, 55, 9, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(31, '5004', 1, 1, 1, 9, 1002, 10000, 'LUIS OCTAVIO RINCON', 'AV DE LAS AMERICAS 45-78 BRR CRISTOBAL COLON CALICA', '3716677', 100.00, 1002, 55, 8, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(32, '5005', 1, 1, 1, 11, 318, 10000, 'FABIO MEXA ORDONEZS', 'CL 84  74 - 45', '456456456', 100.00, 1002, 55, 10, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(42, '190009010', 6, 1, 1, 1, 602, 0, 'JAIRO ANDRES GALLEGO BOTIA', 'CRA 1 A 9 No 70 - 97 SAN LUIS', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(43, '190009011', 6, 1, 1, 1, 126, 0, 'JESUS GABRIEL CALLE GUTIERREZ', 'TRANSVERSAL 43 NO. 4 - 52 ', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(44, '190009012', 6, 1, 1, 1, 150, 0, 'YOLANDA DEL CARMEN BENAVIDES ENRIQUEZ', 'MZ E CASA 27 VILLA SOL', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(45, '190009013', 6, 1, 1, 7, 1002, 0, 'ODRA RUTH VANEGAS ESCAMILLA', 'CLL 11 SUR NO 7-18', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(46, '190009014', 6, 1, 1, 1, 149, 0, 'FREDY CASALLAS PINILLA', 'CLL 140 A # 105 - 11', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(47, '190009015', 6, 1, 1, 7, 1002, 0, 'HELBERTH JAVIER VIVAS ZUBIETA', 'CARRERA 100 NO. 16 C - 75', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(48, '190009016', 6, 1, 1, 1, 1, 0, 'MARTHA ELENA ESPINOSA CACERES Y', 'CLL 36 SUR # 34 B - 13', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(49, '190009018', 6, 1, 1, 8, 1028, 0, 'ANDRES FELIPE RESTREPO ALVAREZ', 'CALLE 113', '6566555', 0.00, 1002, 54, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(59, '19000020', 7, 1, 1, 7, 1002, 0, 'INTERRAPIDISIMO', 'CRA 1 A 9 No 70 - 97 SAN LUIS', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(60, '19000021', 7, 1, 1, 1, 149, 0, 'CRONOENTREGAS', 'TRANSVERSAL 43 NO. 4 - 52 BARRIO NUEVA PRIMAVERA', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(61, '19000022', 7, 1, 1, 1, 713, 0, 'RED ENTREGAS', 'MZ E CASA 27 VILLA SOL', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(62, '19000023', 7, 1, 1, 1, 149, 0, 'POSTAL EXPRESS', 'CLL 11 SUR NO 7-18', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(63, '19000024', 7, 1, 1, 1, 149, 0, 'LIDONET', 'CLL 140 A # 105 - 11', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(64, '19000025', 7, 1, 1, 1, 149, 0, 'LA RECARGA CALICANTO', 'CARRERA 100 NO. 16 C - 75 APTO. 202 BARRIO FONTIBON (nueva nomenclatur', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(65, '19000026', 7, 1, 1, 1, 571, 0, 'MARTHA ELENA ESPINOSA CACERES Y', 'CLL 36 SUR # 34 B - 13', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(66, '19000027', 7, 1, 1, 1, 574, 0, 'HUGO CORDOBA MORENO', 'CRA 26A 2 N 76--21', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(67, '19000028', 7, 1, 1, 1, 1, 0, 'ANDRES FELIPE RESTREPO ALVAREZ', 'CALLE 113AA NO 64BB-20 BARRIO LAS BRISAS', '6566555', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(77, '19000030', 9, 1, 1, 1, 149, 0, 'jhon acosta', '0', '3457896', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(78, '19000032', 9, 1, 1, 7, 1002, 0, 'jairo puentes', '0', '3315838', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(79, '19000033', 9, 1, 1, 1, 149, 0, 'jhon acosta', '0', '3457896', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(80, '19000034', 9, 1, 1, 1, 1, 0, 'octavio rincon', '0', '1256895', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(81, '19000035', 9, 1, 1, 7, 1002, 0, 'jairo puentes', '', '3315838', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(82, '19000036', 9, 1, 1, 1, 149, 0, 'jhon acosta', 'cl 27  31-94', '3457896', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(83, '19000037', 9, 1, 1, 1, 1, 0, 'octavio rincon', 'cl 45  78-56', '1256895', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(84, '19000038', 9, 1, 1, 7, 1002, 0, 'jairo puentes', 'cr 84  48a-16', '3315838', 0.00, 1002, 55, 1, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(85, '10020', 1, 1, 1, 11, 1, 10000, 'LASTENIA ROJAS DEL PORRAZO', 'CL 24  5-78', '3315838', 35.00, 1002, 2, 11, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(86, '98754', 1, 1, 1, 11, 602, 100, 'Wilson Fernando Andrade', 'Calle 8 # 27-31', '3716677', 100.00, 1002, 51, 12, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(87, '999', 1, 1, 1, 9, 1002, 100, 'police ', '', '123', 23.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(88, '1324234', 1, 1, 1, 11, 685, 45, ' ', '', '', 50.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(89, '789456', 1, 1, 1, 11, 319, 45, 'panchito eche', 'lejos juemadre', '123', 123.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(90, '789457', 1, 1, 1, 11, 2, 34, 'nadie jajaj', 'XD', '7894', 12.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(93, '789468', 1, 1, 1, 11, 685, 12, 'asd asdasd', 'asdasd', '123', 23.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(95, '7894568', 1, 1, 1, 11, 1052, 23, 'asd asdas', 'asdad', '123', 12.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(96, '7894564', 1, 1, 1, 11, 3, 22, 'asdf asdf', 'asdf', '134', 232.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(97, '78945644', 1, 1, 1, 11, 778, 22, ' ', '', '123', 232.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(98, '7894567', 1, 1, 1, 11, 778, 123, 'asdf asdf', 'asdf', '134', 123.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(106, '78945671', 1, 1, 1, 11, 685, 123, 'asdf sadf', 'sadf', '166', 123.00, 1002, 64, 14, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(107, '7894', 1, 1, 1, 11, 685, 34, 'asdf asdf', 'asdf', '134', 34.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(108, '7', 1, 1, 3, 11, 685, 3, 'asdf asdf', 'asdf', '134', 3.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(109, '5', 1, 1, 3, 11, 603, 121, 'asdf asdf', 'asdf', '134', 1.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(110, '8', 1, 1, 2, 11, 2, 12, 'asdf asdf', 'asdf', '134', 0.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(111, '715', 1, 1, 1, 11, 572, 2, 'asdf asdf', 'asdf', '134', 12.00, 1002, 2, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(113, '4789', 1, 1, 1, 11, 3, 12, ' ', '', '', 212.00, 1002, 64, 4, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(115, '456', 1, 1, 1, 11, 3, 22, 'asdf asdf', 'asdf', '134', 11.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(116, '784', 1, 1, 1, 11, 2, 12, 'asdf asdf', 'asdf', '134', 12.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(117, '78946578', 1, 1, 1, 11, 2, 23, 'asdf asdf', 'asdf', '134', 23.00, 1002, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(118, '78946579', 1, 1, 1, 11, 455, 22, 'chehe pacnnnnnnnn', 'go hell', '478', 12.00, 1002, 64, 16, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(143, '11', 1, 1, 1, 1, 9, 22, 'asdf asdf', 'asdf', '134', 12.00, 203, 64, 13, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(144, '12', 1, 1, 1, 25, 2, 123, 'celestino god', 'cielo', '999', 12.00, 2, 2, 15, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(145, '13', 1, 1, 1, 1, 1083, 12, 'celestino god', 'cielo', '999', 12.00, 207, 64, 15, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(146, '14', 1, 1, 1, 11, 125, 23, 'nadie arroyo', 'calle lejossssssss', '123', 12.00, 317, 64, 4, '2013-03-21 19:22:08', 'cede norte valle muhahaha', 'panaderia deli pan pan rico a toda hora y moemento', 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(150, '15', 1, 1, 1, 14, 1083, 0, ' ', '', '', 0.00, 1011, 2, NULL, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(151, '6667443', 1, 1, 1, 11, 3, 11, 'celestino god', 'cielo', '999', 12.00, 962, 64, 15, '2013-03-21 19:22:08', NULL, NULL, 1, NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'activo'),
(164, '987987', 17, NULL, 1, 14, 604, NULL, NULL, NULL, NULL, NULL, 1, 64, NULL, '2013-03-26 14:54:10', 'muhahaha', NULL, 51, 0.00, 0, NULL, '', NULL, NULL, NULL, 'activo'),
(165, '789789', 17, NULL, 1, 11, 151, 5000, 'santa  claus', 'polo norte', '5555', 50.00, 1, 64, 17, '2013-03-26 16:24:03', 'jejejeje', 'papa noel', 51, 0.00, 0, 'dulces :D', 'PL89', 1.00, 1.00, 1.00, 'activo'),
(168, '987654', 10, NULL, 1, 21, 572, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 594, 2, 13, '2013-03-26 17:18:11', 'correito', 'va pa ese webon', 51, 0.00, 0, 'carta', 'cc66', 2.00, 1.00, 4.00, 'activo'),
(170, '', 18, NULL, 1, 11, 603, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 1, 5, 13, '2013-03-26 22:00:45', '', 'qewewqeqwewq', 5, 0.00, 0, 'nada', 'cde3r4', 1.00, 1.00, 1.00, 'activo'),
(172, 'CC1', 18, NULL, 1, 11, 455, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 1, 5, 13, '2013-03-26 22:22:18', '', 'en el cerrito', 5, 0.00, 0, 'pikachu', 'fr456as34', 1.00, 1.00, 1.00, 'activo'),
(173, 'CC000012', 18, NULL, 1, 11, 455, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 1, 5, 13, '2013-03-26 22:25:56', '', 'en el cerrito', 5, 0.00, 0, 'pikachu', 'fr456as34', 1.00, 1.00, 1.00, 'activo'),
(174, 'CC0000013', 18, NULL, 1, 1, 844, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 417, 5, 13, '2013-03-27 12:56:31', '', '', 5, 0.00, 0, '', '', 1.00, 1.00, 1.00, 'activo'),
(175, 'CC0000014', 18, NULL, 1, 22, 1083, 5000, 'uno dos  tres cuatro', 'valle de lily', '320407', 50.00, 169, 5, 13, '2013-03-27 13:15:59', 'a yo', 'el feo', 5, 0.00, 0, 'ancla', 'ads322', 1.00, 1.00, 1.00, 'activo');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `guia`
--
ALTER TABLE `guia`
  ADD CONSTRAINT `fk_guia_causal_devolucion1` FOREIGN KEY (`causal_devolucion_idcausal_devolucion`) REFERENCES `causal_devolucion` (`idcausal_devolucion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_ciudad1` FOREIGN KEY (`ciudad_iddestino`) REFERENCES `ciudad` (`idciudad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_ciudad2` FOREIGN KEY (`ciudad_idorigen`) REFERENCES `ciudad` (`idciudad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_orden_servicio1` FOREIGN KEY (`orden_servicio_idorden_servicio`) REFERENCES `orden_servicio` (`idorden_servicio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_producto1` FOREIGN KEY (`producto_idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_tercero_creador` FOREIGN KEY (`owner`) REFERENCES `tercero` (`idtercero`),
  ADD CONSTRAINT `fk_guia_zona1` FOREIGN KEY (`zona_idzona`) REFERENCES `zona` (`idzona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `guia_ibfk_1` FOREIGN KEY (`tercero_idremitente`) REFERENCES `tercero` (`idtercero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `guia_ibfk_2` FOREIGN KEY (`tercero_iddestinatario`) REFERENCES `destinatario` (`iddestinatario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

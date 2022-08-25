-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-08-2022 a las 01:53:27
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `stock_center`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bills_list`
--

CREATE TABLE `bills_list` (
  `billDate` tinytext NOT NULL,
  `billNumber` smallint(5) UNSIGNED NOT NULL,
  `billType` tinytext NOT NULL,
  `billProducts` longtext NOT NULL,
  `billMadeBy` tinytext NOT NULL,
  `billClient` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `cliName` tinytext NOT NULL,
  `cliID` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `UNAME` tinytext NOT NULL,
  `ACTIONDATE` tinytext NOT NULL,
  `ACTIONINFO` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_list`
--

CREATE TABLE `stock_list` (
  `PIC` text NOT NULL,
  `PNAME` tinytext NOT NULL,
  `PCODE` tinytext NOT NULL,
  `PUNITS` smallint(5) UNSIGNED NOT NULL,
  `PRICE` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `NAME` tinytext NOT NULL,
  `PASSWORD` text NOT NULL,
  `ACCESSTYPE` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bills_list`
--
ALTER TABLE `bills_list`
  ADD PRIMARY KEY (`billNumber`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bills_list`
--
ALTER TABLE `bills_list`
  MODIFY `billNumber` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

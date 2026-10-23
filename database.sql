-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 04:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblioteca`
--

-- --------------------------------------------------------

--
-- Table structure for table `autores`
--

CREATE TABLE `autores` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Edad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bibliotecarios`
--

CREATE TABLE `bibliotecarios` (
  `Id_Usuario` int(11) NOT NULL,
  `Num_Seguridad_Social` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `Id_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devoluciones`
--

CREATE TABLE `devoluciones` (
  `Id` int(11) NOT NULL,
  `Id_Prestamo` int(11) NOT NULL,
  `Fecha_Devolucion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `editoriales`
--

CREATE TABLE `editoriales` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Pais` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ejemplares`
--

CREATE TABLE `ejemplares` (
  `Id_Ejemplar` int(11) NOT NULL,
  `ISBN_Libro` varchar(20) NOT NULL,
  `Estado` enum('Disponible','Prestado','Dado de baja') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generos`
--

CREATE TABLE `generos` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gestion_inventario`
--

CREATE TABLE `gestion_inventario` (
  `Id` int(11) NOT NULL,
  `Id_Bibliotecario` int(11) NOT NULL,
  `Id_Ejemplar` int(11) NOT NULL,
  `Accion` enum('Alta','Baja por desgaste','Baja por pérdida') NOT NULL,
  `Fecha_Accion` date NOT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `libros`
--

CREATE TABLE `libros` (
  `ISBN` varchar(20) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Sinopsis` text DEFAULT NULL,
  `Id_Autor` int(11) DEFAULT NULL,
  `Id_Editorial` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `libros_generos`
--

CREATE TABLE `libros_generos` (
  `ISBN_Libro` varchar(20) NOT NULL,
  `Id_Genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestamos`
--

CREATE TABLE `prestamos` (
  `Id` int(11) NOT NULL,
  `Id_Cliente` int(11) NOT NULL,
  `Id_Ejemplar` int(11) NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Fin` date NOT NULL,
  `Dias_Retraso` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Nombre_Usuario` varchar(50) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Foto` text DEFAULT NULL,
  `Moroso` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `bibliotecarios`
--
ALTER TABLE `bibliotecarios`
  ADD PRIMARY KEY (`Id_Usuario`),
  ADD UNIQUE KEY `Num_Seguridad_Social` (`Num_Seguridad_Social`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`Id_Usuario`);

--
-- Indexes for table `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Id_Prestamo` (`Id_Prestamo`);

--
-- Indexes for table `editoriales`
--
ALTER TABLE `editoriales`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD PRIMARY KEY (`Id_Ejemplar`),
  ADD KEY `ISBN_Libro` (`ISBN_Libro`);

--
-- Indexes for table `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Nombre` (`Nombre`);

--
-- Indexes for table `gestion_inventario`
--
ALTER TABLE `gestion_inventario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Bibliotecario` (`Id_Bibliotecario`),
  ADD KEY `Id_Ejemplar` (`Id_Ejemplar`);

--
-- Indexes for table `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `Id_Autor` (`Id_Autor`),
  ADD KEY `Id_Editorial` (`Id_Editorial`);

--
-- Indexes for table `libros_generos`
--
ALTER TABLE `libros_generos`
  ADD PRIMARY KEY (`ISBN_Libro`,`Id_Genero`),
  ADD KEY `Id_Genero` (`Id_Genero`);

--
-- Indexes for table `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Cliente` (`Id_Cliente`),
  ADD KEY `Id_Ejemplar` (`Id_Ejemplar`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Nombre_Usuario` (`Nombre_Usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autores`
--
ALTER TABLE `autores`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `editoriales`
--
ALTER TABLE `editoriales`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ejemplares`
--
ALTER TABLE `ejemplares`
  MODIFY `Id_Ejemplar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `generos`
--
ALTER TABLE `generos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gestion_inventario`
--
ALTER TABLE `gestion_inventario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bibliotecarios`
--
ALTER TABLE `bibliotecarios`
  ADD CONSTRAINT `bibliotecarios_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`Id_Prestamo`) REFERENCES `prestamos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD CONSTRAINT `ejemplares_ibfk_1` FOREIGN KEY (`ISBN_Libro`) REFERENCES `libros` (`ISBN`) ON DELETE CASCADE;

--
-- Constraints for table `gestion_inventario`
--
ALTER TABLE `gestion_inventario`
  ADD CONSTRAINT `gestion_inventario_ibfk_1` FOREIGN KEY (`Id_Bibliotecario`) REFERENCES `bibliotecarios` (`Id_Usuario`),
  ADD CONSTRAINT `gestion_inventario_ibfk_2` FOREIGN KEY (`Id_Ejemplar`) REFERENCES `ejemplares` (`Id_Ejemplar`) ON DELETE CASCADE;

--
-- Constraints for table `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`Id_Autor`) REFERENCES `autores` (`Id`) ON DELETE SET NULL,
  ADD CONSTRAINT `libros_ibfk_2` FOREIGN KEY (`Id_Editorial`) REFERENCES `editoriales` (`Id`) ON DELETE SET NULL;

--
-- Constraints for table `libros_generos`
--
ALTER TABLE `libros_generos`
  ADD CONSTRAINT `libros_generos_ibfk_1` FOREIGN KEY (`ISBN_Libro`) REFERENCES `libros` (`ISBN`) ON DELETE CASCADE,
  ADD CONSTRAINT `libros_generos_ibfk_2` FOREIGN KEY (`Id_Genero`) REFERENCES `generos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`Id_Cliente`) REFERENCES `clientes` (`Id_Usuario`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`Id_Ejemplar`) REFERENCES `ejemplares` (`Id_Ejemplar`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

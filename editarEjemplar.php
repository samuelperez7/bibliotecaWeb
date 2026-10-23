<?php

require_once "conexion/conexionBibliotecario.php";

$idEjemplar = $_GET["id"] ?? null;

if ($idEjemplar) {
    try {
        $conexion = conectarBibliotecario();

        $sql = "SELECT Id_Ejemplar, ISBN_Libro, Estado FROM ejemplares WHERE Id_Ejemplar=$idEjemplar";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        $ejemplar = $stmt->fetch();

        if (empty($ejemplar)) {
            $error = "No se ha encontrado ningún ejemplar con ese ID.";
        }
    } catch (PDOException $e) {
        $error = "Error SQL: " . $e->getMessage();
    }
} else {
    $error = "No se ha especificado qué ejemplar quieres editar.";
};





?>
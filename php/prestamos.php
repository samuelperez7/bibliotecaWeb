<?php

require_once 'conexion/conexionCliente.php';

$ejemplares = [];
$error = null;

try{
    $conexion = conectarCliente();

    $consultaSQL = "SELECT Id_Ejemplar, ISBN_Libro, Estado FROM ejemplares";

    $statement = $conexion->query($consultaSQL);

    $ejemplares = $statement->fetchAll();
}catch (PDOException $e){
    $error = "Error en la base de datos: ".$e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Mostrar ejemplares</title>
    </head>
    <body>
        <h1>Ejemplares disponibles</h1>

        <?php if($error): ?>
            <p class="error"><?= htmlspecialchars($error)?></p>
        <?php elseif(empty($ejemplares)): ?>
            <p> No hay ejemplares disponibles </p>
        <?php else: ?>
            <!--Creamos una tabla para mostrar los ejmplares-->
            <table>
                <thead>
                    <tr>
                        <th>ID Ejemplar</th>
                        <th>ISBN libro</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ejemplares as $ejemplar): ?>
                        <tr>
                            <th><?= htmlspecialchars($ejemplar['Id_Ejemplar']) ?></td>
                            <th><a href="libro.php?id=<?= urlencode($ejemplar['ISBN_Libro']) ?>">
                    Ver datos del Libro #<?= htmlspecialchars($ejemplar['ISBN_Libro']) ?>
                </a></td>
                            <th><?= htmlspecialchars($ejemplar['Estado']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
        <?php endif; ?>
    </body>
</html>
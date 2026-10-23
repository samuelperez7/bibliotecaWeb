<?php
// Iniciamos sesion y requerimos la conexión de bibliotecario (permisos totales)
session_start();
require_once "conexion/conexionBibliotecario.php";

// Extremos el ID del ejemplar a editar
$idEjemplar = $_GET["id"] ?? null;

$error = null;

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
}
;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar ejemplar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .tarjeta {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 400px;
            border-radius: 8px;
            margin-top: 20px;
            background: #fdfdfd;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .debug-sql {
            background: #1e1e1e;
            color: #5ce6cd;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 14px;
        }

        .contador {
            font-size: 1.1em;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>

<body>
    <h1>Editar el ejemplar</h1>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($ejemplar)): ?>

        <!-- Recorremos el array con un bucle foreach -->
        <div class="tarjeta">
            <p><strong>ID:</strong> <?= htmlspecialchars($ejemplar['Id_Ejemplar']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($ejemplar['ISBN_Libro']) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($ejemplar['Estado']) ?></p>
        </div>


    <?php endif; ?>

    <br>
    <a href="gestionEjemplares.php" class="volver">← Volver al catálogo</a>

</body>

</html>
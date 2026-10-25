<?php
// Iniciamos sesion y requerimos la conexión de bibliotecario (permisos totales)
session_start();
require_once "conexion/conexionBibliotecario.php";

$error = null;
$conexion = conectarBibliotecario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos el ID oculto y el estado seleccionado en el <select>
    $idEjemplar = $_POST['id_ejemplar'] ?? '';
    $nuevoEstado = $_POST['estado'] ?? '';

    if (!empty($idEjemplar) && !empty($nuevoEstado)) {
        try {
            $sql = "UPDATE ejemplares SET Estado = :estado WHERE Id_Ejemplar = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                'estado' => $nuevoEstado,
                'id' => $idEjemplar
            ]);

            // Redirigimos de vuelta a la gestión con mensaje de éxito
            header('Location: gestionEjemplares.php?mensaje=Ejemplar actualizado correctamente');
            exit();

        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error = "Faltan datos obligatorios.";
    }
}


$idRecibido = $_GET['id'] ?? null;
$ejemplar = null;

if ($idRecibido) {
    $stmt = $conexion->prepare("SELECT * FROM ejemplares WHERE Id_Ejemplar = :id");
    $stmt->execute(['id' => $idRecibido]);
    $ejemplar = $stmt->fetch();
}
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
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="tarjeta">
        <?php if ($ejemplar): ?>
            <form action="editarEjemplar.php" method="POST">

                <!-- Campo oculto para llevar el ID del ejemplar que estamos editando -->
                <input type="hidden" name="id_ejemplar" value="<?= htmlspecialchars($ejemplar['Id_Ejemplar']) ?>">

                <label>Estado actual: <strong><?= htmlspecialchars($ejemplar['Estado']) ?></strong></label><br><br>

                <label for="estado">Nuevo Estado:</label><br>
                <select id="estado" name="estado">
                    <option value="Disponible" <?= ($ejemplar['Estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible
                    </option>
                    <option value="Prestado" <?= ($ejemplar['Estado'] == 'Prestado') ? 'selected' : '' ?>>Prestado</option>
                    <option value="Dado de baja" <?= ($ejemplar['Estado'] == 'Dado de baja') ? 'selected' : '' ?>>Dado de baja
                    </option>
                </select><br><br>

                <button type="submit">Actualizar Cambios</button>
            </form>
        <?php else: ?>
            <p>No se ha especificado un ejemplar válido para editar.</p>
        <?php endif; ?>
    </div>

    <br>
    <a href="gestionEjemplares.php" class="volver">← Volver al panel</a>
</body>

</html>
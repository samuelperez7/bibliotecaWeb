<?php
// Iniciamos sesion y requerimos la conexión de bibliotecario (permisos totales)
session_start();
require_once 'conexion/conexionBibliotecario.php';

$error = null;
$mensaje = null;

try {
    $conexion = conectarBibliotecario();

    // Borrar ejemplar
    if (isset($_GET['borrar'])) {
        $idBorrar = $_GET['borrar'];
        $sqlDelete = "DELETE FROM ejemplares WHERE Id_Ejemplar = :id";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->execute(['id' => $idBorrar]);
        header('Location: gestionEjemplares.php?mensaje=Ejemplar borrado con éxito');
        exit();
    }

    // Añadir ejemplar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
        $isbn = $_POST['isbn'] ?? '';
        $estado = $_POST['estado'] ?? 'Disponible';

        if (!empty($isbn)) {
            $sqlInsert = "INSERT INTO ejemplares (ISBN_Libro, Estado) VALUES (:isbn, :estado)";
            $stmtInsert = $conexion->prepare($sqlInsert);
            $stmtInsert->execute(['isbn' => $isbn, 'estado' => $estado]);
            header('Location: gestionEjemplares.php?mensaje=Ejemplar añadido con éxito');
            exit();
        } else {
            $error = "El campo ISBN es obligatorio.";
        }
    }

    // Obtener todos los ejemplares
    $sqlSelect = "SELECT Id_Ejemplar, ISBN_Libro, Estado FROM ejemplares";
    $stmtSelect = $conexion->query($sqlSelect);
    $ejemplares = $stmtSelect->fetchAll();

} catch (PDOException $e) {
    $error = "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Gestión de Ejemplares</title>
</head>
<body>

    <h1>Panel de Bibliotecario - Gestión de Ejemplares</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <p class="exito"><?= htmlspecialchars($_GET['mensaje']) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Formulario para AÑADIR un nuevo ejemplar -->
    <div class="card" style="width: 300px;">
        <div class="card-overlay"></div>
        <div class="card-inner">
        <h3>Añadir Nuevo Ejemplar</h3>
        <form action="gestionEjemplares.php" method="POST">
            <input type="hidden" name="accion" value="añadir">
            <label for="isbn">ISBN del Libro:</label><br>
            <input type="text" id="isbn" name="isbn" required><br><br>
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="Disponible">Disponible</option>
                <option value="Prestado">Prestado</option>
                <option value="Dado de baja">Dado de baja</option>
            </select><br><br>
            <button type="submit">Guardar Ejemplar</button>
        </form>
        </div>
    </div>
    <hr>

    <!-- Tabla para LISTAR, BORRAR y EDITAR -->
    <h2>Ejemplares Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID_Ejemplar</th>
                <th>ISBN_Libro</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ejemplares)): ?>
                <?php foreach ($ejemplares as $ejemplar): ?>
                    <tr>
                        <td><?= htmlspecialchars($ejemplar['Id_Ejemplar']) ?></td>
                        <td><?= htmlspecialchars($ejemplar['ISBN_Libro']) ?></td>
                        <td><?= htmlspecialchars($ejemplar['Estado']) ?></td>
                        <td>
                            <!-- Enlace para Borrar -->
                            <a href="gestionEjemplares.php?borrar=<?= $ejemplar['Id_Ejemplar'] ?>" onclick="return confirm('¿Estás seguro de que quieres borrar este ejemplar?');" style="color: red; text-decoration: none; margin-right: 10px;">Borrar</a>
                            
                            <!-- Enlace para ir a una página de edición -->
                            <a href="editarEjemplar.php?id=<?= $ejemplar['Id_Ejemplar'] ?>" style="color: blue; text-decoration: none;">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay ejemplares registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
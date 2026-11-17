<?php
// Iniciamos sesion y requerimos la conexión de bibliotecario (permisos totales)
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION["rol"] !== "bibliotecario") {
    header('Location: ../../index.php');
    exit();
}

require_once "../conexionDB/conexion.php";

$error = null;
$mensaje = null;

try {
    $conexion = conectarBibliotecario();

    // Consultas
        // Autores
        $autores = [];
        $autores = $conexion->query("SELECT Id, Nombre, Apellidos FROM autores")->fetchAll();

        // Editoriales
        $editoriales = [];
        $editoriales = $conexion->query("SELECT Id, Nombre FROM editoriales")->fetchAll();

    // Añadir ejemplar
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isbn = $_POST['isbn'] ?? [];
        $titulo = $_POST['titulo'] ?? [];
        $sinopsis = $_POST['sinopsis'] ?? [];
        $portada = $_POST['portada'] ?? [];
        $autor = $_POST['autor'] ?? [];
        $editorial = $_POST['editorial'] ?? [];


        // Inserciones
        for ($i; $i < 3; $i++) {
            $sqlInsert = "INSERT INTO libros VALUES (:isbn, :titulo, :sinopsis, :id_autor, :id_editorial, :ruta_portada)";
            $stmtInsert = $conexion->prepare($sqlInsert);
            $stmtInsert->execute(['isbn' => $isbn[$i], 'titulo' => $titulo[$i], 'sinopsis' => $sinopsis[$i], 'id_autor' => $id_autor[$i], 'id_editorial' => $id_editorial[$i], 'ruta_portada' => $ruta_portada[$i]]);
            header('Location: altaLibros.php?mensaje=Libro añadido con éxito');
            exit();
        }
    }

} catch (PDOException $e) {
    $error = "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Gestión de Libros</title>
</head>

<body>
    <header>
        <p>Pulse <a href="../logout.php">aquí</a> para salir</p>
    </header>

    <h1>Panel de Bibliotecario - Alta de Libros</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <p class="exito"><?= htmlspecialchars($_GET['mensaje']) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Formularios para AÑADIR un nuevo libro -->
    <form action="altaLibros.php" method="POST">
        <?php for ($i = 0; $i < 3; $i++): ?>
            <h3>Libro <?= $i + 1 ?>:</h3>
            <label for="isbn">ISBN: </label>
            <input type="text" name="isbn<?= $i ?>" required><br><br>
            <label for="titulo">Titulo: </label>
            <input type="text" name="titulo<?= $i ?>" required><br><br>
            <label for="sinopsis">Sinopsis: </label>
            <input type="text" name="sinopsis<?= $i ?>" required><br><br>
            <label for="portada">Portada (ruta): </label>
            <input type="text" name="portada<?= $i ?>" placeholder="portadaLibros/imagen.jpg" required><br><br>
            <label for="autor">Autor: </label>
            <select name="autor<?= $i ?>">
                <option>Selecciona un autor</option>
                <?php foreach ($autores as $autor): ?>
                    <option value="<?= (int)$autor['Id'] ?>"><?= htmlspecialchars($autor['Nombre'] . ' ' . $autor['Apellidos']) ?></option>
                <?php endforeach ?>
            </select><br><br>
            <label for="editorial">Editorial: </label>
            <select name="editorial<?= $i ?>">
                <option>Selecciona una editorial</option>
                <?php foreach ($editoriales as $editorial): ?>
                    <option value="<?= (int)$editorial['Id'] ?>"><?= htmlspecialchars($editorial['Nombre']) ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <hr>
        <?php endfor ?>
        <button type="submit">Añadir libros</button>
    </form>
    <hr>
</body>

</html>
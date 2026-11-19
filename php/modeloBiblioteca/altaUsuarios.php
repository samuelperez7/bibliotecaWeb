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

    // Añadir usuario/s
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $caso = $_GET['caso'] ?? 1;
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $permisos = $_POST['permisos'] ?? 2;
        $moroso = $_POST['moroso'] ?? 0;

        $conexion->beginTransaction();

        switch ($caso) {
            case 1:
                // Insertamos en usuarios
                $sqlInsert = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :moroso)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'usuario' => $usuario, 'password' => $password, 'moroso' => $moroso]);
                $idUsuario = $conexion->lastInsertId();
                // Consulta del ID
                if ((Int) $permisos === 1) { // Insercion de bibliotecario
                    $sqlInsert = "INSERT INTO bibliotecarios VALUES (:idUsuario, :numSS)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $idUsuario, 'numSS' => "ES" . rand(1111111111, 9999999999)]);
                } else if ((Int) $permisos === 2) { // Insercion de cliente
                    $sqlInsert = "INSERT INTO clientes VALUES (:idUsuario)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $idUsuario]);
                }
                break;
            case 2:
                if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                    header('Location: altaUsuarios.php?error=Error al recibir el archivo (código: ' . ($_FILES['foto']['error'] ?? -1) . ').');
                    exit();
                }
                $archivo = $_FILES['foto'];
                $maxTamano = 2 * 1024 * 1024;
                if ($archivo['size'] > $maxTamano) {
                    header('Location: altaUsuarios.php?error=El archivo supera el tamaño máximo permitido de 2 MB.');
                    exit();
                }
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeReal = $finfo->file($archivo['tmp_name']);

                $tiposPermitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                ];

                if (!array_key_exists($mimeReal, $tiposPermitidos)) {
                    header('Location: altaUsuarios.php?error=Formato no permitido. Solo se aceptan JPG, PNG o WEBP.');
                    exit();
                }

                // Comprobar adicionalmente que es una imagen válida
                if (getimagesize($archivo['tmp_name']) === false) {
                    header('Location: altaUsuarios.php?error=El archivo no es una imagen válida.');
                    exit();
                }

                $carpetaDestino = __DIR__ . '../../media/fotoUsuarios';

                // Generar un nombre único para evitar sobrescrituras y nombres peligrosos
                $nombreSeguro = bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$mimeReal];
                $rutaFinal = $carpetaDestino . '/' . $nombreSeguro;

                if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
                    header('Location: altaUsuarios.php?error=Error al guardar la imagen en el servidor.');
                    exit();
                }

                $mensaje = 'Imagen subida correctamente como: ' . (String) $nombreSeguro;

                // Insertamos en usuarios
                $sqlInsert = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :moroso)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'usuario' => $usuario, 'password' => $password, 'moroso' => $moroso]);
                $idUsuario = $conexion->lastInsertId();
                // Consulta del ID
                if ((Int) $permisos === 1) { // Insercion de bibliotecario
                    $sqlInsert = "INSERT INTO bibliotecarios VALUES (:idUsuario, :numSS)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $idUsuario, 'numSS' => "ES" . rand(1111111111, 9999999999)]);
                } else if ((Int) $permisos === 2) { // Insercion de cliente
                    $sqlInsert = "INSERT INTO clientes VALUES (:idUsuario)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $idUsuario]);
                }
                break;
            case 3:
                # code...
                break;

            case 4:
                # code...
                break;
        }
        // Inserciones
        $conexion->commit();
        // header('Location: altaUsuarios.php?mensaje=Usuario/s añadido/s con éxito');
        // exit();
    }

} catch (PDOException $e) {
    if ($conexion instanceof PDO && $conexion->inTransaction()) {
        $conexion->rollBack();
    }
    $error = "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios - Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 40px auto;
            padding: 0 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-weight: bold;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        input,
        select,
        button {
            padding: 8px;
            font-size: 14px;
        }

        button {
            background-color: #0056b3;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #004494;
        }

        .mensaje {
            color: greenyellow;
            padding: 10px;
            background-color: #eef;
            border-left: 4px solid #0056b3;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <h1>Panel de Bibliotecario - Alta de Usuarios</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <p class="exito"><?= htmlspecialchars($_GET['mensaje']) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- IMPORTANTE: enctype="multipart/form-data" es obligatorio para poder enviar archivos ($_FILES) -->
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">

        <label>
            Nombre:
            <input type="text" name="nombre" placeholder="Ej. Laura">
        </label>

        <label>
            Apellidos:
            <input type="text" name="apellidos" placeholder="Ej. Martínez Gómez">
        </label>

        <label>
            Nombre de usuario:
            <input type="text" name="usuario" placeholder="Ej. lmartinez">
        </label>

        <label>
            Contraseña:
            <input type="password" name="password">
        </label>

        <label>
            Permisos:
            <select name="permisos">
                <option value="1">Bibliotecario</option>
                <option value="2">Cliente</option>
            </select>
        </label>

        <label>
            Foto de perfil:
            <input type="file" name="foto" accept="image/*">
        </label>

        <!-- Campo Moroso inicializado a false (0) -->
        <input type="hidden" name="moroso" value="0">

        <br>
        <br>

        <button type="submit">Guardar usuario(s)</button>
    </form>

</body>

</html>
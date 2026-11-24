<?php

// Samuel Pérez Ruiz

// Inicio sesion y pido la conexión de bibliotecario (permisos totales)
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION["rol"] !== "bibliotecario") {
    header('Location: ../../index.php');
    exit();
}

require_once "../conexionDB/conexion.php";

$error = null;
$mensaje = null;

// Una vez enviado el formulario:
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recojo los datos (de un solo libro) del formulario
    $caso = (int) $_POST["caso"] ?? 1;
    $nombre = $_POST["nombre"] ?? "";
    $apellidos = $_POST["apellidos"] ?? "";
    $usuario = $_POST["usuario"] ?? "";
    $password = $_POST["password"] ?? "";
    $rol = $_POST['rol'] ?? "cliente";
    $moroso = false;

    try {
        $conexion = conectarBibliotecario();

        switch ($caso) {
            case 1:
                $conexion->beginTransaction();

                $sqlInsert = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :moroso)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'usuario' => $usuario, 'password' => password_hash($password, PASSWORD_BCRYPT), 'moroso' => $moroso]);
                if ((int) $rol === 2) {
                    $sqlInsert = "INSERT INTO clientes VALUES (:idUsuario)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId()]);
                } else if ((int) $rol === 1) {
                    $sqlInsert = "INSERT INTO bibliotecarios VALUES (:idUsuario, :nuss)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId(), 'nuss' => "ES" . (String) rand(1111111111, 9999999999)]);
                }

                $conexion->commit();

                header('Location: creacionUsuarios_2.php?caso=1&mensaje=Usuario creado con éxito');
                exit();

            case 2:
                $foto = $_FILES["foto"] ?? null;

                // Error en el archivo (foto obligatoria)
                if ($foto['error'] !== UPLOAD_ERR_OK) {
                    die('Error al subir el archivo. Código: ' . $foto['error']);
                }

                // Tamaño maximo
                $maxSize = 2 * 1024 * 1024; // 2 MB
                if ($foto['size'] > $maxSize) {
                    die('El archivo supera el tamaño máximo permitido (2 MB).');
                }

                // Manejo de la extension
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($foto['tmp_name']);

                $permitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                ];

                if (!array_key_exists($mimeType, $permitidos)) {
                    die('Formato de imagen no permitido. Solo JPG, PNG o WEBP.');
                }

                // Carpeta de destino y nombre unico
                $carpetaDestino = __DIR__ . '/../../media/fotosPerfil/';

                $nombreArchivo = uniqid('foto_', true) . '.' . $permitidos[$mimeType];
                $rutaFisica = $carpetaDestino . (string) $nombreArchivo;
                $rutaRelativa = '../../media/fotosPerfil/' . (string) $nombreArchivo;

                // Mover el archivo temporal con move_uploaded_file()
                if (!move_uploaded_file($foto['tmp_name'], $rutaRelativa)) {
                    die('No se pudo mover el archivo a la carpeta de destino.');
                }

                // Inserciones
                $conexion->beginTransaction();

                $sqlInsert = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Foto, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :foto, :moroso)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'usuario' => $usuario, 'password' => password_hash($password, PASSWORD_BCRYPT), 'foto' => $rutaRelativa, 'moroso' => $moroso]);
                if ((int) $rol === 2) {
                    $sqlInsert = "INSERT INTO clientes VALUES (:idUsuario)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId()]);
                } else if ((int) $rol === 1) {
                    $sqlInsert = "INSERT INTO bibliotecarios VALUES (:idUsuario, :nuss)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId(), 'nuss' => "ES" . (String) rand(1111111111, 9999999999)]);
                }

                $conexion->commit();

                header('Location: creacionUsuarios_2.php?caso=2&mensaje=Usuario creado con éxito');
                exit();


            case 3:
                $usuariosData = [];

                // Recojo y valido los datos y archivos de los 3 usuarios en un array
                for ($i = 0; $i < 3; $i++) {
                    $foto = $_FILES["foto" . $i] ?? null;
                    $nombre = $_POST["nombre" . $i] ?? "";
                    $apellidos = $_POST["apellidos" . $i] ?? "";
                    $usuario = $_POST["usuario" . $i] ?? "";
                    $password = $_POST["password" . $i] ?? "";
                    $rol = $_POST["rol" . $i] ?? "2";
                    $rutaRelativa = null;

                    // Solo valido si se envio un archivo (puede no tener foto)
                    if ($foto && $foto['error'] !== UPLOAD_ERR_NO_FILE) {
                        if ($foto['error'] !== UPLOAD_ERR_OK) {
                            die('Error al subir el archivo. Código: ' . $foto['error']);
                        }

                        $maxSize = 2 * 1024 * 1024; // 2 MB
                        if ($foto['size'] > $maxSize) {
                            die('El archivo supera el tamaño máximo permitido (2 MB).');
                        }

                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->file($foto['tmp_name']);

                        $permitidos = [
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/webp' => 'webp',
                        ];

                        if (!array_key_exists($mimeType, $permitidos)) {
                            die('Formato de imagen no permitido. Solo JPG, PNG o WEBP.');
                        }

                        $carpetaDestino = __DIR__ . '/../../media/fotosPerfil/';
                        if (!is_dir($carpetaDestino) && !mkdir($carpetaDestino, 0775, true)) {
                            die('No se pudo crear la carpeta de destino.');
                        }

                        $nombreArchivo = uniqid('foto_', true) . '.' . $permitidos[$mimeType];
                        $rutaFisica = $carpetaDestino . $nombreArchivo;
                        $rutaRelativa = '../../media/fotosPerfil/' . $nombreArchivo;

                        if (!move_uploaded_file($foto['tmp_name'], $rutaFisica)) {
                            die('No se pudo mover el archivo a la carpeta de destino.');
                        }
                    }

                    $usuariosData[] = [
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'usuario' => $usuario,
                        'password' => password_hash($password, PASSWORD_BCRYPT),
                        'foto' => $rutaRelativa,
                        'moroso' => $moroso,
                        'rol' => (int) $rol,
                    ];
                }

                // Inicio transaccion y preparo los bindparam
                $conexion->beginTransaction();

                $sqlUsuario = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Foto, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :foto, :moroso)";
                $stmtUsuario = $conexion->prepare($sqlUsuario);

                $pNombre = null;
                $pApellidos = null;
                $pUsuario = null;
                $pPassword = null;
                $pFoto = null;
                $pMoroso = false;

                $stmtUsuario->bindParam(':nombre', $pNombre);
                $stmtUsuario->bindParam(':apellidos', $pApellidos);
                $stmtUsuario->bindParam(':usuario', $pUsuario);
                $stmtUsuario->bindParam(':password', $pPassword);
                $stmtUsuario->bindParam(':foto', $pFoto);
                $stmtUsuario->bindParam(':moroso', $pMoroso, PDO::PARAM_BOOL);

                $stmtCliente = $conexion->prepare("INSERT INTO clientes VALUES (:idUsuario)");
                $stmtBibliotecario = $conexion->prepare("INSERT INTO bibliotecarios VALUES (:idUsuario, :nuss)");

                $pIdUsuario = null;
                $pNuss = null;

                $stmtCliente->bindParam(':idUsuario', $pIdUsuario);
                $stmtBibliotecario->bindParam(':idUsuario', $pIdUsuario);
                $stmtBibliotecario->bindParam(':nuss', $pNuss);

                // Ejecuciones (inserciones)
                foreach ($usuariosData as $u) {
                    $pNombre = $u['nombre'];
                    $pApellidos = $u['apellidos'];
                    $pUsuario = $u['usuario'];
                    $pPassword = $u['password'];
                    $pFoto = $u['foto'];
                    $pMoroso = $u['moroso'];

                    $stmtUsuario->execute();
                    $pIdUsuario = $conexion->lastInsertId();

                    if ($u['rol'] === 2) {
                        $stmtCliente->execute();
                    } elseif ($u['rol'] === 1) {
                        $pNuss = "ES" . (string) rand(1111111111, 9999999999);
                        $stmtBibliotecario->execute();
                    }
                }

                $conexion->commit();

                header('Location: creacionUsuarios_2.php?caso=3&mensaje=Usuarios creados con éxito');
                exit();


            case 4:
                $foto = $_FILES["foto"] ?? null;

                // Error en el archivo (foto obligatoria)
                if ($foto['error'] !== UPLOAD_ERR_OK) {
                    die('Error al subir el archivo. Código: ' . $foto['error']);
                }

                // Tamaño maximo
                $maxSize = 2 * 1024 * 1024; // 2 MB
                if ($foto['size'] > $maxSize) {
                    die('El archivo supera el tamaño máximo permitido (2 MB).');
                }

                // Manejo de la extension
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($foto['tmp_name']);

                $permitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                ];

                if (!array_key_exists($mimeType, $permitidos)) {
                    die('Formato de imagen no permitido. Solo JPG, PNG o WEBP.');
                }

                $contenido = file_get_contents($_FILES["foto"]["tmp_name"]);
                $fotoCifrada = base64_encode($contenido);

                // Inserciones
                $conexion->beginTransaction();

                $sqlInsert = "INSERT INTO usuarios (Nombre, Apellidos, Nombre_Usuario, Contrasena, Foto, Moroso) VALUES (:nombre, :apellidos, :usuario, :password, :foto, :moroso)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'usuario' => $usuario, 'password' => password_hash($password, PASSWORD_BCRYPT), 'foto' => $fotoCifrada, 'moroso' => $moroso]);
                if ((int) $rol === 2) {
                    $sqlInsert = "INSERT INTO clientes VALUES (:idUsuario)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId()]);
                } else if ((int) $rol === 1) {
                    $sqlInsert = "INSERT INTO bibliotecarios VALUES (:idUsuario, :nuss)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute(['idUsuario' => $conexion->lastInsertId(), 'nuss' => "ES" . (String) rand(1111111111, 9999999999)]);
                }

                $conexion->commit();

                header('Location: creacionUsuarios_2.php?caso=4&mensaje=Usuario creado con éxito');
                exit();
        }

    } catch (PDOException $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        };
        header('Location: creacionUsuarios_2.php?caso='.$caso.'&error='.urlencode("Error de base de datos:" . $e->getMessage()));
    }

    $conexion = null;
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
            color: green;
            padding: 10px;
            background-color: #eef;
            border-left: 4px solid #0056b3;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            padding: 10px;
            background-color: #eef;
            border-left: 4px solid #0056b3;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <header>
        <p>Pulse <a href="../logout.php">aquí</a> para salir</p>
    </header>

    <h1>Alta de Usuarios</h1>

    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>


    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje"><?= htmlspecialchars($_GET['mensaje']) ?></div>
    <?php endif; ?>

    <!-- IMPORTANTE: enctype="multipart/form-data" es obligatorio para poder enviar archivos ($_FILES) -->
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="caso" value="<?= htmlspecialchars($_GET["caso"] ?? "1") ?>">

        <?php if (($_GET["caso"] ?? "1") === '3'): ?> <!-- Para insertar los tres libros -->
            <?php for ($i = 0; $i < 3; $i++): ?>
                <h3>Libro <?= $i + 1 ?>:</h3>
                <label>
                    Nombre:
                    <input type="text" name="nombre<?= $i ?>" placeholder="Ej. Laura">
                </label>

                <label>
                    Apellidos:
                    <input type="text" name="apellidos<?= $i ?>" placeholder="Ej. Martínez Gómez">
                </label>

                <label>
                    Nombre de usuario:
                    <input type="text" name="usuario<?= $i ?>" placeholder="Ej. lmartinez">
                </label>

                <label>
                    Contraseña:
                    <input type="password" name="password<?= $i ?>">
                </label>

                <label>
                    Rol:
                    <select name="rol<?= $i ?>">
                        <option value="1">Bibliotecario</option>
                        <option value="2">Cliente</option>
                    </select>
                </label>

                <label>
                    Foto de perfil:
                    <input type="file" name="foto<?= $i ?>" accept="image/*">
                </label>
                <br>
            <?php endfor ?>
        <?php else: ?> <!-- Para insertar solo un libro -->
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
                Rol:
                <select name="rol">
                    <option value="1">Bibliotecario</option>
                    <option value="2">Cliente</option>
                </select>
            </label>

            <label>
                Foto de perfil:
                <input type="file" name="foto" accept="image/*">
            </label>
        <?php endif ?>
        <br>
        <hr>
        <button type="submit">Guardar usuario(s)</button>
    </form>

</body>

</html>
<?php
// Iniciamos sesion y requerimos la conexión de bibliotecario (permisos totales)
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION["rol"] !== "bibliotecario") {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['caso'])) {
        header('Location: altaUsuarios.php?caso='.$_POST['caso']);
        exit();
    } else {
        header('Location: altaUsuariosCaso.php');
        exit();
    }
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
            padding: 10px;
            background-color: #eef;
            border-left: 4px solid #0056b3;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <h1>Panel de Bibliotecario - Alta de Usuarios</h1>

    <!-- IMPORTANTE: enctype="multipart/form-data" es obligatorio para poder enviar archivos ($_FILES) -->
    <form action="altaUsuariosCaso.php" method="POST" enctype="multipart/form-data">

        <label>
            Caso a ejecutar:
            <select name="caso">
                <option value="1">Caso 1: Usuario sin foto</option>
                <option value="2">Caso 2: Foto como archivo en el servidor</option>
                <option value="3">Caso 3: Varios usuarios simultáneamente</option>
                <option value="4">Caso 4: Foto como texto (Base64) en la BD</option>
            </select>
        </label>

        <br>

        <button type="submit">Continuar</button>
    </form>

</body>

</html>
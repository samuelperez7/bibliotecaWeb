<!-- Samuel Perez Ruiz -->

<?php
if (isset($_POST['tema'])) {
    $tema = $_POST['tema'];
    setcookie(
        "tema",
        $tema,
        [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]
    );
}

$temaActual = isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            transition: background-color 0.3s, color 0.3s;
        }

        <?php if ($temaActual === 'oscuro'): ?>
            body {
                background-color: #1a1a1a;
                color: #f0f0f0;
            }

            .card {
                background-color: #2a2a2a;
                border: 1px solid #444;
            }

        <?php else: ?>
            body {
                background-color: #f9f9f9;
                color: #333;
            }

            .card {
                background-color: #ffffff;
                border: 1px solid #ddd;
            }

        <?php endif; ?>
        .card {
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button,
        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            text-decoration: none;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        a:hover,
        button:hover {
            background: #0056b3;
        }
    </style>
    <title>Preferencias</title>
</head>

<body>
    <div class="card">
        <h2>Paso 1: Preferencias Visuales</h2>
        <form method="POST" action="">
            <label>Selecciona el tema visual:</label><br><br>
            <input type="radio" id="claro" name="tema" value="claro" <?php if ($temaActual === 'claro')
                echo 'checked'; ?>>
            <label for="claro">Tema Claro</label><br>
            <input type="radio" id="oscuro" name="tema" value="oscuro" <?php if ($temaActual === 'oscuro')
                echo 'checked'; ?>>
            <label for="oscuro">Tema Oscuro</label><br><br>

            <button type="submit">Guardar Preferencia</button>
        </form>
        <br>
        <a href="2_datosPersonales.php">Continuar al Paso 2 >></a>
    </div>
</body>

</html>
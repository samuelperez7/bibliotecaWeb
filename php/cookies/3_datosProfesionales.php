<!-- Samuel Perez Ruiz -->

<?php
session_start();

// Recogemos los datos con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'])) {
        $_SESSION['nombre'] = $_POST['nombre'];
        $_SESSION['apellidos'] = $_POST['apellidos'];
        $_SESSION['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
    }
}

// Recogemos la cookie tema
$temaActual = isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Paso 3: Datos Profesionales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
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

            select,
            input {
                background: #333;
                color: #fff;
                border: 1px solid #555;
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

            select,
            input {
                background: #fff;
                color: #333;
                border: 1px solid #ccc;
            }

        <?php endif; ?>
        .card {
            padding: 20px;
            border-radius: 8px;
            max-width: 450px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            box-sizing: border-box;
            border-radius: 4px;
        }

        .checkbox-group {
            margin-bottom: 15px;
        }

        .checkbox-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: normal;
        }

        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Paso 3: Datos Profesionales</h2>
        <form method="POST" action="4_resumenPerfil.php">
            <label for="estudios">Nivel de Estudios:</label>
            <select id="estudios" name="estudios" required>
                <option value="">Selecciona...</option>
                <option value="ESO / Bachillerato">ESO / Bachillerato</option>
                <option value="Grado Medio">Grado Medio</option>
                <option value="Grado Superior (DAW u otro)">Grado Superior (DAW u otro)</option>
                <option value="Universidad">Universidad</option>
            </select>

            <label for="experiencia">Años de Experiencia:</label>
            <input type="number" id="experiencia" name="experiencia" min="0" max="50" required>

            <label>Habilidades:</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="habilidades[]" value="PHP"> PHP & PDO</label>
                <label><input type="checkbox" name="habilidades[]" value="JavaScript"> JavaScript</label>
                <label><input type="checkbox" name="habilidades[]" value="HTML/CSS"> HTML5 & CSS</label>
                <label><input type="checkbox" name="habilidades[]" value="Bases de Datos"> Bases de Datos (MySQL)</label>
                <label><input type="checkbox" name="habilidades[]" value="Git"> Git & GitHub</label>
            </div>

            <button type="submit">Ver Resumen >></button>
        </form>
    </div>
</body>

</html>
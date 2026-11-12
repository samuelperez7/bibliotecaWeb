<!-- Samuel Perez Ruiz -->

<?php
session_start();

// Recojo los datos con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['estudios'] = $_POST['estudios'] ?? '';
    $_SESSION['experiencia'] = $_POST['experiencia'] ?? '';
    $_SESSION['habilidades'] = $_POST['habilidades'] ?? [];
}

// Recojo la cookie tema
$temaActual = isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Paso 4: Resumen del Perfil</title>
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

            table {
                color: #f0f0f0;
                border-color: #555;
            }

            th,
            td {
                border-color: #555;
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

            table {
                color: #333;
                border-color: #ccc;
            }

            th,
            td {
                border-color: #ccc;
            }

        <?php endif; ?>
        .card {
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid;
        }

        th {
            background: rgba(0, 123, 255, 0.2);
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        a {
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
        }

        .btn-edit {
            background: #6c757d;
        }

        .btn-edit:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #bd2130;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Resumen Completo del Perfil</h2>

        <table>
            <tr>
                <th colspan="2">Datos Personales</th>
            </tr>
            <tr>
                <td><strong>Nombre y Apellidos:</strong></td>
                <td><?php echo htmlspecialchars(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? '')); ?>
                </td>
            </tr>
            <tr>
                <td><strong>Fecha de Nacimiento:</strong></td>
                <td><?php echo htmlspecialchars($_SESSION['fecha_nacimiento'] ?? ''); ?></td>
            </tr>
            <tr>
                <th colspan="2">Datos Profesionales</th>
            </tr>
            <tr>
                <td><strong>Nivel de Estudios:</strong></td>
                <td><?php echo htmlspecialchars($_SESSION['estudios'] ?? ''); ?></td>
            </tr>
            <tr>
                <td><strong>Años de Experiencia:</strong></td>
                <td><?php echo htmlspecialchars($_SESSION['experiencia'] ?? '0'); ?> años</td>
            </tr>
            <tr>
                <td><strong>Habilidades:</strong></td>
                <td>
                    <?php
                    if (!empty($_SESSION['habilidades']) && is_array($_SESSION['habilidades'])) {
                        echo htmlspecialchars(implode(', ', $_SESSION['habilidades']));
                    } else {
                        echo 'Ninguna seleccionada';
                    }
                    ?>
                </td>
            </tr>
        </table>

        <div class="actions">
            <a href="2_datosPersonales.php" class="btn-edit"> << Modificar Datos</a>
            <a href="5_borrarTodo.php" class="btn-danger">Borrar Todo y Reiniciar</a>
        </div>
    </div>
</body>

</html>
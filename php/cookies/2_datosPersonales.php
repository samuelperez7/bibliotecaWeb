<?php
$temaActual = isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'claro';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Paso 2: Datos Personales</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        <?php if ($temaActual === 'oscuro'): ?>
        body { background-color: #1a1a1a; color: #f0f0f0; }
        .card { background-color: #2a2a2a; border: 1px solid #444; }
        input { background: #333; color: #fff; border: 1px solid #555; }
        <?php else: ?>
        body { background-color: #f9f9f9; color: #333; }
        .card { background-color: #ffffff; border: 1px solid #ddd; }
        input { background: #fff; color: #333; border: 1px solid #ccc; }
        <?php endif; ?>
        .card { padding: 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 5px 0 15px 0; box-sizing: border-box; border-radius: 4px; }
        button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Paso 2: Datos Personales</h2>
        <form method="POST" action="3_datosProfesionales.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <button type="submit">Siguiente >></button>
        </form>
    </div>
</body>
</html>
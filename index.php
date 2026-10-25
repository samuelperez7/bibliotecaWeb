<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Iniciar sesión</title>
        <link rel="stylesheet" href="style/login.css">
    </head>
    <body>
        <div class="contenedor-login">
            <h2>Iniciar sesión</h2>
            <form action="php/procesarLogin.php" method="POST">
                <div class="campo-login">
                    <input type="text" name="usuario" required>
                    <label>Usuario</label>
                </div>
                <div class="campo-login">
                    <input type="password" name="password" required>
                    <label>Contraseña</label>
                </div>
                <button type="submit">Acceder</button>
            </form>
        </div>
    </body>
</html>
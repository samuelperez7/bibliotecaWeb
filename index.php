<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Iniciar sesión</title>
        <link rel="stylesheet" href="./style/login.css">
    </head>
    <body>
        <div class="contenedor-login">
            <h2>Iniciar sesión</h2>
            <div><span class='error'><?php echo $error = "" ?></span></div>
            <form action="php/procesarLogin.php" method="POST">
                <div class="campo-login">
                    <input type="text" name="inputUsuario" maxlength="50" required>
                    <label>Usuario</label>
                </div>
                <div class="campo-login">
                    <input type="password" name="inputPassword" maxlength="50" required>
                    <label>Contraseña</label>
                </div>
                <button type="submit">Acceder</button>
            </form>
        </div>
    </body>
</html>
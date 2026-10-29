<?php
//Generar PDO (PHP Data Objects) para la conexión a la base de datos

// Variables de conexión para cliente
define('DB_HOST', 'localhost:3307'); // Host de la base de datos
define('DB_NAME', 'biblioteca'); // Nombre de la base de datos
define('DB_USER', 'cliente'); // Usuario de la base de datos
define('DB_PASS', 'cliente'); // Contraseña de la base de datos

// Variables de conexión para biblioteca
define('DB_HOST', 'localhost:3307');
define('DB_NAME', 'biblioteca');
define('DB_ADMIN', 'bibliotecario');
define('DB_ADMIN_PASS', 'bibliotecario');

// Creamos la conexión PDO cliente
function conectarCliente(): PDO{
    static $conexion = null;

    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';

    return $conexion ?? new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Modo de error: Excepciones para usar try/catch
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   //Devuelve los resultados como un array asociativo
            PDO::ATTR_EMULATE_PREPARES => false,                //Evita inyecciones SQL al desactivar la emulación de consultas preparadas
        ]
    );
}

// Creamos la conexión PDO bibliotecario
function conectarBibliotecario(): PDO{
    static $conexion = null;

    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';

    return $conexion ?? new PDO(
        $dsn,
        DB_ADMIN,
        DB_ADMIN_PASS,
        [
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Modo de error: Excepciones para usar try/catch
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   //Devuelve los resultados como un array asociativo
            PDO::ATTR_EMULATE_PREPARES => false,                //Evita inyecciones SQL al desactivar la emulación de consultas preparadas
        ]
    );
}
?>
<?php
//Generar PDO (PHP Data Objects) para la conexión a la base de datos

//Definimos las variables de conexión
define('DB_HOST', 'localhost:3306'); // Host de la base de datos
define('DB_NAME', 'biblioteca'); // Nombre de la base de datos
define('DB_USER', 'bibliotecario'); // Usuario de la base de datos
define('DB_PASS', 'bibliotecario'); // Contraseña de la base de datos

//Creamos la conexión PDO
function conectarBibliotecario(): PDO{
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
?>
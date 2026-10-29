<?php
// Recuperamos la información de la sesión
session_start();

// Copiamos los parametros de la cookie de sesion
$params = session_get_cookie_params();

$_SESSION = Array();

// Hacemos que la cookie caduque
setcookie(
    session_name(), // ($_COOKIE["PHPSESSID"])
    '', 
    [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'] ?? ''
    ]
);

// Y la destruimos
session_destroy();

// Redirigimos al login
header("Location: ../index.php");
exit();
?>
<?php
// Recuperamos la información de la sesión
session_start();

$params = session_get_cookie_params();

$_SESSION = Array();

setcookie(
    session_name(), // Es mejor usar session_name() en lugar de "PHPSESSID" por seguridad
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
<?php

// Recojo la sesion
session_start();

$_SESSION = array(); // Limpio variables de sesión

$params = session_get_cookie_params();
setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
);

session_destroy();

// Elimino la cookie del tema visual (caducidad en el pasado)
if (isset($_COOKIE['tema'])) {
    setcookie('tema', '', time() - 3600, '/');
}

// Redirijo automáticamente de vuelta al paso 1
header("Location: 1_preferencias.php");
exit();

?>
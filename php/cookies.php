<?php
//Creación de cookies antiguas
// Creamos una cookie para recordar el usuario durante 30 días.
// time() + (30 * 24 * 60 * 60) calcula los segundos totales de 30 días.
setcookie(
    "usuario_recordado",  // nombre
    "admin_ana",         // valor
    time() + (30 * 24 * 60 * 60), // expira (30 días en el futuro)
    "/",                // ruta (disponible en toda la web)
     "",                // dominio (vació = dominio actual)
    true,               // seguro (solo se envía por HTTPS)
    true                // httponly (bloquea acceso desde JavaScript, evita XSS)
);

//Creación de cookies modernas

// Creamos una cookie para el tema visual (oscuro/claro) válida por 7 días.
// Este formato es más legible porque sabes exactamente qué es cada valor.
setcookie(
    "preferencias_tema", "oscuro", [
    'expires'   => time() + (7 * 24 * 60 * 60), // expira (7 días en el futuro)
    'path'   => '/', // ruta (disponible en toda la web)
    'domain'     => '', // dominio (vació = dominio actual)
    'secure'     => true, // seguro (solo se envía por HTTPS)
    'httponly' => true, // httponly (bloquea acceso desde JavaScript, evita XSS)
    'samesite' => 'Strict' // Atributo de seguridad moderno contra ataques CSRF
]);
?>
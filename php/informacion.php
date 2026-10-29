<?php
// Funcion para mostrar variables seguras de PHP
function imprimir($variable) {
    if (isset($_SERVER[$variable])) {
        return htmlspecialchars($_SERVER[$variable],ENT_QUOTES, "UTF-8");
    }
    return "No hay variable";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del servidor</title>
</head>
<body>
    <h2>Datos de la variable $_SERVER</h2>
    <ul>
        <li>
            <span class="variable">PHP_SELF: </span>
            <span class="vakir"><?= imprimir("PHP_SELF") ?></span>
        </li>
        <li>
            <span class="variable">SERVER_SOFTWARE: </span>
            <span class="vakir"><?= imprimir("SERVER_SOFTWARE") ?></span>
        </li>
        <li>
            <span class="variable">SERVER_NAME: </span>
            <span class="vakir"><?= imprimir("SERVER_NAME") ?></span>
        </li>
        <li>
            <span class="variable">REQUEST_METHOD: </span>
            <span class="vakir"><?= imprimir("REQUEST_METHOD") ?></span>
        </li>
        <li>
            <span class="variable">REQUEST_URI: </span>
            <span class="vakir"><?= imprimir("REQUEST_URI") ?></span>
        </li>
        <li>
            <span class="variable">QUERY_STRING: </span>
            <span class="vakir"><?= imprimir("QUERY_STRING") ?></span>
        </li>
    </ul>
</body>
</html>
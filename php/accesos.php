<?php

if (!isset($_SESSION)) {
    session_start();
    $sessionId = session_id();
}
if (!isset($_SESSION['nombre_usuario'])) {
    die("Error - debe <a href='../index.php'>Identificarse</a>.<br />");
}
$accesosPagina;

$nombreCookie = "accesosPagina-".$sessionId;

if (isset($_COOKIE[$nombreCookie])) {
    $accesosPagina = $_COOKIE[$nombreCookie];
    if ($accesosPagina <= 10) {
        $accesosPagina++;
        setcookie($nombreCookie, $accesosPagina, [
            "path" => "/"
        ]);
    } else {
        $accesosPagina = 1;
        setcookie($nombreCookie, $accesosPagina, [
            "path" => "/"
        ]);
    }
    ;
} else {
    $accesosPagina = 1;
    setcookie($nombreCookie, "1", [
        "expires" => time() + (7 * 24 * 60 * 60),
        "path" => "/"
    ]);
}
;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesos a la pagina</title>
</head>

<body>
    <header>
        <p>Pulse <a href="logout.php">aquí</a> para salir. <?= $sessionId ?></p>
    </header>

    <h2>Accesos a la pagina</h2>
    <p>Has accedido a esta pagina <?= $accesosPagina ?> veces.</p>
</body>

</html>
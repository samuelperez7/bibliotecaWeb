<?php
// Incluimos la conexión con permisos de bibliotecario (ubicada en la carpeta conexion)
require_once "../conexionDB/conexion.php";

// Verificamos que los datos lleguen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuarioForm = $_POST['inputUsuario'] ?? '';
    $passwordForm = $_POST['inputPassword'] ?? '';

    if (!empty($usuarioForm) && !empty($passwordForm)) {
        try {
            // 1. Usamos la función de conexión con privilegios totales
            $conexion = conectarBibliotecario();

            // 2. Consulta con LEFT JOIN para verificar si el usuario es bibliotecario o cliente
            $sql = "SELECT u.*, b.Id_Usuario AS es_bibliotecario 
                    FROM usuarios u 
                    LEFT JOIN bibliotecarios b ON u.Id = b.Id_Usuario 
                    WHERE u.Nombre_Usuario = :nombre_usuario";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute(['nombre_usuario' => $usuarioForm]);
            $usuario = $stmt->fetch();

            // 3. Comprobamos la contraseña en texto plano
            if ($usuario && $usuario['Contrasena'] === $passwordForm) {
                
                // Iniciamos sesión en PHP
                session_start();
                session_regenerate_id(true);
                $_SESSION['user_id'] = $usuario['Id'];
                $_SESSION['nombre_usuario'] = $usuario['Nombre_Usuario'];

                // 4. Redirección basada en si es bibliotecario o no
                if (!empty($usuario['es_bibliotecario'])) {
                    // Si está en la tabla bibliotecarios, va a su panel de gestión total
                    header('Location: accesos.php');
                    exit();
                } else {
                    // Si es un cliente normal, va a la vista de préstamos
                    header('Location: accesos.php');
                    exit();
                }

            } else {
                header('Location: index.php?error=Usuario o contraseña incorrectos');
                exit();
            }

        } catch (PDOException $e) {
            header('Location: index.php?error=Error en la base de datos');
            exit();
        }
    } else {
        header('Location: index.php?error=Por favor completa todos los campos');
        exit();
    }
}
?>
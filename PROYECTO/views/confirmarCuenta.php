<?php
include '../src/Administracion.php';
include '../src/Usuario.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $usuario = new Usuario($db); // Pasamos $db al constructor

    try {
        if ($usuario->confirmarToken($token)) {
            echo "<p>Tu cuenta ha sido confirmada con éxito. Ahora puedes iniciar sesión.</p>";
            echo '<p><a href="login.php">Regresar al Login</a></p>'; // Enlace para regresar al login
        } else {
            echo "<p>Token inválido o expirado.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>No se ha proporcionado un token.</p>";
}

?>

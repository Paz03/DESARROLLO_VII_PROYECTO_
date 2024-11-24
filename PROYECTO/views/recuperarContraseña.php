<?php
include '../src/config.php';
require_once '../src/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = htmlspecialchars($_POST['email']);
    $usuarioObj = new Usuario();

    try {
        // Generar token único
        $token = bin2hex(random_bytes(50));

        // Llamar al método para actualizar el token
        $filasAfectadas = $usuarioObj->actualizarTokenRecuperacion($correo, $token);

        if ($filasAfectadas) {
            // Enviar correo al usuario
            $link = "http://localhost/PROYECTO/views/cambioContraseña.php?token=$token";
            $mensaje = "<p class='mensaje-alerta'>Haz clic en el siguiente enlace para restablecer tu contraseña:\n$link </p>";
            mail($correo, "<p>Restablece tu contraseña</p>", $mensaje, "From: no-reply@tusitio.com");

            echo "<p class='mensaje-alerta'>Revisa tu correo para restablecer tu contraseña.</p>";
        } else {
            echo "<p class='mensaje-error'>El correo ingresado no está registrado.</p>";
        }
    } catch (Exception $e) {
        echo "<p class='mensaje-error'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/recuperarContraseña.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <h2>
            <a href="login.php" style="text-decoration: none; color: white;">
                <i class='bx bx-arrow-back'></i>
            </a>
            Recuperar Contraseña
        </h2>
        <form method="POST" action="recuperarContraseña.php">
            <div class="input-box">
                <input type="email" name="email" id="email" placeholder="Escribe tu correo electrónico" required>
            </div>
            <button class="btn" type="submit">Enviar Enlace</button>
        </form>
    </div>
</body>
</html>


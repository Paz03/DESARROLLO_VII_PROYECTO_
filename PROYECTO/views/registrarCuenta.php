<?php
include '../src/config.php';
include '../src/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas de usuario
    $nombre_usuario = htmlspecialchars(trim($_POST['username']));
    $correo_electronico = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contrasena = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(50)); // Crear un token único
    $usuario = new Usuario();
    $usuarioExistente = $usuario->obtenerUsuarioPorCorreo($correo_electronico);

    // Validar correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='mensaje-error'>Correo electrónico no válido.</p>";
    }
    else{
    if ($usuarioExistente) {
        // Si el correo ya está registrado, mostrar un mensaje
        echo "<p class='mensaje-error'>El correo electrónico ya está registrado. Por favor, usa otro correo.</p>";
    }
    else{
    try {
        // Enviar el correo de confirmación
        $to = $correo_electronico;
        $subject = "Confirma tu cuenta";
        $message = "Hola, $nombre_usuario.\n\nPor favor, confirma tu cuenta haciendo clic en el siguiente enlace:\n\n";
        $message .= "http://localhost/PROYECTO/views/confirmarCuenta.php?token=$token";
        $headers = "From:jhonielsmith813@gmail.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            // Registrar al usuario en la base de datos
            $usuario->RegistroUsuarioPorToken($nombre_usuario, $correo_electronico, $contrasena, $token);
            echo "<p class='mensaje-exito'>Te hemos enviado un correo electrónico para confirmar tu cuenta.</p>";
        } else {
            echo "<p class='mensaje-error'>Error al enviar el correo de confirmación.</p>";
        }
        } catch (Exception $e) {
            echo "<p class='mensaje-error'>Error: " . $e->getMessage() . "</p>";
        }        
    }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/registrarCuenta.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <h2>
            <a href="login.php" style="text-decoration: none; color: white;">
                <i class='bx bx-arrow-back'></i>
            </a>
            Registrarse
        </h2>
        <form method="POST" action="registrarCuenta.php">
            <div class="input-box">
                <input type="text" name="username" id="username" placeholder="Escribe tu nombre de usuario" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" id="email" placeholder="Escribe tu correo electrónico" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Escribe tu contraseña" required>
            </div>
            <button class="btn" type="submit">Registrar</button>        
            <div class="register-link">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
</body>
</html>

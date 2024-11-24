<?php
session_start();  // Inicia la sesión al principio de la página
include '../src/config.php';
include_once '../src/googleOAuth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = htmlspecialchars($_POST['email']);
    $contraseña = $_POST['password'];

    try {
        // Consulta para verificar el correo, la contraseña y el estado de confirmación
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo_electronico = :email");
        $stmt->bindParam(':email', $correo);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($contraseña, $usuario['contrasena'])) {
            // Verificar si la cuenta está confirmada
            if ($usuario['confirmado'] == 1) {
                $_SESSION['user_id'] = $usuario['id'];  // Almacena el ID del usuario en la sesión
                $_SESSION['permiso'] = $usuario['permiso'];  // Almacena el permiso del usuario ('usuario' o 'admin')
                    header("Location: habitaciones.php");  // Redirige a la página de habitaciones si es usuario 
                exit();  // Asegúrate de que no se ejecute más código después de redirigir
            } else {
                echo "<p class='mensaje-alerta'>Debes confirmar tu cuenta antes de iniciar sesión. Revisa tu correo electrónico.</p>";
            }
        } else {
            echo "<p class='mensaje-error'>Correo electrónico o contraseña incorrectos.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='mensaje-error'>Error: " . $e->getMessage() . "</p>";
    }            
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/login.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <form method="POST">
            <h2>
                <a href="../index.php" style="text-decoration: none; color: white;">
                    <i class='bx bx-arrow-back'></i>
                </a>
                Iniciar Sesión
            </h2>
            <div class="input-box">
                <input type="email" name="email" id="email" placeholder="Escribe tu correo electrónico" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Escribe tu contraseña" required>
            </div>
            <div class="submit">
                <button class="btn" type="submit">Iniciar Sesión</button>
            </div>
            <div class="register-link">
                <p>¿No tienes cuenta? <a href="registrarCuenta.php">Regístrate aquí</a></p>
            </div>
            <div class="remember-forgot">
                <p>¿Olvidaste tu contraseña? <a href="recuperarContraseña.php">Recupérala aquí</a></p>
            </div>    
            <div class="register-link"> 
                <p><a href="<?= GoogleOAuth::obtenerUrlDeAutenticacion(); ?>">Iniciar sesión con Google</a></p>
            </div>
        </form>
    </div>
</body>
</html>

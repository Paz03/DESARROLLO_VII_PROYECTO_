<?php
include '../src/Administracion.php';
require_once '../src/Usuario.php'; // Incluir la clase Usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nuevaContrasena = $_POST['password'];
    
    // Crear una instancia de la clase Usuario
    $usuario = new Usuario();

    try {
        // Llamar al método de la clase Usuario para actualizar la contraseña
        if ($usuario->actualizarContrasenaPorToken($token, $nuevaContrasena)) {
            echo "<p class='mensaje-alerta'>Tu contraseña ha sido actualizada exitosamente. <a href='login.php'>Inicia sesión</a></p>";
            echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 3000);</script>"; // 3 segundos de espera
        } else {
            echo "<p class='mensaje-error'>El token es inválido o ha expirado.</p>";
        }
    } catch (Exception $e) {
        echo "<p class='mensaje-error'>Error: " . $e->getMessage() . "</p>";
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("<p class='mensaje-error'> Token no proporcionado.</p>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/cambioContraseña.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <h2>
            <a href="login.php" style="text-decoration: none; color: white;">
                <i class='bx bx-arrow-back'></i>
            </a>
            Cambiar Contraseña
        </h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Escribe tu nueva contraseña" required>
            </div>
            <button class="btn" type="submit">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>


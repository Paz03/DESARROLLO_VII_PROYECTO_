<?php
require_once  'config.php';
require_once 'Usuario.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasena = $_POST['password'];
    $userId = $_SESSION['user_id'];
    $usuario = new Usuario($db);

    try {
        $usuario->actualizarContrasena($userId, $contrasena);
        // Redirigir después de guardar la contraseña
        header('Location: habitaciones.php');
        exit();
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Establecer Contraseña</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/establecerContraseña.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <h2>
            <a href="../index.php" style="text-decoration: none; color: white;">
                <i class='bx bx-arrow-back'></i>
            </a>
            Establecer Contraseña
        </h2>
        <form method="POST">
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Escribe tu nueva contraseña" required>
            </div>
            <button class="btn" type="submit">Guardar Contraseña</button>
        </form>
    </div>
</body>
</html>

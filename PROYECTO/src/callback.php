<?php
require_once 'GoogleOAuth.php';
require_once 'Usuario.php';

session_start();

if (isset($_GET['code'])) {
    $codigo = $_GET['code'];
    $tokenData = GoogleOAuth::obtenerToken($codigo);

    if (isset($tokenData['access_token'])) {
        $datosUsuario = GoogleOAuth::obtenerDatosUsuario($tokenData['access_token']);
        $googleId = $datosUsuario['id'];
        $nombre = $datosUsuario['name'];
        $email = $datosUsuario['email'];

        $usuario = new Usuario();

        // Verificar si el usuario existe por correo
        $usuarioExistentePorCorreo = $usuario->obtenerUsuarioPorCorreo($email);

        if ($usuarioExistentePorCorreo) {
            // Si el usuario existe pero no tiene google_id, actualizamos ese registro
            if (empty($usuarioExistentePorCorreo['google_id'])) {
                $usuario->ActualizarGoogleID($usuarioExistentePorCorreo['id'], $googleId);
            }
            // Usar el registro existente
            $usuarioExistente = $usuario->obtenerUsuarioPorCorreo($email);
        } else {
            // Si el usuario no existe, lo registramos con google_id
            $usuario->RegistroUsuarioOauth($nombre, $email, $googleId);
            $usuarioExistente = $usuario->obtenerUsuarioPorGoogleId($googleId);
        }

        // Guardar el ID del usuario en la sesión
        $_SESSION['user_id'] = $usuarioExistente['id'];
        $_SESSION['permiso'] = $usuarioExistente['permiso'];
        // Verificar si el usuario tiene contraseña
        if (empty($usuarioExistente['contrasena'])) {
            // Redirigir a contraseña.php si no tiene contraseña
            header('Location: establecerContraseña.php');
        } else {
            // Redirigir a la página principal si ya tiene contraseña
            header('Location: ../views/habitaciones.php');
        }
        exit();
    } else {
        // Error al obtener el token
        echo "Error al obtener el token.";
    }
} else {
    // Error si no se recibe el código
    echo "Código de autorización no recibido.";
}

?>

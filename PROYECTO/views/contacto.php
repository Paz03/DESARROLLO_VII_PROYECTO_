<?php
// Procesar el formulario de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];
    $error = '';

    // Validar los campos
    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    }

    // Si no hay errores, enviar el mensaje
    if (empty($error)) {
        // Dirección de correo de destino
        $correo_destino = "Jhonielsmith813@gmail.com";
        
        // Asunto del correo
        $asunto = "Nuevo mensaje de contacto de $nombre";
        
        // Cuerpo del mensaje
        $cuerpo = "Nombre: $nombre\n";
        $cuerpo .= "Correo electrónico: $correo\n";
        $cuerpo .= "Mensaje:\n$mensaje\n";
        
        // Encabezados del correo
        $headers = "From: $correo" . "\r\n" .
                   "Reply-To: $correo" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Enviar el correo
        if (mail($correo_destino, $asunto, $cuerpo, $headers)) {
            echo "<p class='mensaje-exito'>Gracias por tu mensaje, nos pondremos en contacto contigo pronto.</p>";
        } else {
            echo "<p class='mensaje-error'>Hubo un error al enviar el mensaje. Por favor, inténtalo nuevamente más tarde.</p>";
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto y Soporte</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../public/assets/css/contacto.css" rel="stylesheet">    
</head>
<body>
    <div class="wrapper">
        <h2>
            <a href="../index.php" style="text-decoration: none; color: white;">
                <i class='bx bx-arrow-back'></i>
            </a>
            Contacto y Soporte
        </h2>

        <form action="contacto.php" method="POST">
            <div class="input-box">
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>
            <div class="input-box">
                <input type="email" id="correo" name="correo" placeholder="Tu correo electrónico" required>
            </div>
            <div class="input-box">
                <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje" rows="5" required></textarea>
            </div>
            <button class="btn" type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>

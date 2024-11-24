<?php
session_start();
include '../src/config.php';
include '../src/Usuario.php';
include '../src/Reserva.php';

if (isset($_SESSION['user_id'])) {
    $usuario_id = $_SESSION['user_id'];

    // Verificar si el ID de la habitación está presente
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $habitacion_id = $_GET['id']; // ID de la habitación pasada como parámetro GET
        $token = bin2hex(random_bytes(50)); // Generar un token único

        $reserva = new Reserva($db); // Usar conexión $db con PDO

        try {
            if ($reserva->guardarReserva($usuario_id, $habitacion_id, $token)) {
                // Enviar correo de confirmación
                $usuario = new Usuario($db);
                $correo = $usuario->obtenerCorreoPorId($usuario_id);

                if ($correo) {
                    $to = $correo;
                    $subject = "Confirma tu reserva";
                    $message = "Hola, por favor confirma tu reserva haciendo clic en el siguiente enlace:\n\n";
                    $message .= "http://localhost/PROYECTO/views/confirmarReserva.php?token=$token";
                    $headers = "From: jhonielsmith813@gmail.com\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    if (mail($to, $subject, $message, $headers)) {
                        echo "<p>Reserva guardada con éxito. Se ha enviado un correo de confirmación.</p>";
                    } else {
                        echo "<p>Error al enviar el correo de confirmación.</p>";
                    }
                } else {
                    echo "<p>Error: No se pudo obtener el correo del usuario.</p>";
                }
            }
        } catch (Exception $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Error: ID de habitación inválido.</p>";
    }
} else {
    echo "<p>Error: Usuario no autenticado.</p>";
}
?>
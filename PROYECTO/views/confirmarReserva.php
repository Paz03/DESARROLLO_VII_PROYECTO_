<?php
include '../src/Administracion.php';
require_once '../src/Reserva.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $reserva = new Reserva($db); // Usar conexión $db con PDO

    try {
        if ($reserva->confirmarToken($token)) {
            echo "<p>Tu reserva ha sido confirmada con éxito.</p>";
            echo '<p><a href="habitaciones.php">Regresar al Inicio</a></p>';
        } else {
            echo "<p>Token inválido o ya utilizado.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>No se ha proporcionado un token.</p>";
}
?>

<?php
// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión y cerrar la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión (login.php)
header('Location: ../index.php');

// Terminar la ejecución del script
exit();
?>

<?php

// Incluir la clase EnvLoader
require_once 'EnvLoader.php';

try {
    // Cargar las variables desde el archivo .env
    EnvLoader::load(__DIR__ . '../../.env');
} catch (Exception $e) {
    die($e->getMessage());
}

// Definir constantes utilizando las variables de entorno
define('GOOGLE_CLIENT_ID', EnvLoader::get('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', EnvLoader::get('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', EnvLoader::get('GOOGLE_REDIRECT_URI'));

define('DB_SERVER', EnvLoader::get('DB_SERVER'));
define('DB_USERNAME', EnvLoader::get('DB_USERNAME'));
define('DB_PASSWORD', EnvLoader::get('DB_PASSWORD'));
define('DB_NAME', EnvLoader::get('DB_NAME'));


// Ejemplo de conexión a la base de datos
try {
    $db = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa.";
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
<?php
// GoogleOAuth.php

// Incluir archivo de configuración con credenciales de Google OAuth.
require_once  'config.php';
class GoogleOAuth {
    //login
    // Funcion para generar la URL de autenticación de Google OAuth.
    public static function obtenerUrlDeAutenticacion() {
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,           // ID de cliente de Google
            'redirect_uri' => GOOGLE_REDIRECT_URI,     // URI de redirección tras la autenticación
            'response_type' => 'code',                 // Tipo de respuesta (código de autorización)
            'scope' => 'openid email profile',         // Alcances solicitados: información básica de perfil y correo electrónico
            'access_type' => 'offline'                 // Solicita un token de actualización para acceso offline
        ];
        // Construir y retornar la URL completa para iniciar la autenticación en Google
        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }
    
    //callback
    // Funcion para obtener un token de acceso usando un código de autorización.
    public static function obtenerToken($codigo) {
        $params = [
            'code' => $codigo,                        // Código de autorización recibido de Google
            'client_id' => GOOGLE_CLIENT_ID,          // ID de cliente
            'client_secret' => GOOGLE_CLIENT_SECRET,  // Secreto de cliente
            'redirect_uri' => GOOGLE_REDIRECT_URI,    // URI de redirección, es la URI registrada en Google
            'grant_type' => 'authorization_code'      // Tipo de concesión, en este caso 'authorization_code'
        ];

        // Se inicializar una solicitud cURL para obtener el token de Google
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);                // Indicar que se trata de una solicitud POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); // Enviar los parámetros codificados
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // Configurar cURL para retornar la respuesta como string

        $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta
        curl_close($ch);            // Cerrar la sesión de cURL

        return json_decode($response, true); // Decodificar la respuesta JSON y devolver el array resultante
    }

    // Funcion para obtener los datos del usuario autenticado mediante un token de acceso.
    public static function obtenerDatosUsuario($token) {
        // Inicializar una solicitud cURL para obtener los datos del usuario usando el token de acceso
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Configurar cURL para retornar la respuesta como string

        $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta
        curl_close($ch);            // Cerrar la sesión de cURL

        return json_decode($response, true); // Decodificar la respuesta JSON y devolver el array resultante
    }
}
?>

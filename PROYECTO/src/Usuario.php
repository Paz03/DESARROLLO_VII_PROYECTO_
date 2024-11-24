<?php
// Usuario.php

require_once 'config.php';

class Usuario {

    // Otros métodos como obtener usuarios, registrar con Google, actualizar Google ID
    public function obtenerUsuarioPorCorreo($correo) {
        global $db;
        $sql = "SELECT * FROM usuarios WHERE correo_electronico = :correo";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorGoogleId($googleId) {
        global $db;
        $sql = "SELECT * FROM usuarios WHERE google_id = :googleId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':googleId', $googleId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function RegistroUsuarioOauth($nombre, $correo, $googleId) {
        global $db;
        $sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, google_id) 
                VALUES (:nombre, :correo, :googleId)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':googleId', $googleId, PDO::PARAM_STR);
        $stmt->execute();
    }

        // Método para registrar un usuario de forma normal (correo y contraseña)
        public function RegistroUsuarioPorToken($nombre, $correo, $contrasena, $token) {
            global $db;
            try {
                $sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, token) 
                        VALUES (:nombre_usuario, :correo_electronico, :contrasena, :token)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':nombre_usuario', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':correo_electronico', $correo, PDO::PARAM_STR);
                $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->execute();
            } catch (PDOException $e) {
                throw new Exception("Error al registrar usuario: " . $e->getMessage());
            }
        }
    

    public function ActualizarGoogleID($usuarioId, $googleId) {
        global $db;
        $sql = "UPDATE usuarios SET google_id = :googleId WHERE id = :usuarioId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':googleId', $googleId, PDO::PARAM_STR);
        $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Método para verificar y confirmar un usuario por token
    public function confirmarToken($token) {
        global $db; // Esto asegura que $db esté disponible en el contexto del método
        try {
            // Verificar si el token existe en la base de datos
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE token = :token");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($usuario) {
                // Actualizar el estado del usuario
                $stmt = $db->prepare("UPDATE usuarios SET confirmado = 1, token = NULL WHERE token = :token");
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->execute();
                return true; // Confirmación exitosa
            } else {
                return false; // Token inválido o expirado
            }
        } catch (PDOException $e) {
            throw new Exception("Error al confirmar la cuenta: " . $e->getMessage());
        }
    }

    // Método para actualizar la contraseña y confirmar el estado del usuario
    public function actualizarContrasena($userId, $nuevaContrasena) {
        try {
            // Acceso a la conexión global
            global $db;
    
            // Encriptar la nueva contraseña
            $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    
            // Preparar la consulta SQL para actualizar la contraseña y confirmar el estado del usuario
            $stmt = $db->prepare("UPDATE usuarios SET contrasena = :contrasena, confirmado = 1 WHERE id = :id");
            $stmt->bindParam(':contrasena', $contrasenaHash, PDO::PARAM_STR);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    
            // Ejecutar la consulta
            $stmt->execute();
        } catch (PDOException $e) {
            // Manejar errores y lanzar una excepción con un mensaje descriptivo
            throw new Exception("Error al actualizar la contraseña: " . $e->getMessage());
        }
    }
    public function actualizarTokenRecuperacion($correo, $token) {
        global $db;
        try {
            // Preparar la consulta para actualizar el token en la base de datos
            $sql = "UPDATE usuarios SET token = :token WHERE correo_electronico = :correo";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se actualizó el token correctamente
            return $stmt->rowCount(); // Retorna el número de filas afectadas
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el token de recuperación: " . $e->getMessage());
        }
    }

    // Método para actualizar la contraseña usando el token
public function actualizarContrasenaPorToken($token, $nuevaContrasena) {
    global $db;
    try {
        // Encriptar la nueva contraseña
        $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        
        // Preparar la consulta SQL para actualizar la contraseña
        $sql = "UPDATE usuarios SET contrasena = :contrasena, token = NULL WHERE token = :token";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':contrasena', $contrasenaHash, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Retorna si se actualizó la contraseña
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar la contraseña: " . $e->getMessage());
    }
}
public function obtenerCorreoPorId($usuario_id) {
    global $db;
    $sql = "SELECT correo_electronico FROM usuarios WHERE id = :usuario_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $usuario ? $usuario['correo_electronico'] : null; // Retorna el correo o null si no existe
}

}

<?php
class Reserva {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Guarda una nueva reserva en la base de datos
    public function guardarReserva($usuario_id, $habitacion_id, $token) {
        $sql = "INSERT INTO reservas (usuario_id, habitacion_id, token, confirmada) VALUES (:usuario_id, :habitacion_id, :token, 0)";
        $stmt = $this->db->prepare($sql);
    
        // Asignar valores a los parámetros con PDO
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':habitacion_id', $habitacion_id, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al guardar la reserva.");
        }
    }

    // Confirma la reserva utilizando un token
    public function confirmarToken($token) {
        // Consultar la reserva por el token
        $sql = "SELECT r.id AS reserva_id, r.habitacion_id 
                FROM reservas r 
                WHERE r.token = :token AND r.confirmada = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $reserva_id = $row['reserva_id'];
            $habitacion_id = $row['habitacion_id'];
            $fecha_confirmacion = date('Y-m-d H:i:s'); // Fecha actual de confirmación
    
            // Actualizar la reserva como confirmada y agregar la fecha
            $update_reserva_sql = "UPDATE reservas SET confirmada = 1, fecha = :fecha WHERE id = :id";
            $update_reserva_stmt = $this->db->prepare($update_reserva_sql);
            $update_reserva_stmt->bindParam(':id', $reserva_id, PDO::PARAM_INT);
            $update_reserva_stmt->bindParam(':fecha', $fecha_confirmacion, PDO::PARAM_STR);
    
            if ($update_reserva_stmt->execute()) {
                // Actualizar la disponibilidad de la habitación asociada
                $update_habitacion_sql = "UPDATE habitaciones SET disponible = 0 WHERE id = :habitacion_id";
                $update_habitacion_stmt = $this->db->prepare($update_habitacion_sql);
                $update_habitacion_stmt->bindParam(':habitacion_id', $habitacion_id, PDO::PARAM_INT);
    
                if ($update_habitacion_stmt->execute()) {
                    return true;
                } else {
                    throw new Exception("Error al actualizar la disponibilidad de la habitación.");
                }
            } else {
                throw new Exception("Error al confirmar la reserva.");
            }
        } else {
            return false; // Token inválido o ya confirmado
        }
    }
    public function obtenerReservas($usuario_id = null, $es_admin = false) {
        if ($es_admin) {
            // Si es admin, mostrar todas las reservas
            $sql = "
                SELECT r.id, r.fecha, r.confirmada, h.nombre AS habitacion, h.descripcion, h.precio, u.nombre_usuario
                FROM reservas r
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN usuarios u ON r.usuario_id = u.id
            ";
        } else {
            // Si es un usuario normal, mostrar solo sus propias reservas
            $sql = "
                SELECT r.id, r.fecha, r.confirmada, h.nombre AS habitacion, h.descripcion, h.precio
                FROM reservas r
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                WHERE r.usuario_id = :usuario_id
            ";
        }

        $stmt = $this->db->prepare($sql);

        // Si no es admin, bindear el usuario_id
        if (!$es_admin) {
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar una reserva
    public function eliminarReserva($reserva_id, $usuario_id, $es_admin) {
        // Primero obtenemos la habitación asociada a la reserva
        $sql = "SELECT habitacion_id FROM reservas WHERE id = :reserva_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
        $stmt->execute();
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Si la reserva no existe, devolvemos false
        if (!$reserva) {
            return false;
        }
    
        // Si es admin, puede eliminar cualquier reserva
        if ($es_admin) {
            $sql = "DELETE FROM reservas WHERE id = :reserva_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            $resultado = $stmt->execute();
        } else {
            // Si es un usuario normal, solo puede eliminar su propia reserva
            $sql = "DELETE FROM reservas WHERE id = :reserva_id AND usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $resultado = $stmt->execute();
        }
    
        // Si la eliminación fue exitosa, actualizamos la disponibilidad de la habitación
        if ($resultado) {
            $sql = "UPDATE habitaciones SET disponible = 1 WHERE id = :habitacion_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':habitacion_id', $reserva['habitacion_id'], PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
        return false;
    }
    
    

}
?>
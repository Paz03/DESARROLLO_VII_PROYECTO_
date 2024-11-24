<?php
require_once 'config.php';

class Administracion
{
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function obtenerHabitaciones()
    {
        try {
            // Corregimos el nombre de la columna a 'fecha'
            $query = $this->db->query("SELECT id, nombre, descripcion, precio, imagen, disponible FROM habitaciones");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener habitaciones: " . $e->getMessage());
        }
    }

    public function filtrarHabitaciones($nombre, $precioMinimo, $precioMaximo, $soloDisponibles)
    {
        try {
            $query = "SELECT * FROM habitaciones WHERE 1=1";
            $params = [];
    
            // Filtrar por nombre (si está definido)
            if (!empty($nombre)) {
                $query .= " AND nombre LIKE :nombre";
                $params[':nombre'] = '%' . $nombre . '%';
            }
    
            // Filtrar por precio mínimo (si está definido)
            if (!empty($precioMinimo)) {
                $query .= " AND precio >= :precio_min";
                $params[':precio_min'] = $precioMinimo;
            }
    
            // Filtrar por precio máximo (si está definido)
            if (!empty($precioMaximo)) {
                $query .= " AND precio <= :precio_max";
                $params[':precio_max'] = $precioMaximo;
            }
    
            // Filtrar por disponibilidad (si está marcado)
            if ($soloDisponibles) {
                $query .= " AND disponible = 1";
            }
    
            // Preparar y ejecutar la consulta
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al filtrar habitaciones: " . $e->getMessage());
        }
    }
    
    public function agregarHabitacion($nombre, $descripcion, $precio, $imagen, $disponible)
    {
        try {
            $query = $this->db->prepare("INSERT INTO habitaciones (nombre, descripcion, precio, imagen, disponible) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$nombre, $descripcion, $precio, $imagen, $disponible]);
        } catch (PDOException $e) {
            die("Error al agregar la habitación: " . $e->getMessage());
        }
    }

    public function editarHabitacion($id, $nombre, $descripcion, $precio, $imagen, $disponible)
    {
        try {
            $query = $this->db->prepare(
                "UPDATE habitaciones SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, disponible = ? WHERE id = ?"
            );
            $query->execute([$nombre, $descripcion, $precio, $imagen, $disponible, $id]);
        } catch (PDOException $e) {
            die("Error al editar la habitación: " . $e->getMessage());
        }
    }

    public function eliminarHabitacion($id)
    {
        try {
            $query = $this->db->prepare("DELETE FROM habitaciones WHERE id = ?");
            $query->execute([$id]);
        } catch (PDOException $e) {
            die("Error al eliminar la habitación: " . $e->getMessage());
        }
    }

    public function obtenerUsuarios()
    {
        try {
            $query = $this->db->query("SELECT id, nombre_usuario, correo_electronico, contrasena, confirmado, permiso FROM usuarios");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener usuarios: " . $e->getMessage());
        }
    }

    // Filtrar usuarios por nombre, correo o permiso
    public function filtrarUsuarios($nombre_usuario, $correo_electronico, $permiso)
    {
        try {
            $query = "SELECT * FROM usuarios WHERE 1=1";
            $params = [];

            if (!empty($nombre_usuario)) {
                $query .= " AND nombre_usuario LIKE :nombre_usuario";
                $params[':nombre_usuario'] = '%' . $nombre_usuario . '%';
            }

            if (!empty($correo_electronico)) {
                $query .= " AND correo_electronico LIKE :correo_electronico";
                $params[':correo_electronico'] = '%' . $correo_electronico . '%';
            }

            if (!empty($permiso)) {
                $query .= " AND permiso = :permiso";
                $params[':permiso'] = $permiso;
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al filtrar usuarios: " . $e->getMessage());
        }
    }

    // Modificar un usuario
    public function modificarUsuario($id, $nombre_usuario, $correo_electronico, $confirmado, $permiso)
    {
        try {
            // Validar que el permiso sea correcto
            if (!in_array($permiso, ['usuario', 'admin'])) {
                throw new Exception("Error: Valor de permiso inválido.");
            }

            $query = $this->db->prepare("UPDATE usuarios SET nombre_usuario = ?, correo_electronico = ?, confirmado = ?, permiso = ? WHERE id = ?");
            $query->execute([$nombre_usuario, $correo_electronico, $confirmado, $permiso, $id]);
        } catch (PDOException $e) {
            die("Error al modificar el usuario: " . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Eliminar un usuario
    public function eliminarUsuario($id)
    {
        try {
            $query = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
            $query->execute([$id]);
        } catch (PDOException $e) {
            die("Error al eliminar el usuario: " . $e->getMessage());
        }
    }
}

?>
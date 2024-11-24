CREATE DATABASE hotel;

USE hotel;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50),
    correo_electronico VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255),
    google_id VARCHAR(255) UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token VARCHAR(255) NULL,
    confirmado BOOLEAN DEFAULT 0,
    permiso ENUM('usuario', 'admin') DEFAULT 'usuario'  -- Campo para permiso con valores 'usuario' o 'admin'
);

CREATE TABLE habitaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT,
    precio DECIMAL(10, 2),
    imagen VARCHAR(255),
    disponible BOOLEAN DEFAULT 1,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    habitacion_id INT,
    fecha DATETIME,
    token VARCHAR(255) NOT NULL,
    confirmada TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id)
);

SELECT * FROM usuarios
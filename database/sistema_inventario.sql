-- Base de datos: sistema_inventario
CREATE DATABASE IF NOT EXISTS sistema_inventario;
USE sistema_inventario;
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','vendedor') NOT NULL DEFAULT 'vendedor',
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0,
  categoria VARCHAR(100) DEFAULT NULL,
  imagen VARCHAR(255) DEFAULT NULL,
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  cliente VARCHAR(200),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS detalle_ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT,
  producto_id INT,
  cantidad INT,
  precio_unitario DECIMAL(10,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Nota: el script 'config/db.php' crea una cuenta admin por defecto (admin@demo.com / admin123) en la primera carga del sistema.

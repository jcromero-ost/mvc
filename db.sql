CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('webmaster', 'gerencia', 'tecnico') DEFAULT 'tecnico',
    foto VARCHAR(255) DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuario administrador de prueba (password: admin123)
INSERT INTO usuarios (nombre, email, password, rol)
VALUES (
    'Admin General',
    'admin@intranet.local',
    '$2y$10$4y3ab6AjzWkNTswc4zM4P.EEC2OLoBrO8NkU.5fPSrb7De6cChU/y', -- hash de 'admin123'
    'webmaster'
);


CREATE TABLE IF NOT EXISTS departamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

ALTER TABLE usuarios ADD departamento_id INT,
ADD FOREIGN KEY (departamento_id) REFERENCES departamentos(id);

CREATE TABLE permisos_especiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  permiso VARCHAR(100) NOT NULL,
  valor BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

ALTER TABLE usuarios 
ADD alias VARCHAR(100),
ADD telefono VARCHAR(50),
ADD fecha_ingreso DATE,
ADD activo BOOLEAN DEFAULT 1,
ADD foto VARCHAR(255);

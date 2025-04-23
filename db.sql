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

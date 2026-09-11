CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    categoria VARCHAR(80) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    precio DECIMAL(10,2) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nombre, usuario, password)
VALUES ('Administrador', 'admin', SHA2('admin123', 256));

INSERT INTO productos (nombre, sku, categoria, stock, precio) VALUES
('Café molido 500g', 'CAF-500-01', 'Alimentos', 48, 8.50),
('Leche entera 1L', 'LAC-001-02', 'Lácteos', 12, 1.95),
('Arroz premium 1kg', 'ARR-001-03', 'Alimentos', 76, 3.20);
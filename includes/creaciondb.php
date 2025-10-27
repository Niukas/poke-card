<?php
require 'db.php';

// sentencia de sql
$sqlscript = "CREATE TABLE Avatares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    url_imagen VARCHAR(255) NOT NULL
);

CREATE TABLE Usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    id_avatar INT,
    sobres_disponibles INT NOT NULL DEFAULT 0,
    ultima_recarga_sobres TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_avatar) REFERENCES Avatares(id)
);

CREATE TABLE Tipos (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Rarezas (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Pokemon (
    id INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    id_tipo INT NOT NULL,
    id_rareza INT NOT NULL,
    puntos_coleccion INT NOT NULL DEFAULT 1,
    url_imagen VARCHAR(255),
    ALTER TABLE Pokemon,
    vida INT NOT NULL DEFAULT 0,
    ataque INT NOT NULL DEFAULT 0,
    defensa INT NOT NULL DEFAULT 0,
    velocidad INT NOT NULL DEFAULT 0;
    FOREIGN KEY (id_tipo) REFERENCES Tipos(id),
    FOREIGN KEY (id_rareza) REFERENCES Rarezas(id)
);

CREATE TABLE Coleccion (
    id_usuario INT,
    id_carta INT,
    cantidad INT NOT NULL DEFAULT 1,
    PRIMARY KEY (id_usuario, id_carta),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_carta) REFERENCES Pokemon(id) ON DELETE CASCADE
);

CREATE TABLE Logros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    recompensa_sobres INT NOT NULL DEFAULT 0
);

CREATE TABLE Logros_Usuario (
    id_usuario INT,
    id_logro INT,
    fecha_obtenido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reclamada BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (id_usuario, id_logro),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_logro) REFERENCES Logros(id) ON DELETE CASCADE
);

INSERT INTO Rarezas (id, nombre) VALUES
(1, 'Común'),
(2, 'Poco Común'),
(3, 'Rara'),
(4, 'Épica'),
(5, 'Legendaria');

INSERT INTO Tipos (id, nombre) VALUES
(1, 'Fuego'), (2, 'Agua'), (3, 'Planta'), (4, 'Eléctrico'), (5, 'Hielo'),
(6, 'Roca'), (7, 'Tierra'), (8, 'Volador'), (9, 'Veneno'), (10, 'Lucha'),
(11, 'Fantasma'), (12, 'Dragón'), (13, 'Bicho'), (14, 'Psíquico'), (15, 'Normal');

INSERT INTO Avatares (id, url_imagen) VALUES
(1, '/img/avatares/20.png');
";

try {
    $pdo->exec($sqlscript);
    echo "Tablas creadas";
} catch (PDOException $e) {
    echo "error" . $e->getMessage();
}

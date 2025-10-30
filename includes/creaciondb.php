<?php
// Asume que $pdo ya existe gracias a 'require db.php'
// y que está conectado a la base de datos 'poke_card'.
require 'db.php';

try {
    // --- Crear Tablas ---
    $pdo->exec("CREATE TABLE `avatares` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `url_imagen` varchar(255) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE `logros` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nombre` varchar(150) NOT NULL,
      `descripcion` text DEFAULT NULL,
      `recompensa_sobres` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE `rarezas` (
      `id` int(11) NOT NULL,
      `nombre` varchar(50) NOT NULL,
      `puntos_coleccion` int(11) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nombre` (`nombre`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE `tipos` (
      `id` int(11) NOT NULL,
      `nombre` varchar(50) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nombre` (`nombre`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // --- Crear Tablas que dependen de las anteriores ---
    $pdo->exec("CREATE TABLE `pokemon` (
      `id` int(11) NOT NULL,
      `nombre` varchar(100) NOT NULL,
      `id_tipo` int(11) NOT NULL,
      `id_rareza` int(11) NOT NULL,
      `url_imagen` varchar(255) DEFAULT NULL,
      `hp` int(11) NOT NULL DEFAULT 0,
      `ataque` int(11) NOT NULL DEFAULT 0,
      `defensa` int(11) NOT NULL DEFAULT 0,
      `velocidad` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nombre` (`nombre`),
      KEY `id_tipo` (`id_tipo`),
      KEY `id_rareza` (`id_rareza`),
      CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`),
      CONSTRAINT `pokemon_ibfk_2` FOREIGN KEY (`id_rareza`) REFERENCES `rarezas` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE `usuarios` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nombre` varchar(100) NOT NULL,
      `contrasena` varchar(255) NOT NULL,
      `rol` varchar(20) NOT NULL DEFAULT 'jugador',
      `id_avatar` int(11) DEFAULT 20,
      `sobres_disponibles` int(11) NOT NULL DEFAULT 5,
      `ultima_recarga_sobres` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `nombre` (`nombre`),
      KEY `id_avatar` (`id_avatar`),
      CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_avatar`) REFERENCES `avatares` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // --- Crear el resto de tablas ---
    $pdo->exec("CREATE TABLE `coleccion` (
      `id_usuario` int(11) NOT NULL,
      `id_carta` int(11) NOT NULL,
      `cantidad` int(11) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id_usuario`,`id_carta`),
      KEY `id_carta` (`id_carta`),
      CONSTRAINT `coleccion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
      CONSTRAINT `coleccion_ibfk_2` FOREIGN KEY (`id_carta`) REFERENCES `pokemon` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE `logros_usuario` (
      `id_usuario` int(11) NOT NULL,
      `id_logro` int(11) NOT NULL,
      `fecha_obtenido` timestamp NOT NULL DEFAULT current_timestamp(),
      `reclamada` tinyint(1) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id_usuario`,`id_logro`),
      KEY `id_logro` (`id_logro`),
      CONSTRAINT `logros_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
      CONSTRAINT `logros_usuario_ibfk_2` FOREIGN KEY (`id_logro`) REFERENCES `logros` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // --- Insertar todos los datos ---
    $pdo->exec("INSERT INTO `avatares` (`id`, `url_imagen`) VALUES
    (1, '/poke-card/img/avatares/1.png'),(2, '/poke-card/img/avatares/2.png'),(3, '/poke-card/img/avatares/3.png'),(4, '/poke-card/img/avatares/4.png'),(5, '/poke-card/img/avatares/5.png'),(6, '/poke-card/img/avatares/6.png'),(7, '/poke-card/img/avatares/7.png'),(8, '/poke-card/img/avatares/8.png'),(9, '/poke-card/img/avatares/9.png'),(10, '/poke-card/img/avatares/10.png'),(11, '/poke-card/img/avatares/11.png'),(12, '/poke-card/img/avatares/12.png'),(13, '/poke-card/img/avatares/13.png'),(14, '/poke-card/img/avatares/14.png'),(15, '/poke-card/img/avatares/15.png'),(16, '/poke-card/img/avatares/16.png'),(17, '/poke-card/img/avatares/17.png'),(18, '/poke-card/img/avatares/18.png'),(19, '/poke-card/img/avatares/19.png'),(20, '/poke-card/img/avatares/20.png');");
    
    $pdo->exec("INSERT INTO `rarezas` (`id`, `nombre`, `puntos_coleccion`) VALUES
    (1, 'Común', 5),(2, 'PocoComún', 10),(3, 'Rara', 50),(4, 'Épica', 200),(5, 'Legendaria', 1000);");

    $pdo->exec("INSERT INTO `tipos` (`id`, `nombre`) VALUES
    (1, 'Fuego'), (2, 'Agua'), (3, 'Planta'), (4, 'Eléctrico'), (5, 'Hielo'),(6, 'Roca'), (7, 'Tierra'), (8, 'Volador'), (9, 'Veneno'), (10, 'Lucha'),(11, 'Fantasma'), (12, 'Dragón'), (13, 'Bicho'), (14, 'Psíquico'), (15, 'Normal');");

    $pdo->exec("INSERT INTO `pokemon` (`id`, `nombre`, `id_tipo`, `id_rareza`, `url_imagen`, `hp`, `ataque`, `defensa`, `velocidad`) VALUES
    (4, 'Charmander', 1, 2, './img/cartas/004.gif', 39, 52, 43, 65),
    (25, 'Pikachu', 4, 1, './img/cartas/025.gif', 35, 55, 40, 90),
    (37, 'Vulpix', 1, 3, './img/cartas/037.gif', 38, 41, 40, 65),
    (143, 'Snorlax', 15, 4, './img/cartas/143.gif', 160, 110, 65, 30),
    (150, 'Mewtwo', 14, 5, './img/cartas/150.gif', 106, 110, 90, 130);");

    echo "Base de datos creada";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
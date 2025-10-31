<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

// En la sentencia sumo los puntos de la rareza multiplicados por la cantidad
$sqlRanking = "SELECT 
    Usuarios.nombre,
    Avatares.url_imagen,
    SUM(Rarezas.puntos_coleccion * Coleccion.cantidad) AS total_puntos
FROM 
    Usuarios
JOIN 
    Coleccion ON Usuarios.id = Coleccion.id_usuario
JOIN 
    Pokemon ON Coleccion.id_carta = Pokemon.id
JOIN 
    Rarezas ON Pokemon.id_rareza = Rarezas.id
JOIN 
    Avatares ON Usuarios.id_avatar = Avatares.id
GROUP BY 
    Usuarios.id, Usuarios.nombre, Avatares.url_imagen
ORDER BY
    total_puntos DESC LIMIT 20;";


try {
    $stmt = $pdo->query($sqlRanking);
    $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error " . $e->getMessage());
    $ranking = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - PokeCard</title>
    <link rel="icon" type="image/x-icon" href="/poke-card/img/ui/favicon.ico">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/head_footer_style.css">
    <link rel="stylesheet" href="https://use.typekit.net/sdy1dik.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require './includes/header.php'; ?>
    <main>
        <section class="contenedorRanking">
            <h2>Ranking Global</h2>
            <table class="tablaRanking">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $puesto = 1;
                    foreach ($ranking as $jugador):
                    ?>
                        <tr>
                            <td class="columnaPuesto"><?= $puesto++ ?></td>
                            <td class="columnaUsuario">
                                <img src="<?= $jugador['url_imagen'] ?>" alt="Avatar">
                                <span><?= $jugador['nombre'] ?></span>
                            </td>
                            <td class="columnaPuntaje">
                                <?= number_format($jugador['total_puntos']) ?> Pts
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($ranking)): ?>
                        <tr>
                            <td colspan="3">Nadie ha entrado al ranking todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
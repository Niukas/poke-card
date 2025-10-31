<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

$idUsuario = $_SESSION['idUsuario'];

$sql = "SELECT 
    pokemon.*, 
    coleccion.cantidad,
    Tipos.nombre AS nombre_tipo,
    Rarezas.nombre AS nombre_rareza
FROM 
    pokemon
LEFT JOIN 
    coleccion ON pokemon.id = coleccion.id_carta AND coleccion.id_usuario = ?
JOIN 
    Tipos ON pokemon.id_tipo = Tipos.id
JOIN 
    Rarezas ON pokemon.id_rareza = Rarezas.id
WHERE 
    pokemon.id <= 151
ORDER BY 
    pokemon.id ASC";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);

    $todasLasCartas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colección - PokeCard</title>
    <link rel="icon" type="image/x-icon" href="/poke-card/img/ui/favicon.ico">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/head_footer_style.css">
    <link rel="stylesheet" href="./css/cartas_style.css">
    <link rel="stylesheet" href="https://use.typekit.net/sdy1dik.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require './includes/header.php'; ?>
    <main>
        <section class="contenedorColeccion">
            <h2>Mi colección</h2>
            <section class="contenedorCartasColeccion">
                <?php foreach ($todasLasCartas as $carta):
                    if ($carta['cantidad'] > 0):
                        $cartas = $carta;
                        include './includes/cartaTemplate.php';
                    else:
                ?>
                        <div class="cartaFaltante">
                            <span class="cartaFaltanteID">#<?= $carta['id'] ?></span>
                            <?php include './img/ui/sobres/cartaFaltante.svg';
                            ?>
                        </div>
                <?php
                    endif;
                endforeach;
                ?>
            </section>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
<?php
session_start();

$cartasEjemplo = [
    [
        'id' => 4,
        'nombre' => 'CHARMANDER',
        'url_imagen' => './img/cartas/004.gif',
        'ataque' => 52,
        'defensa' => 43,
        'velocidad' => 65,
        'hp' => 39,
        'nombre_tipo' => 'Fuego',
        'nombre_rareza' => 'Rara'
    ],
    [
        'id' => 25,
        'nombre' => 'PIKACHU',
        'url_imagen' => './img/cartas/025.gif',
        'ataque' => 55,
        'defensa' => 40,
        'velocidad' => 90,
        'hp' => 35,
        'nombre_tipo' => 'Eléctrico',
        'nombre_rareza' => 'Rara'
    ],
    [
        'id' => 151,
        'nombre' => 'MEW',
        'url_imagen' => './img/cartas/151.gif',
        'ataque' => 100,
        'defensa' => 100,
        'velocidad' => 100,
        'hp' => 100,
        'nombre_tipo' => 'Psíquico',
        'nombre_rareza' => 'Legendaria'
    ],
    [
        'id' => 144,
        'nombre' => 'ARTICUNO',
        'url_imagen' => './img/cartas/144.gif',
        'ataque' => 85,
        'defensa' => 100,
        'velocidad' => 85,
        'hp' => 90,
        'nombre_tipo' => 'Hielo',
        'nombre_rareza' => 'Legendaria'
    ]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeCard</title>
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
    <?php require './includes/header.php' ?>
    <main>
        <section class="contenedorIndex">
            <h2>Colecciónalos a Todos, ¡Ahora en la Web!</h2>
            <p>Abre sobres diarios, colecciona los 151 Pokémon originales y completa tu Pokédex virtual.</p>
            <section class="contenedorCartas">
                <?php foreach ($cartasEjemplo as $cartas):
                    include './includes/cartaTemplate.php';
                endforeach; ?>
            </section>
            <a href="registro.php" class="boton rojo">Comenzar tu Colección!</a>
        </section>
    </main>
    <?php require './includes/footer.php' ?>
</body>

</html>
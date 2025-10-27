<?php
session_start();
$nombre = 'Niuka';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PokeCard</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/head_footer_style.css">
    <link rel="stylesheet" href="https://use.typekit.net/sdy1dik.css">
</head>
<body>
    <?= require './includes/header.php';?>
    <main>
        <section class="contenedorLogin">
            <h2>Inicio Sesion</h2>

        </section>
    </main>
    <?= require './includes/footer.php';?>
</body>
</html>
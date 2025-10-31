<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}
// guardo las cartas en una variable
$cartasMostrar = $_SESSION['cartasRecientes'];

unset($_SESSION['cartasRecientes']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartas Ganadas! - PokeCard</title>
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
        <section class="contenedorDashboard">
            <p class="texto">!Tus nuevas Cartas!</p>
            <section class="contenedorCartas">
                <?php foreach ($cartasMostrar as $cartas): ?>
                    <div class="cartaConEstado">
                        <?php if ($cartas['nueva']): ?>
                            <span class="etiquetaNueva">¡NUEVA!</span>
                        <?php endif; ?>
                        <?php
                        include './includes/cartaTemplate.php';
                        ?>
                    </div>
                <?php endforeach; ?>
            </section>
            <section class="botones">
                <form action="./scripts/procesar_abrir_sobre.php" method="post">
                    <button type="submit" class="boton abrir sobres">
                        Abrir otro sobre
                    </button>
                </form>
                <a href="dashboard.php" class="boton abrir sobres">Volver al Inicio</a>
            </section>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
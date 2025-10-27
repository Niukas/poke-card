<?php
$nombre = "Niuka";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PokeCard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/head_footer_style.css">
    <link rel="stylesheet" href="https://use.typekit.net/sdy1dik.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="contenedorAdmin">
        <?php require '../includes/headerAdmin.php' ?>
        <main>
            <section class="stats">
                <ul class="ulStats">
                    <li class="liStats">
                        Hola <?= $nombre ?>
                    </li>
                    <li class="liStats">Cartas Totales abiertas: 500</li>
                    <li class="liStats">Cartas abiertas hoy: 100</li>
                    <li class="liStats">Sobres Totales abiertos: 100</li>
                    <li class="liStats">Usuarios registrados: 15</li>
                </ul>
            </section>
        </main>
    </div>
    <?php require '../includes/footer.php' ?>
</body>

</html>
<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] != 'admin') {
    header('Location: ../dashboard.php'); 
    exit;
}

require_once '../includes/db.php';

$totalUsuarios = 0;
$totalCartas = 0;
$totalSobres = 0;

try {
    $sqlStats = "SELECT
                (SELECT COUNT(id) FROM usuarios) AS total_usuarios,
                (SELECT SUM(cantidad) FROM coleccion) AS total_cartas,
                (SELECT SUM(sobres_abiertos_total) FROM usuarios) AS total_sobres";
    
    $stmt = $pdo->query($sqlStats);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalUsuarios = $stats['total_usuarios'];
    $totalCartas = $stats['total_cartas'];
    $totalSobres = $stats['total_sobres'];

} catch (PDOException $e) {
    echo "Error " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PokeCard</title>
    <link rel="icon" type="image/x-icon" href="/poke-card/img/ui/favicon.ico">
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
                        Hola <?= $_SESSION['nombreUsuario'] ?> :D
                    </li>
                    <li class="liStats">Cartas Totales abiertas: <?= $totalCartas ?></li>
                    <li class="liStats">Sobres Totales abiertos: <?= $totalSobres ?></li>
                    <li class="liStats">Usuarios registrados: <?= $totalUsuarios?></li>
                </ul>
            </section>
        </main>
    </div>
    <?php require '../includes/footer.php' ?>
</body>

</html>
<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';
$idUsuario = $_SESSION['idUsuario'];

// Obtengo los logros del usuario en una lista
$sqlLogros = "SELECT 
    logros.id, 
    logros.nombre, 
    logros.descripcion, 
    logros.recompensa_sobres,
    logros_usuario.fecha_obtenido, 
    logros_usuario.reclamada
FROM 
    logros
LEFT JOIN 
    logros_usuario ON logros.id = logros_usuario.id_logro AND logros_usuario.id_usuario = ?
ORDER BY 
    logros.id ASC";


try {
    $stmt = $pdo->prepare($sqlLogros);
    $stmt->execute([$idUsuario]);
    $listaLogros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error " . $e->getMessage());
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
        <section class="contenedorLogros">
            <h2>Ranking Global</h2>
            <?php foreach ($listaLogros as $logro): ?>
                <?php
                // --- Determinamos el estado del logro ---
                $claseCss = '';
                $puedeReclamar = false;

                if ($logro['fecha_obtenido'] == NULL) {
                    $claseCss = 'logroBloqueado';
                } else if ($logro['reclamada'] == 0) {
                    // Lo tiene Y no lo ha reclamado
                    $claseCss = 'logroReclamable';
                    $puedeReclamar = true;
                } else {
                    // Lo tiene Y ya lo reclamo
                    $claseCss = 'logroReclamado';
                }
                ?>
                <div class="logroItem <?= $claseCss ?>">
                    <h3><?= $logro['nombre'] ?></h3>
                    <p><?= $logro['descripcion'] ?> Recompensa: <?= $logro['recompensa_sobres'] ?> sobres </p>
                    <?php if ($puedeReclamar): ?>
                        <form action="scripts/reclamar_logros.php" method="POST">
                            <input type="hidden" name="idLogroAReclamar" value="<?= $logro['id'] ?>">
                            <button type="submit" class="boton">Reclamar</button>
                        </form>
                    <?php endif;
                    if (isset($_SESSION['mensaje_exito'])) {
                        echo '<p class="exito">' . $_SESSION['mensaje_exito'] . '</p>';
                        unset($_SESSION['mensaje_exito']);
                    }
                    if (isset($_SESSION['mensaje_error'])) {
                        echo '<p class="error">' . $_SESSION['mensaje_error'] . '</p>';
                        unset($_SESSION['mensaje_error']);
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
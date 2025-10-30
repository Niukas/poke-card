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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require './includes/header.php'; ?>
    <main>
        <form action="scripts/procesar_login.php" method="POST" class="contenedorLogin">
            <h2>Incio Sesion</h2>
            <label for="user">Usuario:</label>
            <input type="text" name="user" id="usuario">
            <label for="contra">Contraseña:</label>
            <input type="password" name="password" id="contrasena">
            <button type="submit" class="boton">Enviar</button>
            <?php if (isset($_SESSION['mensaje_error'])) {
                echo '<p class="error">' . $_SESSION['mensaje_error'] . '</p>';
                unset($_SESSION['mensaje_error']);
            }
            ?>
        </form>
    </main>
    <?php require './includes/footer.php'; ?>
</body>

</html>
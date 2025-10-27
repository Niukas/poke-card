<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PokeCard</title>
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
        <form action="scripts/procesar_registro.php" method="POST" class="contenedorRegistro">
            <h2>Registro</h2>
            <label for="user">Usuario:</label>
            <input type="text" name="user" id="usuario">
            <label for="contra">Contraseña:</label>
            <input type="password" name="password" id="contrasena">
            <label for="rpassword">Contraseña:</label>
            <input type="password" name="rpassword" id="rcontrasena">
            <button type="submit" class="boton">Registro!</button>
            <?php if (isset($_SESSION['mensaje_exito_registro'])) {
                echo '<p class="exito">' . $_SESSION['mensaje_exito_registro'] . '</p>';
                unset($_SESSION['mensaje_exito_registro']);
            }
            if (isset($_SESSION['mensaje_error_registro'])) {
                echo '<p class="error">' . $_SESSION['mensaje_error_registro'] . '</p>';
                unset($_SESSION['mensaje_error_registro']);
            }
            ?>
        </form>
    </main>
    <?php require './includes/footer.php'; ?>
</body>

</html>
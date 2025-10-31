<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

try {
    $stmt = $pdo->query("SELECT id, url_imagen FROM Avatares ORDER BY id ASC");
    $listaAvatares = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $listaAvatares = []; // Si hay un error dejo la lista vacía
    echo "Error al cargar los avatares: " . $e->getMessage();
}

$idAvatarActual = $_SESSION['idAvatar'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes de Usuario - PokeCard</title>
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
        <section class="contenedorAjustes">
            <form action="./scripts/procesar_ajustes_avatar.php" method="POST" class="formAjustes">
                <h1>Ajustes de Usuario</h1>
                <label for="">Avatar</label>

                <div class="selectorAvatar">

                    <?php
                    foreach ($listaAvatares as $avatar):
                    ?>
                        <div class="opcionAvatar">
                            <input type="radio"
                                name="avatarId"
                                id="avatar<?= $avatar['id'] ?>"
                                value="<?= $avatar['id'] ?>"
                                class="input-avatar"

                                <?php
                                if ($idAvatarActual == $avatar['id']) {
                                    echo 'checked'; // si es el mismo que el usuario le marco checked
                                }
                                ?>>
                            <label for="avatar<?= $avatar['id'] ?>">
                                <img src="<?= htmlspecialchars($avatar['url_imagen']) ?>" alt="Avatar <?= $avatar['id'] ?>">
                            </label>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
                <div>

                </div>
                <button type="submit" class="boton">Guardar Avatar</button>
                <?php if (isset($_SESSION['mensaje_error'])) {
                    echo '<p class="error">' . $_SESSION['mensaje_error'] . '</p>';
                    unset($_SESSION['mensaje_error']);
                }
                ?>
            </form>
            <form action="./scripts/procesar_ajustes_nombre.php" method="post" class="formCambioNombre">
                <label for="nuevoNombre">Cambiar nombre de Usuario</label>
                <input type="text" name="nuevoNombre" placeholder="Nuevo nombre de Usuario">
                <button type="submit" class="boton">Enviar</button>
                <?php if (isset($_SESSION['mensaje_exito_usuario'])) {
                    echo '<p class="exito">' . $_SESSION['mensaje_exito_usuario'] . '</p>';
                    unset($_SESSION['mensaje_exito_usuario']);
                }
                if (isset($_SESSION['mensaje_error_usuario'])) {
                    echo '<p class="error">' . $_SESSION['mensaje_error_usuario'] . '</p>';
                    unset($_SESSION['mensaje_error_usuario']);
                }
                ?>
            </form>
            <form action="./scripts/procesar_ajustes_pass.php" method="post" class="formCambioContra">
                <label for="contraNueva">Cambiar Contraseña</label>
                <input type="password" name="contraNueva" id="contra1" placeholder="Nueva contraseña">
                <input type="password" name="contraNuevaRepetida" id="contra2" placeholder="Repite la contraseña">
                <button type="submit" class="boton">Enviar</button>
                <?php if (isset($_SESSION['mensaje_exito_contra'])) {
                    echo '<p class="exito">' . $_SESSION['mensaje_exito_contra'] . '</p>';
                    unset($_SESSION['mensaje_exito_contra']);
                }
                if (isset($_SESSION['mensaje_error_contra'])) {
                    echo '<p class="error">' . $_SESSION['mensaje_error_contra'] . '</p>';
                    unset($_SESSION['mensaje_error_contra']);
                }
                ?>
            </form>
            <a href="./scripts/procesar_logout.php" class="boton abrir">Cerrar Sesión</a>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
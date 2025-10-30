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

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PokeCard</title>
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
        <h1>Ajustes de Usuario</h1>
        <form action="" method="POST" class="contenedorAjustes">
            <h2>Avatar</h2>

            <div class="selectorAvatar">

                <?php
                foreach ($listaAvatares as $avatar):
                ?>
                    <div class="opcionAvatar">
                        <input type="radio"
                            name="avatarId"
                            id="avatar-<?= $avatar['id'] ?>"
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
            <button type="submit" class="boton">Guardar Avatar</button>
            <?php if (isset($_SESSION['mensaje_error'])) {
                echo '<p class="error">' . $_SESSION['mensaje_error'] . '</p>';
                unset($_SESSION['mensaje_error']);
            }
            ?>
        </form>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
</body>

</html>
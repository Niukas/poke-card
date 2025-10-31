<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';
$paginaActual = basename($_SERVER['SCRIPT_NAME']);


// Consulta para rellenar los selects de los formularios
try {
    $sqlLista = "SELECT id, nombre FROM pokemon ORDER by id ASC";
    $stmtLista = $pdo->query($sqlLista);
    $listaPokemon = $stmtLista->fetchAll();

    $numeroPokemones = count($listaPokemon);
} catch (PDOException $e) {
    echo "Error al consultar pokemones" . $e;
}

$cartaAEditar = null;

// Verificacion si hay un id para editar pokemon y mostrar el formulario oculto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'cargar') {

    $idParaEditar = $_POST['listaPoke'];

    if (!empty($idParaEditar)) {
        // Buscamos la carta en la BD
        $sqlSelect = "SELECT * FROM pokemon WHERE id = ?";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->execute([$idParaEditar]);
        $cartaAEditar = $stmtSelect->fetch();
    }
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
            <section class="contenedorCrearCartas">
                <!-- Formulario para crear cartas -->
                <form action="crearCarta.php" method="post" class="crear-carta-form">
                    <h2>Crear</h2>

                    <label for="idPokemon">ID:</label>
                    <input type="text" name="idPokemon" id="idPokemon">

                    <label for="nombrePokemon">Nombre:</label>
                    <input type="text" name="nombrePokemon" id="nombrePokemon">

                    <label for="tipoPokemon">Tipo:</label>
                    <select name="tipoPokemon" id="tipoPokemon">
                        <option value="1">Fuego</option>
                        <option value="2">Agua</option>
                        <option value="3">Planta</option>
                        <option value="4">Electrico</option>
                        <option value="5">Hielo</option>
                        <option value="6">Roca</option>
                        <option value="7">Tierra</option>
                        <option value="8">Volador</option>
                        <option value="9">Veneno</option>
                        <option value="10">Lucha</option>
                        <option value="11">Fantasma</option>
                        <option value="12">Dragón</option>
                        <option value="13">Bicho</option>
                        <option value="14">Psíquico</option>
                        <option value="15">Normal</option>
                    </select>

                    <label for="rarezaPokemon">Rareza:</label>
                    <select name="rarezaPokemon" id="rareza">
                        <option value="1">Común</option>
                        <option value="2">Poco Común</option>
                        <option value="3">Rara</option>
                        <option value="4">Épica</option>
                        <option value="5">Legendaria</option>
                    </select>

                    <label for="stats">Stats:</label>
                    <div class="statsInputs">
                        <input type="number" name="attackPokemon" id="attackPokemon" placeholder="Atk" aria-label="Ataque">
                        <input type="number" name="defensePokemon" id="defensePokemon" placeholder="Def" aria-label="Defensa">
                        <input type="number" name="speedPokemon" id="speedPokemon" placeholder="Vel" aria-label="Velocidad">
                        <input type="number" name="hpPokemon" id="hpPokemon" placeholder="Hp" aria-label="Hp">
                    </div>

                    <label for="imagenPokemon">Imagen (url):</label>
                    <input type="text" name="imagenPokemon" id="imagenPokemon">

                    <button type="submit" class="boton">Crear</button>
                    <?php if (isset($_SESSION['mensaje_exito_crear'])) {
                        echo '<p class="exito">' . $_SESSION['mensaje_exito_crear'] . '</p>';
                        unset($_SESSION['mensaje_exito_crear']);
                    }
                    if (isset($_SESSION['mensaje_error_crear'])) {
                        echo '<p class="error">' . $_SESSION['mensaje_error_crear'] . '</p>';
                        unset($_SESSION['mensaje_error_crear']);
                    }
                    ?>
                </form>
                <section>
                    <!-- Formulario para editar cartas -->
                    <form action="" method="post" class="editar-carta-form">
                        <h2>Editar</h2>

                        <form action="gestionarCartas.php" method="POST" class="seleccionar-form">
                            <input type="hidden" name="accion" value="cargar">
                            <label for="idPokemon">Selecciona un Pokemon:</label>
                            <select name="listaPoke" id="idPokemon">
                                <?php
                                foreach ($listaPokemon as $pokemon) {
                                    echo '<option value="' . $pokemon['id'] . '">'
                                        . '#' . $pokemon['id'] . ' - ' . $pokemon['nombre']
                                        . '</option>';
                                }
                                ?>
                            </select>
                            <button type="submit" class="boton">Editar</button>
                            <?php if (isset($_SESSION['mensaje_exito_editar'])) {
                                echo '<p class="exito">' . $_SESSION['mensaje_exito_editar'] . '</p>';
                                unset($_SESSION['mensaje_exito_editar']);
                            }
                            if (isset($_SESSION['mensaje_error_editar'])) {
                                echo '<p class="error">' . $_SESSION['mensaje_error_editar'] . '</p>';
                                unset($_SESSION['mensaje_error_editar']);
                            }
                            ?>
                        </form>
                        <!-- Formulario Oculto hasta que se selecciona una carta a editar -->
                        <?php
                        if ($cartaAEditar):
                        ?>
                            <form action="editarCarta.php" method="POST" class="form-editar-completo">
                                <!-- Devuelvo en el formulario el valor ID del pokemon a editar -->
                                <input type="hidden" name="idPokemonOculto" value="<?= $cartaAEditar['id'] ?>">

                                <label for="nombrePokemon">Nombre:</label>
                                <input type="text" name="nombre" id="nombrePokemon" value="<?= $cartaAEditar['nombre'] ?>">

                                <label for="tipoPokemon">Tipo:</label>
                                <select name="tipos" id="tipoPokemon">
                                    <option value="1" <?php if ($cartaAEditar['id_tipo'] == 1) echo 'selected'; ?>>Fuego</option>
                                    <option value="2" <?php if ($cartaAEditar['id_tipo'] == 2) echo 'selected'; ?>>Agua</option>
                                    <option value="3" <?php if ($cartaAEditar['id_tipo'] == 3) echo 'selected'; ?>>Planta</option>
                                    <option value="4" <?php if ($cartaAEditar['id_tipo'] == 4) echo 'selected'; ?>>Electrico</option>
                                    <option value="5" <?php if ($cartaAEditar['id_tipo'] == 5) echo 'selected'; ?>>Hielo</option>
                                    <option value="6" <?php if ($cartaAEditar['id_tipo'] == 6) echo 'selected'; ?>>Roca</option>
                                    <option value="7" <?php if ($cartaAEditar['id_tipo'] == 7) echo 'selected'; ?>>Tierra</option>
                                    <option value="8" <?php if ($cartaAEditar['id_tipo'] == 8) echo 'selected'; ?>>Volador</option>
                                    <option value="9" <?php if ($cartaAEditar['id_tipo'] == 9) echo 'selected'; ?>>Veneno</option>
                                    <option value="10" <?php if ($cartaAEditar['id_tipo'] == 10) echo 'selected'; ?>>Lucha</option>
                                    <option value="11" <?php if ($cartaAEditar['id_tipo'] == 11) echo 'selected'; ?>>Fantasma</option>
                                    <option value="12" <?php if ($cartaAEditar['id_tipo'] == 12) echo 'selected'; ?>>Dragón</option>
                                    <option value="13" <?php if ($cartaAEditar['id_tipo'] == 13) echo 'selected'; ?>>Bicho</option>
                                    <option value="14" <?php if ($cartaAEditar['id_tipo'] == 14) echo 'selected'; ?>>Psíquico</option>
                                    <option value="15" <?php if ($cartaAEditar['id_tipo'] == 15) echo 'selected'; ?>>Normal</option>
                                </select>

                                <label for="rarezaPokemon">Rareza:</label>
                                <select name="rarezaPokemon" id="rareza">
                                    <option value="1" <?php if ($cartaAEditar['id_rareza'] == 1) echo 'selected'; ?>>Común</option>
                                    <option value="2" <?php if ($cartaAEditar['id_rareza'] == 2) echo 'selected'; ?>>Poco Común</option>
                                    <option value="3" <?php if ($cartaAEditar['id_rareza'] == 3) echo 'selected'; ?>>Rara</option>
                                    <option value="4" <?php if ($cartaAEditar['id_rareza'] == 4) echo 'selected'; ?>>Épica</option>
                                    <option value="5" <?php if ($cartaAEditar['id_rareza'] == 5) echo 'selected'; ?>>Legendaria</option>
                                </select>

                                <label for="stats">Stats:</label>
                                <div class="statsInputs">
                                    <input type="number" name="attackPokemon" id="attackPokemon" placeholder="Atk" aria-label="Ataque" value="<?= $cartaAEditar['ataque'] ?>">
                                    <input type="number" name="defensePokemon" id="defensePokemon" placeholder="Def" aria-label="Defensa" value="<?= $cartaAEditar['defensa'] ?>">
                                    <input type="number" name="speedPokemon" id="speedPokemon" placeholder="Vel" aria-label="Velocidad" value="<?= $cartaAEditar['velocidad'] ?>">
                                    <input type="number" name="hpPokemon" id="hpPokemon" placeholder="Hp" aria-label="Hp" value="<?= $cartaAEditar['hp'] ?>">
                                </div>

                                <label for="imagenPokemon">Imagen (url):</label>
                                <input type="text" name="imagenPokemon" id="imagenPokemon" value="<?= htmlspecialchars($cartaAEditar['url_imagen']) ?>">

                                <button type="submit" class="boton">Enviar</button>
                            </form>
                        <?php
                        endif; // Fin del if ($cartaAEditar)
                        ?>
                </section>
                <!-- Formulario para elimnar cartas -->
                <form action="eliminarCarta.php" method="POST" class="eliminar-carta-form">
                    <h2>Eliminar</h2>
                    <label for="elimarCarta">Selecciona:</label>
                    <select name="listaPokes" id="eliminarCarta">
                        <?php
                        foreach ($listaPokemon as $pokemon) {
                            echo '<option value="' . $pokemon['id'] . '">'
                                . '#' . $pokemon['id'] . ' - ' . $pokemon['nombre']
                                . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" class="boton rojo">Eliminar</button>
                    <?php if (isset($_SESSION['mensaje_exito_eliminar'])) {
                        echo '<p class="exito">' . $_SESSION['mensaje_exito_eliminar'] . '</p>';
                        unset($_SESSION['mensaje_exito_eliminar']);
                    }
                    if (isset($_SESSION['mensaje_error_eliminar'])) {
                        echo '<p class="error">' . $_SESSION['mensaje_error_eliminar'] . '</p>';
                        unset($_SESSION['mensaje_error_eliminar']);
                    }
                    ?>
                </form>
            </section>
        </main>
    </div>
    <?php require '../includes/footer.php' ?>
</body>

</html>
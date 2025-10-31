<header class="headerAdmin">
    <a href="./Index.php" class="enlaceInicio"><img src="/poke-card/img/ui/PokeCard.svg" alt="Logo de Poke-Card" class="logoPokeAdmin"></a>
    <a href="./gestionar_cartas.php" class="boton <?php if ($paginaActual == 'gestionar_cartas.php') { echo 'activo'; } ?>">Cartas</a>
    <a href="../ajustes.php" class="enlaceUsuario">
        <section class="usuario">
            <?= $_SESSION['nombreUsuario'] ?>
            <img src="<?= $_SESSION['avatar']; ?>" alt="Avatar de usuario">
        </section>
    </a>
</header>
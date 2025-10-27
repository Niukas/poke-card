<header class="headerAdmin">
    <a href="./Index.php" class="enlaceInicio"><img src="/poke-card/img/ui/PokeCard.svg" alt="Logo de Poke-Card" class="logoPokeAdmin"></a>
    <a href="./gestionar_cartas.php" class="boton <?php if ($paginaActual == 'gestionar_cartas.php') { echo 'activo'; } ?>">Cartas</a>
    <a href="" class="enlaceUsuario">
        <section class="usuario">
            <?= $nombre ?>
            <img src="/poke-card/img/avatares/20.png" alt="Avatar de usuario">
        </section>
    </a>
</header>
<?php $paginaActual = basename($_SERVER['SCRIPT_NAME']);?>
<header class="headerPrincipal <?php if ($paginaActual == "registro.php" || $paginaActual == "login.php" || $paginaActual == "ajustes.php") { echo 'centradoH'; } ?>">
    <a href="" class="enlaceInicio"><img src="/poke-card/img/ui/PokeCard.svg" alt="Logo de Poke-Card" class="logoPoke"></a>
    <?php if (!($paginaActual == "registro.php" || $paginaActual == "login.php" || $paginaActual == "ajustes.php")):?>
    <a href="" class="enlaceUsuario">
        <section class="usuario">
            <?= $nombre ?>
            <img src="/poke-card/img/avatares/20.png" alt="Avatar de usuario">
        </section>
    </a>
    <?php endif;?>
</header>
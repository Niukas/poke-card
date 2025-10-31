<?php $paginaActual = basename($_SERVER['SCRIPT_NAME']); ?>


<header class="headerPrincipal <?php if ($paginaActual == "registro.php" || $paginaActual == "login.php" || $paginaActual == "ajustes.php") {
                                    echo 'centradoH';
                                } ?>">
    <a href="<?php if ($paginaActual == "registro.php" || $paginaActual == "login.php" || $paginaActual == "Index.php") {
                    echo '/poke-card/Index.php';
                } else {
                    echo '/poke-card/dashboard.php';
                } ?>" class="enlaceInicio"><img src="/poke-card/img/ui/PokeCard.svg" alt="Logo de Poke-Card" class="logoPoke"></a>
    <?php if (!($paginaActual == "registro.php" || $paginaActual == "login.php" || $paginaActual == "ajustes.php" || $paginaActual == "Index.php")): ?>
        <a href="/poke-card/ajustes.php" class="enlaceUsuario">
            <section class="usuario">
                <?= $_SESSION['nombreUsuario'] ?>
                <img src="<?= $_SESSION['avatar']; ?>" alt="Avatar de usuario">
            </section>
        </a>
    <?php endif; ?>
    <?php if ($paginaActual == "Index.php"): ?>
        <a href="/poke-card/login.php" class="enlaceUsuario">
            <section class="usuario">
                Inicia Sesion!
            </section>
        </a>
    <?php endif; ?>
</header>
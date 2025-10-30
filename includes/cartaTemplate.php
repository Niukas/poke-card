<div class="cartas <?= $cartas['nombre_tipo'] ?> <?= $cartas['nombre_rareza'] ?>">
    <span class="pokemonId">#<?= $cartas['id'] ?></span>
    <div class="imagenPoke">
        <div class="circuloPoke <?= $cartas['nombre_tipo'] ?>"></div>
        <img src="<?= $cartas['url_imagen'] ?>" alt="Imagen de <?= $cartas['nombre'] ?>">
    </div>
    <h3><?= $cartas['nombre'] ?></h3>
    <div class="stats">
        <div>A: <?= $cartas['ataque'] ?></div>
        <div>D: <?= $cartas['defensa'] ?></div>
        <div>V: <?= $cartas['velocidad'] ?></div>
        <div>HP: <?= $cartas['hp'] ?></div>
    </div>
</div>
<?php
session_start();

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

$segundosPorSobre = 3600 * 2; // 1 sobre cada 2 horas (3600 segundos * 2 horas)
$sobresMaximos = 5;

require_once './scripts/recarga_sobres.php';

// Logica para contador de sobre disponibles
$segundosRestantesJS = 0;
$mensajeEstatico = "";
$sobresDisponibles = $_SESSION['sobresDisponibles'];

if ($sobresDisponibles >= $sobresMaximos) {

    $mensajeEstatico = "Abre sobres para obtener más!";
} else {

    $tiempoUltimaRecarga = strtotime($_SESSION['recargaSobres']); // Convertir la fecha en un numero grande de segundos
    $tiempoProximoSobre = $tiempoUltimaRecarga + $segundosPorSobre; // 18:00 + 2 horas = 20:00
    $segundosRestantes = $tiempoProximoSobre - time(); // se resta la hora del proxSobre - ahora

    // verificamos que sea positivo
    if ($segundosRestantes > 0) {
        $segundosRestantesJS = $segundosRestantes;
    } else {
        $mensajeEstatico = "¡Sobre listo! (Recarga la página)";
    }
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
        <section class="contenedorDashboard">
            <p class="texto">Sobres disponibles: <?= $sobresDisponibles ?>/<?= $sobresMaximos ?></p>
            <section class="contenedorSobres">
                <?php
                $rutaTemplateActivo = "./img/ui/sobres/sobreTemplate.svg";
                $rutaTemplateActivoDetras = "./img/ui/sobres/sobreTemplateDetras.svg";
                $rutaTemplateFaltante = "./img/ui/sobres/sobreFaltanteTemplate.svg";
                $rutaTemplateFaltanteDetras = "./img/ui/sobres/sobreFaltanteTemplateDetras.svg";

                for ($i = 1; $i <= $sobresMaximos; $i++):

                    $rutaMostrar = '';
                    $claseCentro = '';

                    // IF para que el sobre del centro si hay 1 sobre este pintado
                    if ($i == 3) {
                        $claseCentro = 'sobreCentro';
                        if ($sobresDisponibles > 0) {
                            $rutaMostrar = $rutaTemplateActivo;
                        } else {
                            $rutaMostrar = $rutaTemplateFaltante;
                        }
                    } else {
                        if ($i <= $sobresDisponibles) {
                            $rutaMostrar = $rutaTemplateActivoDetras;
                        } else {
                            $rutaMostrar = $rutaTemplateFaltanteDetras;
                        }
                    }
                ?>
                    <div class="sobre <?= $claseCentro ?>">
                        <?php include $rutaMostrar; ?>
                    </div>
                <?php endfor; ?>
            </section>
            <?php
            // Si el usuario SÍ tiene sobres, muestra el formulario
            if ($sobresDisponibles > 0):
            ?>
                <form action="./scripts/procesar_abrir_sobre.php" method="post">
                    <button type="submit" class="boton abrir">
                        Abrir
                    </button>
                </form>
            <?php
            endif;
            if (isset($_SESSION['mensaje_error'])) {
                echo '<p class="error">' . $_SESSION['mensaje_error'] . '</p>';
                unset($_SESSION['mensaje_error']);
            }
            ?>
            <p class="texto" id="contadorSobres"></p>
        </section>
    </main>
    <?php
    require './includes/nav.php';
    require './includes/footer.php';
    ?>
    <script>
        // Script para hacer el contador interactivo
        // Traigo los datos de php a JS
        const segundosIniciales = <?= $segundosRestantesJS ?>;
        const mensajeEstatico = "<?= $mensajeEstatico ?>";

        const contadorElemento = document.getElementById('contadorSobres');

        // Vemos que el tiempo sea mayor a 0 sino se muesta el mensaje de "Abrir mas sobres"
        if (segundosIniciales > 0) {
            let segundos = segundosIniciales;

            function actualizarContador() {

                // Comprobamos si el reloj ya llego a 0
                if (segundos <= 0) {
                    contadorElemento.innerText = "¡Sobre listo! (Recarga la página)"
                    clearInterval(intervalo); // Detener el reloj
                    return;
                }

                // Hora : Minutos : Segundos
                let h = Math.floor(segundos / 3600);
                let m = Math.floor((segundos % 3600) / 60);
                let s = segundos % 60;

                // Formato 00:00:00
                if (h < 10) h = "0" + h;
                if (m < 10) m = "0" + m;
                if (s < 10) s = "0" + s;

                contadorElemento.innerText = "Proximo sobre en: " + h + ":" + m + ":" + s;

                segundos--;
            }

            // Llamada a la funcion
            actualizarContador();
            // Setear el intervalo de 1000 ms = 1 segundo, llama a la funcion repetidamente cada ese tiempo seteado
            const intervalo = setInterval(actualizarContador, 1000);
        } else {
            contadorElemento.innerText = mensajeEstatico;
        }
    </script>
</body>

</html>
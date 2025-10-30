<?php

$idUsuario = $_SESSION['idUsuario'];
$sobresActuales = $_SESSION['sobresDisponibles'];

// Verificamos si los sobres que tiene el usuario son menores al maximo (5)
if ($sobresActuales < $sobresMaximos) {

    if (empty($_SESSION['recargaSobres'])) {
        // Si no existe ej: usuario nuevo, pongo 0 para que recargue todo
        $tiempoUltimaRecarga = 0;
    } else {
        $tiempoUltimaRecarga = strtotime($_SESSION['recargaSobres']); // Convertir la fecha en un numero grande de segundos
    }

    $tiempoDeAhora = time(); // Guardo el momento exacto de ahora

    //Verifico si la fecha esta mal en la db
    if ($tiempoUltimaRecarga < 1704067200) {
        $tiempoUltimaRecarga = 0;
    }

    $segundosPasados = $tiempoDeAhora - $tiempoUltimaRecarga; // obtengo cuantos segundos psaron de la ultima recarga

    # Obtengo cuantos sobres gano en base al tiempo y con floor se redondea para abajo, ej 12000/7200 = 1,66 == 1
    $sobreGanados = floor($segundosPasados / $segundosPorSobre);

    if ($sobreGanados > 0) {

        $sobrePotenciales = $sobresActuales + $sobreGanados; //si antes tenia 4 y gano 2, el potencial es 6

        $sobreFinal = min($sobrePotenciales, $sobresMaximos); // Pero maximo es 5 entonces con la funcion min comparo para tener el numero mas chico

        $sobreAgregar = $sobreFinal - $sobresActuales;

        // si no se tiene que agregar ninguno se vuelve Eje: 5 - 5
        if ($sobreAgregar <= 0) {
            return;
        }

        if ($tiempoUltimaRecarga == 0) {
            $nuevoTimestampRecarga = $tiempoDeAhora;
        } else {
            // Cuenta para que el usuario no pierda tiempo por ejemplo si aun falta una hora y media para el otro sobre
            $segundosAConsumir = $sobreAgregar * $segundosPorSobre; // 2 * 7200 = 14400 
            $nuevoTimestampRecarga = $tiempoUltimaRecarga + $segundosAConsumir; // 14:00 + 4 hs = 18:00 
        }

        try {
            $sql = "UPDATE Usuarios SET 
                        sobres_disponibles = ?, 
                        ultima_recarga_sobres = FROM_UNIXTIME(?) 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sobreFinal, $nuevoTimestampRecarga, $idUsuario]);

            // Actualizo los datos de la sesion
            $_SESSION['sobresDisponibles'] = $sobreFinal;
            $_SESSION['recargaSobres'] = date('Y-m-d H:i:s', $nuevoTimestampRecarga);
        } catch (PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }
}

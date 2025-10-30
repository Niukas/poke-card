<?php
// Funcion que obtiene las cartas indivuales Random con sentencia de sql
function obtenerCartaRarezas(PDO $pdo, array $rarezaIds): array | false
{
    //Verificacion que no este vacio el id
    if (empty($rarezaIds)) {
        return false;
    }
    /* construyo el (?, ?, ?) de la sentencia, arrayfill me crea un array con lo mismo varias veces,
    aca de 0 a la cantidad de rarezas que le paso y con implode uno los elementos del array en un string */
    $maqueta = implode(',', array_fill(0, count($rarezaIds), '?'));

    try {
        // sentencia renombro el tipo y rareza para que no haya conflicto con el nombre de pokemon
        $sql = "SELECT pokemon.*, 
                Tipos.nombre AS nombre_tipo,
                Rarezas.nombre AS nombre_rareza
                FROM pokemon 
                JOIN Tipos ON pokemon.id_tipo = Tipos.id 
                JOIN Rarezas ON pokemon.id_rareza = Rarezas.id
                WHERE pokemon.id_rareza IN ($maqueta) 
                ORDER BY RAND() LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($rarezaIds);
        $carta = $stmt->fetch(PDO::FETCH_ASSOC); // Convertimos en un array asociativo
        return $carta;
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return false;
    }
}

// Funcion que genera el array con 5 cartas segun las rarezas posibles
function abrirSobreNuevo(PDO $pdo): array
{
    $sobreResultado = [];

    // Primer ranura = Comun o Poco Comun
    $cartaRanura1 = obtenerCartaRarezas($pdo, [1, 2]);

    if ($cartaRanura1) {
        $sobreResultado[] = $cartaRanura1;
    }

    // Segunda ranura = Comun o Poco Comun
    $cartaRanura2 = obtenerCartaRarezas($pdo, [1, 2]);

    if ($cartaRanura2) {
        $sobreResultado[] = $cartaRanura2;
    }

    // Tercera ranura = Comun, Poco comun o Rara
    $cartaRanura3 = obtenerCartaRarezas($pdo, [1, 2, 3]);

    if ($cartaRanura3) {
        $sobreResultado[] = $cartaRanura3;
    }

    // Cuarta ranura = Rara o Epica
    $cartaRanura4 = obtenerCartaRarezas($pdo, [3, 4]);

    if ($cartaRanura4) {
        $sobreResultado[] = $cartaRanura4;
    }

    // Cuarta ranura = Rara o Epica o Legendaria
    $cartaRanura5 = obtenerCartaRarezas($pdo, [3, 4, 5]);

    if ($cartaRanura5) {
        $sobreResultado[] = $cartaRanura5;
    }

    // Seguro para que siempre salgan 5 cartas
    while (count($sobreResultado) < 5) {
        $reserva = obtenerCartaRarezas($pdo, [1]); // Rellena con comunes
        if ($reserva) {
            $sobreResultado[] = $reserva;
        } else {
            break; // Evita bucle infinito si la BD falla
        }
    }

    return $sobreResultado;
}
?>
<?php
//Funcion para decidir el id por rareza
function seleccionarRarezaPonderada(array $probabilidades): int
{
    $numeroAleatorio = mt_rand(1, 100);
    $acumulado = 0;

    foreach ($probabilidades as $rarezaId => $probabilidad) {
        $acumulado += $probabilidad;
        if ($numeroAleatorio <= $acumulado) {
            return $rarezaId; // si el numero generado es menor al acumulado devuelve la rareza de la carta
        }
    }

    return 1;
}

// Funcion que obtiene las cartas indivuales Random con sentencia de sql
function obtenerCartaRarezas(PDO $pdo, int $rarezaId): array | false
{
    try {
        $sql = "SELECT pokemon.*, 
                Tipos.nombre AS nombre_tipo,
                Rarezas.nombre AS nombre_rareza
                FROM pokemon 
                JOIN Tipos ON pokemon.id_tipo = Tipos.id 
                JOIN Rarezas ON pokemon.id_rareza = Rarezas.id
                WHERE pokemon.id_rareza = ? 
                ORDER BY RAND() LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$rarezaId]);
        $carta = $stmt->fetch(PDO::FETCH_ASSOC);
        return $carta;
    } catch (PDOException $e) {
        error_log("Error en obtenerUnaCartaDeRareza: " . $e->getMessage());
        return false;
    }
}

// Funcion que genera el array con 5 cartas segun las rarezas posibles
function abrirSobreNuevo(PDO $pdo): array
{
    $sobreResultado = [];

    // Primer ranura = Comun o Poco Comun
    // Ranuras 1 y 2: 70% Común, 30% Poco Común
    $probRanura1_2 = [ 1 => 70, 2 => 30 ];
    
    // Ranura 3: 60% Común, 30% Poco Común, 10% Rara
    $probRanura3 = [ 1 => 60, 2 => 30, 3 => 10 ];
    
    // Ranura 4: 80% Rara, 20% Épica
    $probRanura4 = [ 3 => 80, 4 => 20 ];

    // Ranura 5: 75% Rara, 23% Épica, 2% Legendaria
    $probRanura5 = [ 3 => 75, 4 => 23, 5 => 2 ];


    // Ranura 1:
    $rarezaId_1 = seleccionarRarezaPonderada($probRanura1_2);
    $sobreResultado[] = obtenerCartaRarezas($pdo, $rarezaId_1);

    // Ranura 2:
    $rarezaId_2 = seleccionarRarezaPonderada($probRanura1_2);
    $sobreResultado[] = obtenerCartaRarezas($pdo, $rarezaId_2);

    // Ranura 3:
    $rarezaId_3 = seleccionarRarezaPonderada($probRanura3);
    $sobreResultado[] = obtenerCartaRarezas($pdo, $rarezaId_3);

    // Ranura 4:
    $rarezaId_4 = seleccionarRarezaPonderada($probRanura4);
    $sobreResultado[] = obtenerCartaRarezas($pdo, $rarezaId_4);

    // Ranura 5:
    $rarezaId_5 = seleccionarRarezaPonderada($probRanura5);
    $sobreResultado[] = obtenerCartaRarezas($pdo, $rarezaId_5);
    

    // Seguro para que siempre salgan 5 cartas
    while (count($sobreResultado) < 5) {
        $reserva = obtenerCartaRarezas($pdo, 1); // Rellena con comunes
        if ($reserva) {
            $sobreResultado[] = $reserva;
        } else {
            break; // Evita bucle infinito si la BD falla
        }
    }

    return $sobreResultado;
}

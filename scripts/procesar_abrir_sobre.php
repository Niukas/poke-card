<?php
session_start();
require '../includes/db.php';
require_once '../includes/logica_sobres.php';
include '../includes/logica_logros.php';

// Si el usuario no esta logeado lo redirijo al login
if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Checkeo que tenga sobres por las dudas

    $idUsuario = $_SESSION['idUsuario'];

    try {
        $stmtSobres = $pdo->prepare("SELECT sobres_disponibles FROM Usuarios WHERE id = ?");

        $stmtSobres->execute([$idUsuario]);

        $sobresUsuario = $stmtSobres->fetch();

        // Checkeo denuevo si tiene sobres por las dudas
        if (!$sobresUsuario || $sobresUsuario['sobres_disponibles'] <= 0) {
            $_SESSION['mensaje_error'] = "No tenes sobres disponibles";
            session_write_close();
            header('Location: ../dashboard.php');
            exit;
        }

        // Restamos un sobre al usuario en la DB
        $sqlRestar = "UPDATE Usuarios SET 
                  sobres_disponibles = sobres_disponibles - 1,
                  sobres_abiertos_total = sobres_abiertos_total + 1 
                WHERE id = ?";
        $stmtRestar = $pdo->prepare($sqlRestar);
        $stmtRestar->execute([$idUsuario]);
        $_SESSION['sobresDisponibles']--;

        // Traemos la coleccion actual
        $coleccionActual = [];
        $sqlColeccion = "SELECT id_carta FROM coleccion WHERE id_usuario = ?";
        $stmtColeccion = $pdo->prepare($sqlColeccion);
        $stmtColeccion->execute([$idUsuario]);

        // fetchAll(PDO::FETCH_COLUMN, 0) trae solo la primera columna (id_carta) en un array simple
        $coleccionActual = $stmtColeccion->fetchAll(PDO::FETCH_COLUMN, 0); // [1,4,7,10]

        // Genero el sobre nuevo con la funcion
        $cartasDelSobre = abrirSobreNuevo($pdo);

        $cartasConEstado = []; // Array para guardar si una carta es nueva

        foreach ($cartasDelSobre as $carta) {

            $idCarta = $carta['id'];

            $esNueva = !in_array($idCarta, $coleccionActual); // Chek de si la carta esta en el array de la coleccion

            $carta['nueva'] = $esNueva; // le pongo la etiqueta

            $cartasConEstado[] = $carta; // la guardo en el array

            // Logros de Primera vez:
            if ($esNueva) {
                // ID 3 = Rara
                if ($carta['id_rareza'] == 3) otorgarLogroSiNoExiste($idUsuario, 5, $pdo);
                // ID 4 = Épica
                if ($carta['id_rareza'] == 4) otorgarLogroSiNoExiste($idUsuario, 6, $pdo);
                // ID 5 = Legendaria
                if ($carta['id_rareza'] == 5) otorgarLogroSiNoExiste($idUsuario, 7, $pdo);
                // ID 129 = Magikarp
                if ($idCarta == 129) otorgarLogroSiNoExiste($idUsuario, 11, $pdo);
            }
            //------------

            try {
                $sqlGuardar = "INSERT INTO coleccion (id_usuario, id_carta, cantidad) VALUES (?, ?, 1)
                            ON DUPLICATE KEY UPDATE cantidad = cantidad + 1";
                $smtGuardar = $pdo->prepare($sqlGuardar);
                $smtGuardar->execute([$idUsuario, $idCarta]);
            } catch (PDOException $e) {
                error_log($e->getMessage());
            }

            // Guardo por si sale repetido en el mismo sobre no se marque como nueva
            if ($esNueva) {
                $coleccionActual[] = $idCarta;
            }
        }

        // -----------------
        // Verificacion de "Logros obtenidos":
        // Logro de conteo de los sobrres
        $stmtSobres = $pdo->prepare("SELECT sobres_abiertos_total FROM Usuarios WHERE id = ?");
        $stmtSobres->execute([$idUsuario]);
        $totalSobres = $stmtSobres->fetchColumn();

        if ($totalSobres >= 50) otorgarLogroSiNoExiste($idUsuario, 9, $pdo); // ID 9 = Apertura Masiva
        if ($totalSobres >= 200) otorgarLogroSiNoExiste($idUsuario, 10, $pdo); // ID 10 = Fanatico

        // Logros de colecionar cartas Unicas
        $stmtUnicas = $pdo->prepare("SELECT COUNT(id_carta) FROM coleccion WHERE id_usuario = ?");
        $stmtUnicas->execute([$idUsuario]);
        $totalUnicas = $stmtUnicas->fetchColumn();

        if ($totalUnicas >= 25) otorgarLogroSiNoExiste($idUsuario, 1, $pdo); // ID 1 = Novato
        if ($totalUnicas >= 100) otorgarLogroSiNoExiste($idUsuario, 2, $pdo); // ID 2 = Veterano
        if ($totalUnicas >= 151) otorgarLogroSiNoExiste($idUsuario, 3, $pdo); // ID 3 = Maestro

        // Logro de coleccionar 10 tipo FUEGO
        $sqlTipo = "SELECT COUNT(coleccion.id_carta) FROM coleccion JOIN pokemon ON coleccion.id_carta = pokemon.id WHERE coleccion.id_usuario = ? AND pokemon.id_tipo = 1";
        $stmtTipo = $pdo->prepare($sqlTipo);
        $stmtTipo->execute([$idUsuario]);
        if ($stmtTipo->fetchColumn() >= 10) otorgarLogroSiNoExiste($idUsuario, 8, $pdo); // ID 8 = Maestro Fuego

        // Logro de SET Trío Kanto = IDs 1, 4, 7
        $sqlSet = "SELECT COUNT(id_carta) FROM coleccion WHERE id_usuario = ? AND id_carta IN (1, 4, 7)";
        $stmtSet = $pdo->prepare($sqlSet);
        $stmtSet->execute([$idUsuario]);
        if ($stmtSet->fetchColumn() == 3) otorgarLogroSiNoExiste($idUsuario, 4, $pdo); // ID 4 = Trío Kanto
        // -----------------

        $_SESSION['cartasRecientes'] = $cartasConEstado;

        session_write_close();

        header('Location: ../recompensa.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "Error " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: ../dashboard.php');
        exit;
    }
} else {
    header('Location: ../dashboard.php'); // Si no es post se devuelve a la pagina
}

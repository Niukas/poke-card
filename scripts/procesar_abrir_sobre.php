<?php
session_start();
require '../includes/db.php';
require_once '../includes/logica_sobres.php';

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
            header('Location: ../dashboard.php');
            exit;
        }

        // Restamos un sobre al usuario en la DB
        $sqlRestar = "UPDATE Usuarios SET sobres_disponibles = sobres_disponibles - 1 WHERE id = ?";
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

        $_SESSION['cartasRecientes'] = $cartasConEstado;
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

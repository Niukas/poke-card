<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idLogroAReclamar'])) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $idLogro = $_POST['idLogroAReclamar'];

    try {
        
        $sqlCheck = "SELECT 
                        logros.recompensa_sobres, 
                        logros_usuario.reclamada 
                     FROM logros_usuario
                     JOIN logros ON logros_usuario.id_logro = logros.id
                     WHERE logros_usuario.id_usuario = ? AND logros_usuario.id_logro = ?";

        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$idUsuario, $idLogro]);
        $logro = $stmtCheck->fetch();

        // Si no lo tiene o ya lo reclamo lo saco.
        if (!$logro || $logro['reclamada'] == 1) {
            $_SESSION['mensaje_error'] = "Ya has reclamado este logro o no lo tienes.";
            header('Location: ../logros.php');
            exit;
        }

        // Obtengo la recompensa
        $recompensa = $logro['recompensa_sobres'];

        // Se marca el logro como reclamado
        $sqlReclamar = "UPDATE logros_usuario SET reclamada = 1 WHERE id_usuario = ? AND id_logro = ?";
        $pdo->prepare($sqlReclamar)->execute([$idUsuario, $idLogro]);

        // Añadir los sobres al usuario
        $sqlSobres = "UPDATE Usuarios SET sobres_disponibles = sobres_disponibles + ? WHERE id = ?";
        $pdo->prepare($sqlSobres)->execute([$recompensa, $idUsuario]);

        $_SESSION['sobresDisponibles'] += $recompensa;
        $_SESSION['mensaje_exito'] = "¡Has reclamado $recompensa sobres!";
        header('Location: ../logros.php');
        exit;

    } catch (PDOException $e) {

        $_SESSION['mensaje_error'] = "Error al reclamar: " . $e->getMessage();
        header('Location: ../logros.php');
        exit;
    }
} else {
    header('Location: ../logros.php');
    exit;
}
?>
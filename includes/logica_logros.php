<?php
// Funcion para dar un logro al usuario solamente si no lo tiene ya
function otorgarLogroSiNoExiste($idUsuario, $idLogro, PDO $pdo) {
    try {
        $sqlCheck = "SELECT 1 FROM logros_usuario WHERE id_usuario = ? AND id_logro = ?";

        $stmtCheck = $pdo->prepare($sqlCheck);

        $stmtCheck->execute([$idUsuario, $idLogro]);

        // SI da false significa que no lo tiene al logro
        if ($stmtCheck->fetchColumn() == false) {
            
            $sqlInsert = "INSERT INTO logros_usuario (id_usuario, id_logro) VALUES (?, ?)";

            $stmtInsert = $pdo->prepare($sqlInsert);

            $stmtInsert->execute([$idUsuario, $idLogro]);

            $_SESSION['logro_nuevo'] = "¡Logro Desbloqueado!";
        }

    } catch (PDOException $e) {
        error_log("Error ". $e->getMessage());
    }
}

?>
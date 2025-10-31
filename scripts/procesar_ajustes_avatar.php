<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idUsuario = $_SESSION['idUsuario'];
    $avatarId = $_POST['avatarId'];

    $sql = "UPDATE Usuarios SET id_avatar = ? WHERE id = ?;";

    try {
        $stmt = $pdo->prepare($sql); // preparo la sentencia

        $stmt->execute([$avatarId,$idUsuario]); // le cargo los datos a la sentencia

        // Traigo el url de el avatar nuevo
        $sqlAvatar = "SELECT id,url_imagen FROM Avatares WHERE id = ?";
        $stmtAvatar = $pdo->prepare($sqlAvatar);
        $stmtAvatar->execute([$avatarId]);
        $avatar = $stmtAvatar->fetch();

        if ($avatar) {
            $_SESSION['avatar'] = $avatar['url_imagen']; // Actualizo la URL en la sesión
            $_SESSION['idAvatar'] = $avatar['id']; // Actualizo la URL en la sesión
        }

        $_SESSION['mensaje_exito_registro'] = "!Avatar cambiado con exito!"; // Envio el mensaje de exito devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_registro'] = "Error al cambiar el avatar: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    }
}else {
    header('Location: ../ajustes.php'); // Si no es post se devuelve a la pagina
}
?>
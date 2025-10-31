<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idUsuario = $_SESSION['idUsuario'];
    $usuarioNuevo = $_POST['nuevoNombre'];


    $sql = "UPDATE Usuarios SET nombre = ? WHERE id = ?;";

    try {
        $stmt = $pdo->prepare($sql); // preparo la sentencia

        $stmt->execute([$usuarioNuevo,$idUsuario]); // le cargo los datos a la sentencia

        $_SESSION['nombreUsuario'] = $usuarioNuevo;

        $_SESSION['mensaje_exito_usuario'] = "!Nombre de Usuario actualizado con exito!"; // Envio el mensaje de exito devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_usuario'] = "Error al cambiar el nombre de Usuario: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    }
}else {
    header('Location: ../ajustes.php'); // Si no es post se devuelve a la pagina
}
?>
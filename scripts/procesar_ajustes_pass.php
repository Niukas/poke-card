<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idUsuario = $_SESSION['idUsuario'];
    $contra = $_POST['contraNueva'];
    $contraRepetida = $_POST['contraNuevaRepetida'];

    if (empty($contra) || empty($contraRepetida)) {
        $_SESSION['mensaje_error'] = "Debes llenar ambos campos.";
        header('Location: ../ajustes.php');
        exit;
    }

    if ($contra !== $contraRepetida) {
        $_SESSION['mensaje_error'] = "Ingrese dos contraseñas iguales";
        header('Location: ../ajustes.php');
        exit;
    }

    //Hasheo de contrasena
    $contraHasheada = password_hash($contra, PASSWORD_DEFAULT);

    $sql = "UPDATE Usuarios SET contrasena = ? WHERE id = ?;";

    try {
        $stmt = $pdo->prepare($sql); // preparo la sentencia

        $stmt->execute([$contraHasheada,$idUsuario]); // le cargo los datos a la sentencia

        $_SESSION['mensaje_exito_contra'] = "!Contraseña actualizada con exito!"; // Envio el mensaje de exito devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_contra'] = "Error al cambiar la contraseña: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: ../ajustes.php');
        exit;
    }
}else {
    header('Location: ../ajustes.php'); // Si no es post se devuelve a la pagina
}
?>
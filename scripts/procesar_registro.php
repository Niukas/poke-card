<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST['user'];
    $contra = $_POST['password'];
    $contraRepetida = $_POST['rpassword'];

    // Checkeo que las contrasenas sean las mismas 
    if ($contra !== $contraRepetida) {
        $_SESSION['mensaje_error_registro'] = "Ingrese dos contraseñas iguales";
        header('Location: ../registro.php');
        exit;
    }

    //Hasheo de contrasena
    $cotraHasheada = password_hash($contra, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, contrasena, id_avatar) VALUES (:nombre, :contrasena, :avatar);";

    // Creo un array con los datos del formulario
    $datosInsertar = [
        ':nombre'    => $user,
        ':contrasena' => $cotraHasheada,
        ':avatar' => 20
    ];
    
    try {
        $stmt = $pdo->prepare($sql); // preparo la sentencia

        $stmt->execute($datosInsertar); // le cargo los datos a la sentencia

        $_SESSION['mensaje_exito_registro'] = "!Registrado con exito!"; // Envio el mensaje de exito devuelta al formulario
        header('Location: ../registro.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_registro'] = "Error al Registro " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: ../registro.php');
        exit;
    }
}else {
    header('Location: ../registro.php'); // Si no es post se devuelve a la pagina
}
?>
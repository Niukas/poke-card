<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST['user'];
    $contra = $_POST['password'];

    $sql = "SELECT 
                Usuarios.*, 
                Avatares.url_imagen 
            FROM Usuarios 
            JOIN Avatares ON Usuarios.id_avatar = Avatares.id 
            WHERE Usuarios.nombre = :nombre";

    // Creo un array con los datos del formulario
    $datosInsertar = [
        ':nombre'    => $user
    ];

    try {
        $stmt = $pdo->prepare($sql); // preparo la sentencia

        $stmt->execute($datosInsertar); // le cargo los datos a la sentencia

        $usuarioEncontrado = $stmt->fetch(); // Traigo un array con los datos del usuario

        if ($usuarioEncontrado && password_verify($contra, $usuarioEncontrado['contrasena'])) {
            // si el usuario se autentica guardo todos los datos dentro de SESSION
            $_SESSION['idUsuario'] = $usuarioEncontrado['id'];
            $_SESSION['nombreUsuario'] = $usuarioEncontrado['nombre'];
            $_SESSION['rolUsuario'] = $usuarioEncontrado['rol'];
            $_SESSION['sobresDisponibles'] = $usuarioEncontrado['sobres_disponibles'];
            $_SESSION['recargaSobres'] = $usuarioEncontrado['ultima_recarga_sobres'];
            $_SESSION['avatar'] = $usuarioEncontrado['url_imagen'];
            $_SESSION['idAvatar'] = $usuarioEncontrado['id_avatar'];

            session_write_close();

            // Redirijo al dashboard
            if ($usuarioEncontrado['rol'] == 'admin') {
                session_write_close();
                header('Location: ../admin/Index.php');
                exit;
            }
            header('Location: ../dashboard.php');
            exit;
        } else {
            $_SESSION['mensaje_error'] = "Usuario o contraseña incorrectos";
            session_write_close();
            header('Location: ../login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "Error" . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        session_write_close();
        header('Location: ../login.php');
        exit;
    }
} else {
    session_write_close();
    header('Location: ../login.php'); // Si no es post se devuelve a la pagina
    exit;
}

<?php
session_start();
require '../includes/db.php';

// Verifico si el formulario se envio por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $sqlCrear = "DELETE FROM pokemon WHERE id = ?;";

    // Creo un array con los datos del formulario
    $idAEliminar = $_POST['listaPokes'];

    try {
        $stmt = $pdo->prepare($sqlCrear); // preparo la sentencia

        $stmt->execute([ $idAEliminar ]); // le cargo los datos a la sentencia

        $_SESSION['mensaje_exito_eliminar'] = "Carta #$idAEliminar eliminada con exito"; // Envio el mensaje de exito devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_eliminar'] = "Error al eliminar la carta: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    }
}else {
    header('Location: /gestionar_cartas.php'); // Si no es post se devuelve a la pagina
}
?>
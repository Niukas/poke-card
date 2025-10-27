<?php
session_start();
require '../includes/db.php';

// Verifico si el formulario se envio por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sqlCrear = "INSERT INTO pokemon (
                id, nombre, id_tipo, id_rareza, url_imagen, 
                hp, ataque, defensa, velocidad
            ) VALUES (
                :id, :nombre, :tipo, :rareza, :imagen, 
                :hp, :ataque, :defensa, :velocidad
            )";

    // Creo un array con los datos del formulario
    $datosInsertar = [
        ':id'        => $_POST['idPokemon'],
        ':nombre'    => $_POST['nombrePokemon'],
        ':tipo'      => $_POST['tipoPokemon'],
        ':rareza'    => $_POST['rarezaPokemon'],
        ':imagen'    => $_POST['imagenPokemon'],
        ':hp'        => $_POST['hpPokemon'],
        ':ataque'    => $_POST['attackPokemon'],
        ':defensa'   => $_POST['defensePokemon'],
        ':velocidad' => $_POST['speedPokemon']
    ];
    
    try {
        $stmt = $pdo->prepare($sqlCrear); // preparo la sentencia

        $stmt->execute($datosInsertar); // le cargo los datos a la sentencia

        $_SESSION['mensaje_exito_crear'] = "Carta $nombre creada con exito"; // Envio el mensaje de exito devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_crear'] = "Error al crear la carta: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    }
}else {
    header('Location: /gestionar_cartas.php'); // Si no es post se devuelve a la pagina
}
?>
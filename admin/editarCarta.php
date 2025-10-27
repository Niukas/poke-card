<?php
session_start();
require '../includes/db.php';

// Verifico si el formulario se envio por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_a_editar = $_POST['listaPoke'];

    $sqlCrear = "UPDATE pokemon SET 
                nombre = :nombre, 
                id_tipo = :tipo, 
                id_rareza = :rareza, 
                url_imagen = :imagen, 
                hp = :hp, 
                ataque = :ataque, 
                defensa = :defensa, 
                velocidad = :velocidad 
            WHERE 
                id = :id_a_editar";

    // Creo un array con los datos del formulario
    $datosInsertar = [
        ':nombre'    => $_POST['nombre'],
        ':tipo'      => $_POST['tipos'],
        ':rareza'    => $_POST['rarezaPokemon'],
        ':imagen'    => $_POST['imagenPokemon'],
        ':hp'        => $_POST['hpPokemon'],
        ':ataque'    => $_POST['attackPokemon'],
        ':defensa'   => $_POST['defensePokemon'],
        ':velocidad' => $_POST['speedPokemon'],
        ':id_a_editar' => $_POST['idPokemonOculto']
    ];
    
    try {
        $stmt = $pdo->prepare($sqlCrear); // preparo la sentencia

        $stmt->execute($datosInsertar); // le cargo los datos a la sentencia

        $_SESSION['mensaje_exito_editar'] = "Carta $nombre editada con exito"; // Envio el mensaje de exito devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje_error_editar'] = "Error al editar la carta: " . $e->getMessage(); // Envio el mensaje de error devuelta al formulario
        header('Location: gestionar_cartas.php');
        exit;
    }
}else {
    header('Location: /gestionar_cartas.php'); // Si no es post se devuelve a la pagina
}
?>
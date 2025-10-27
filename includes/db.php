<?php
// Conexion a la base de datos mediante pdo

$dsn = "mysql:host=localhost;dbname=poke_card;charset=utf8mb4";

$usuario = "pokecard_user";
$contra = "654987";

// Probamos si la conexion se hace, sino devuelte el error
try {
    $pdo = new Pdo($dsn, $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Linea por si se envia una sentecia sql mal lance un exception
} catch (PDOException $e) {
    echo "error" . $e->getMessage(); // Muestro si hay algun error con echo
}
?>
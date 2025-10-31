<?php
session_start();

// Vacio todos los datos de la variable
session_unset();

// Destruir la sesion
session_destroy();

header('Location: ../Index.php');
exit;
?>
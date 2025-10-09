<?php
session_start(); // empieza la sesion para poder borrarla desp
session_destroy(); // borra todos los datos de la sesion y el usuario queda deslogueado
header("Location: ../index.php"); // lleva al usuario al index
exit; // corta el code asi no se ejecuta mas
?>
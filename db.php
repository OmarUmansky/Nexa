<?php
$hostname = "localhost";
$user = "root";
$pass = "";
$database = "nexaaa";

$db = new mysqli($hostname, $user, $pass, $database);

if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}
?>
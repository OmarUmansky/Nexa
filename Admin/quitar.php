<?php
require_once "../db.php"; // conecta la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") { // revisa si el form se mandó en post
    $ci_usuario = $_POST['ci_usuario']; // agarra el valor del campo ci y lo guarda en ci

    if ($ci_usuario) { // comprueba que no ande vacio y si anda vacio no hace nada
        $delete = $db->query("DELETE FROM empleado WHERE ci_usuario='$ci_usuario'"); // la consulta para borrar el empleado del sql
        if ($delete) {
            header("Location: admin.php");
            exit; // si se borra te manda a admin.php y si no te sale el error
        } else {
            echo "Error al eliminar el barbero <a href='admin.php'>Volver</a>";
        }
    }
}
?>
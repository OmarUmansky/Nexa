<?php
require_once "../db.php"; // conecta la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") { //verifica si el form se mandó en post

    $ci_usuario = $_POST['ci_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $horario_inc = $_POST['horario_inc'];
    $horario_fin = $_POST['horario_fin']; //se guarda cada campo

    $hash = password_hash($contraseña, PASSWORD_DEFAULT); // se cifra la pass

    if ($ci_usuario && $nombre && $apellido && $correo && $contraseña && $horario_inc && $horario_fin) { // verifica que los campos estén completos

        $check = $db->query("SELECT * FROM empleado WHERE ci_usuario='$ci_usuario' OR correo='$correo'"); // Verifica si hay un barbero con esa cedula o correo y si hay mayor a 1, salé el mensaje de este barbero ya está registrado

        if ($check->num_rows > 0) {
            echo "Este barbero ya está registrado <a href='admin.php'>Volver</a>";
        } else {

            $sql = "INSERT INTO empleado (ci_usuario, nombre, apellido, correo, contraseña, horario_inc, horario_fin) 
                    VALUES ('$ci_usuario', '$nombre', '$apellido', '$correo', '$hash', '$horario_inc', '$horario_fin')"; // se inserta el barbero en el sql
            
            if ($db->query($sql)) { // si se inserta bien, se pone barbero agregado correctamente, si no, sale el error
                echo "Barbero agregado correctamente <a href='admin.php'>Volver</a>";
            } else {
                echo "error al agregar el barbero <a href='admin.php'>Volver</a>";
            }
        }

    } else { // si falta completar campos sale esto
        echo "por favor completa todos los campos <a href='admin.php'>Volver</a>";
    }
}
?>
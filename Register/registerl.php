<?php
require_once "../db.php"; // conecta a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") { // verifica si la solicitud es post
    $ci = $_POST['ci_usuario']; // guarda todo lo que el usuario escribio en cada coso
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $ciudad = $_POST['ciudad'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $password = $_POST['contraseña'];
    $hash = password_hash($password, PASSWORD_DEFAULT); // hashea la contraseña

    if ($ci != "" && $correo != "" && $telefono != "" && $nombre != "" && $apellido != "" && $password != "") { // revisa si hay campos vacios

        $check = $db->query("SELECT * FROM cliente WHERE correo='$correo' OR ci_usuario='$ci'"); // busca si ya existe un usuario con ese correo o cedula
        if ($check->num_rows > 0) { // te dice si hay un usuario con ese correo o cedula
            echo "Este usuario ya está registrado <a href='register.php'>Volver</a>";
        } else {
            $insert = $db->query("INSERT INTO cliente (ci_usuario,nombre,apellido,ciudad,correo,contraseña,telefono) 
                                  VALUES ('$ci','$nombre','$apellido','$ciudad','$correo','$hash','$telefono')"); // inserta los datos del usuario en la base de datos
            if ($insert) {
                header("Location: ../Login/login.php"); // si la insercion funciona te manda al login
            } else {
                echo "Error al registrarse <a href='register.php'>Volver</a>";
            } // si sale mal te manda error al registrarse
        }

    } else {
        echo "Por favor completa todos los campos <a href='register.php'>Volver</a>";
    }
}
?>

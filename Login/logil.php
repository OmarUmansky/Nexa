<?php
session_start(); // Inicia la sesión
require "../db.php"; // Conecta a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? ''); //Tomamos los datos del usuario
    $password = trim($_POST["contraseña"] ?? ''); // Lo mismo con la contraseña

    // Se verifica si es el admin
    if ($nombre === "Admin" && $password === "Adminempresa123") {
        $_SESSION['usuario'] = "Admin"; // el usuario se establece admin
        $_SESSION['rol'] = "admin"; // el rol se establece admin
        header("Location: ../index.php"); // te lleva al index
        exit; // detiene el código
    }

    if ($nombre != '' && $password != '') { //Revisa si el usuario y la contraseña están vacíos

        // Primero buscamos en clientes
        if ($stmt = $db->prepare("SELECT contraseña FROM cliente WHERE nombre = ?")) {
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $stmt->bind_result($hash);

            if ($stmt->fetch()) {
                // Acá verifico la contraseña
                if (password_verify($password, $hash)) {
                    $_SESSION['usuario'] = $nombre; // guardo el usuario que está en sesión
                    $_SESSION['rol'] = "cliente"; // rol cliente
                    $stmt->close();
                    header("Location: ../index.php");
                    exit;
                } else {
                    echo "Nombre o contraseña incorrecto  <a href='login.php'>Volver a intentarlo</a>";
                    $stmt->close();
                    exit;
                }
            }
            $stmt->close();
        }

        // Si no se encuentra en clientes, buscamos en empleados
        if ($stmt2 = $db->prepare("SELECT contraseña FROM empleado WHERE nombre = ?")) {
            $stmt2->bind_param("s", $nombre);
            $stmt2->execute();
            $stmt2->bind_result($hash2);

            if ($stmt2->fetch()) { // Si encontró al empleado
                if (password_verify($password, $hash2)) {
                    $_SESSION['usuario'] = $nombre; // usuario en sesión
                    $_SESSION['rol'] = "empleado"; // rol empleado
                    $stmt2->close();
                    header("Location: ../index.php");
                    exit;
                } else {
                    echo "Nombre o contraseña incorrecto  <a href='login.php'>Volver a intentarlo</a>";
                    $stmt2->close();
                    exit;
                }
            } else {
                echo "Nombre o contraseña incorrecto  <a href='login.php'>Volver a intentarlo</a>";
                $stmt2->close();
                exit;
            }
        }

    } else {
        echo "Completa todos los campos por favor <a href='login.php'>Volver</a>"; 
        exit;
    }
} else {
    header("Location: login.php"); // Manda al usuario a la pagina de login
    exit; // Detiene el código
}
?>
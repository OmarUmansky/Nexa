<?php
require_once __DIR__ . '/../db.php'; // conecta a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ci = isset($_POST['ci_usuario']) ? intval($_POST['ci_usuario']) : 0;
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $password = $_POST['contraseña'] ?? '';

    
    if ($ci !== 0 && $correo !== '' && $telefono !== '' && $nombre !== '' && $apellido !== '' && $password !== '') {
        
        if ($stmt = $db->prepare('SELECT ci_usuario FROM cliente WHERE correo = ? OR ci_usuario = ? LIMIT 1')) {
            $stmt->bind_param('si', $correo, $ci);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo "Este usuario ya está registrado <a href='register.php'>Volver</a>";
                $stmt->close();
                exit;
            }
            $stmt->close();
        } else {
            echo "Error en la verificación del usuario. <a href='register.php'>Volver</a>";
            exit;
        }

        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($ins = $db->prepare('INSERT INTO cliente (ci_usuario, nombre, apellido, correo, contraseña, telefono) VALUES (?, ?, ?, ?, ?, ?)')) {
            $ins->bind_param('isssss', $ci, $nombre, $apellido, $correo, $hash, $telefono);
            if ($ins->execute()) {
                $ins->close();
                header('Location: ../Login/login.php');
                exit;
            } else {
                echo "Error al registrarse <a href='register.php'>Volver</a>";
                $ins->close();
                exit;
            }
        } else {
            echo "Error preparando la inserción. <a href='register.php'>Volver</a>";
            exit;
        }

    } else {
        echo "Por favor completa todos los campos <a href='register.php'>Volver</a>";
        exit;
    }
}

?>

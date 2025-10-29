<?php
require_once "../db.php"; // conecta la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ci_usuario = trim($_POST['ci_usuario'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';
    $rol = $_POST['rol'] ?? '';

    // Validaciones mínimas
    if ($ci_usuario === '' || $nombre === '' || $apellido === '' || $correo === '' || $contraseña === '' || ($rol !== 'barbero' && $rol !== 'cliente')) {
        echo "Por favor completa todos los campos requeridos. <a href='admin.php'>Volver</a>";
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico inválido. <a href='admin.php'>Volver</a>";
        exit;
    }

    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Verificar duplicados en ambas tablas (cliente o empleado)
    $stmt = $db->prepare("SELECT ci_usuario FROM cliente WHERE ci_usuario = ? OR correo = ?");
    $stmt->bind_param("is", $ci_usuario, $correo);
    $stmt->execute();
    $stmt->store_result();
    $exists_client = $stmt->num_rows > 0;
    $stmt->close();

    $stmt = $db->prepare("SELECT ci_usuario FROM empleado WHERE ci_usuario = ? OR correo = ?");
    $stmt->bind_param("is", $ci_usuario, $correo);
    $stmt->execute();
    $stmt->store_result();
    $exists_employee = $stmt->num_rows > 0;
    $stmt->close();

    if ($exists_client || $exists_employee) {
        echo "Ya existe un usuario con esa cédula o correo. <a href='admin.php'>Volver</a>";
        exit;
    }

    if ($rol === 'barbero') {
        // Insertar en empleado (sin horarios)
        $stmt = $db->prepare("INSERT INTO empleado (ci_usuario, nombre, apellido, correo, contraseña) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo "Error en la consulta. <a href='admin.php'>Volver</a>";
            exit;
        }
        $stmt->bind_param("issss", $ci_usuario, $nombre, $apellido, $correo, $hash);
        if ($stmt->execute()) {
            echo "Barbero agregado correctamente. <a href='admin.php'>Volver</a>";
        } else {
            echo "Error al agregar barbero. <a href='admin.php'>Volver</a>";
        }
        $stmt->close();

    } else {
        // Insertar en cliente
        $stmt = $db->prepare("INSERT INTO cliente (ci_usuario, nombre, apellido, correo, contraseña) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo "Error en la consulta. <a href='admin.php'>Volver</a>";
            exit;
        }
        $stmt->bind_param("issss", $ci_usuario, $nombre, $apellido, $correo, $hash);
        if ($stmt->execute()) {
            echo "Cliente agregado correctamente. <a href='admin.php'>Volver</a>";
        } else {
            echo "Error al agregar cliente. <a href='admin.php'>Volver</a>";
        }
        $stmt->close();
    }

}

?>
<?php
session_start();
require "../db.php";
require_once '../agendamail/agendamail.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cita = $_POST['id'];

    // Traemos los datos de la cita para enviar el correo
    $stmt = $db->prepare("SELECT usuario, servicio, fecha FROM citas WHERE id = ?");
    $stmt->bind_param("i", $id_cita);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 0) {
        echo "Cita no encontrada.";
        exit();
    }
    $cita = $resultado->fetch_assoc();

    $usuario = $cita['usuario'];
    $servicio = $cita['servicio'];
    $fecha_hora = $cita['fecha'];

    // Obtener correo del usuario
    $stmt2 = $db->prepare("SELECT correo FROM cliente WHERE nombre = ?");
    $stmt2->bind_param("s", $usuario);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($res2->num_rows === 0) {
        echo "Usuario no encontrado.";
        exit();
    }
    $correo_usuario = $res2->fetch_assoc()['correo'];

    // Separar fecha y hora
    $dt = new DateTime($fecha_hora);
    $fecha = $dt->format("Y-m-d");
    $hora = $dt->format("H:i");

    // Enviar correo
    $envio = enviarConfirmacionCita($correo_usuario, $usuario, $fecha, $hora);
    if ($envio !== true) {
        echo $envio; // Error al enviar correo
        exit();
    }

    // Redirigir después del envío
    header("Location: admin.php?mensaje=cita_aceptada");
    exit();
}
?>
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

    // Obtener nombre del servicio (si el campo en citas almacena el id del servicio)
    $serv_nombre = null;
    if (!empty($servicio)) {
        $stmt3 = $db->prepare("SELECT nombre_servicio FROM servicio WHERE id_servicio = ? LIMIT 1");
        $stmt3->bind_param("i", $servicio);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        if ($res3 && $res3->num_rows > 0) {
            $serv_nombre = $res3->fetch_assoc()['nombre_servicio'];
        }
        if ($stmt3) $stmt3->close();
    }

    if (empty($serv_nombre)) {
        // Fallback: si no se encontró nombre en la tabla, usar el valor tal cual (puede ser texto)
        $serv_nombre = $servicio;
    }

    // Enviar correo (pasando el nombre del servicio)
    $envio = enviarConfirmacionCita($correo_usuario, $usuario, $fecha, $hora, $serv_nombre);
    if ($envio !== true) {
        echo $envio; // Error al enviar correo
        exit();
    }

    // Borrar la cita ahora que fue aceptada (aceptar implica confirmación y eliminación de la solicitud)
    $stmtDel = $db->prepare("DELETE FROM citas WHERE id = ?");
    $stmtDel->bind_param("i", $id_cita);
    $stmtDel->execute();
    $stmtDel->close();

    // Redirigir después del envío y eliminación e incluir el nombre del servicio para verificación
    header("Location: admin.php?mensaje=cita_aceptada&servicio=" . urlencode($serv_nombre));
    exit();
}
?>
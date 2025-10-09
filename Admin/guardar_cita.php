<?php
session_start();
require "../db.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_SESSION['usuario'];
    $servicio = $_POST['Servicio'];
    $fecha = $_POST['fecha'];
    
    $stmt = $db->prepare("INSERT INTO citas (usuario, servicio, fecha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $servicio, $fecha);

    if ($stmt->execute()) {

        header("Location: ../index.php?mensaje=cita_agendada");
        exit();
    } else {

        echo "<p>Error al agendar cita: " . htmlspecialchars($stmt->error) . "</p>";
        echo "<p><a href='cita.php'>Volver</a></p>";
    }

    $stmt->close();
}
?>
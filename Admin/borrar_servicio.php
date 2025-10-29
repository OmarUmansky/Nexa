<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../Login/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_servicio'] ?? 0);
    if ($id <= 0) {
        header('Location: admin.php');
        exit;
    }

    $stmt = $db->prepare("DELETE FROM servicio WHERE id_servicio = ?");
    if (!$stmt) {
        echo 'Error en la consulta.';
        exit;
    }
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: admin.php?msg=' . urlencode('Servicio eliminado.') . '&type=success');
        exit;
    } else {
        echo 'Error al eliminar: ' . htmlspecialchars($stmt->error);
        exit;
    }
}

header('Location: admin.php');
exit;

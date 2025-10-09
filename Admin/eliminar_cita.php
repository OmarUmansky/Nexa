<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $db->prepare("DELETE FROM citas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin.php?mensaje=cita_eliminada");
        exit();
    } else {
        echo "<p>Error al eliminar cita: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}
?>
<?php
require_once "../db.php"; // conecta la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ci_usuario = intval($_POST['ci_usuario'] ?? 0);
    $role = $_POST['role'] ?? 'barbero';

    if ($ci_usuario <= 0) {
        header('Location: admin.php');
        exit;
    }

    // Mapear role a tabla
    if ($role === 'cliente') {
        $table = 'cliente';
    } else {
        // aceptar 'barbero' como empleado
        $table = 'empleado';
    }

    // Usar prepared statement para borrar
    $stmt = $db->prepare("DELETE FROM `" . $table . "` WHERE ci_usuario = ?");
    if (!$stmt) {
        echo "Error en la consulta. <a href='admin.php'>Volver</a>";
        exit;
    }
    $stmt->bind_param('i', $ci_usuario);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: admin.php');
        exit;
    } else {
        $err = htmlspecialchars($stmt->error);
        $stmt->close();
        echo "Error al eliminar el usuario: {$err} <a href='admin.php'>Volver</a>";
        exit;
    }
}
?>
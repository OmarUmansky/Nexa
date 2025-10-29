<?php
session_start();
require_once "../db.php";

// Verificar que el usuario sea admin (básico)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../Login/login.php');
    exit;
}

$error = '';
$success = '';

// Si llega POST: procesar la actualización (puede cambiar rol)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ci = intval($_POST['ci_usuario'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['contraseña'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($ci <= 0) {
        header('Location: admin.php?msg=' . urlencode('Cédula inválida.') . '&type=error');
        exit;
    }
    if ($nombre === '' || $apellido === '' || $correo === '' || ($role !== 'cliente' && $role !== 'barbero')) {
        header('Location: admin.php?msg=' . urlencode('Completa los campos requeridos.') . '&type=error');
        exit;
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header('Location: admin.php?msg=' . urlencode('Correo inválido.') . '&type=error');
        exit;
    }

    // Determinar dónde está actualmente el usuario
    $in_cliente = false;
    $in_empleado = false;

    $stmt = $db->prepare("SELECT `contraseña` FROM cliente WHERE ci_usuario = ?");
    if ($stmt) {
        $stmt->bind_param('i', $ci);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $existing_hash = $row['contraseña'];
            $in_cliente = true;
        }
        $stmt->close();
    }

    $stmt = $db->prepare("SELECT `contraseña` FROM empleado WHERE ci_usuario = ?");
    if ($stmt) {
        $stmt->bind_param('i', $ci);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $existing_hash = $row['contraseña'];
            $in_empleado = true;
        }
        $stmt->close();
    }

    // Si no existe en ninguna tabla, error
    if (!$in_cliente && !$in_empleado) {
        header('Location: admin.php?msg=' . urlencode('Usuario no encontrado.') . '&type=error');
        exit;
    }

    // Decidir hash a usar
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // usar hash existente obtenido arriba
        $hash = $existing_hash ?? '';
    }

    // Si el rol no cambia: actualizar en la misma tabla
    if ($in_cliente && $role === 'cliente') {
        $stmt = $db->prepare("UPDATE cliente SET nombre = ?, apellido = ?, correo = ?, telefono = ?, `contraseña` = ? WHERE ci_usuario = ?");
        if (!$stmt) {
            header('Location: admin.php?msg=' . urlencode('Error al preparar la consulta.') . '&type=error');
            exit;
        }
        $stmt->bind_param('sssssi', $nombre, $apellido, $correo, $telefono, $hash, $ci);
        if ($stmt->execute()) {
            header('Location: admin.php?msg=' . urlencode('Cliente actualizado correctamente.') . '&type=success');
            exit;
        } else {
            header('Location: admin.php?msg=' . urlencode('Error al actualizar cliente: ' . $stmt->error) . '&type=error');
            exit;
        }
    }

    if ($in_empleado && $role === 'barbero') {
        $stmt = $db->prepare("UPDATE empleado SET nombre = ?, apellido = ?, correo = ?, `contraseña` = ? WHERE ci_usuario = ?");
        if (!$stmt) {
            header('Location: admin.php?msg=' . urlencode('Error al preparar la consulta.') . '&type=error');
            exit;
        }
        $stmt->bind_param('ssssi', $nombre, $apellido, $correo, $hash, $ci);
        if ($stmt->execute()) {
            header('Location: admin.php?msg=' . urlencode('Barbero actualizado correctamente.') . '&type=success');
            exit;
        } else {
            header('Location: admin.php?msg=' . urlencode('Error al actualizar barbero: ' . $stmt->error) . '&type=error');
            exit;
        }
    }

    // Si hay cambio de rol: mover entre tablas (usar transacción)
    $db->begin_transaction();
    try {
        if ($in_cliente && $role === 'barbero') {
            // Insertar en empleado
            $ins = $db->prepare("INSERT INTO empleado (ci_usuario, nombre, apellido, correo, `contraseña`) VALUES (?, ?, ?, ?, ?)");
            if (!$ins) throw new Exception('Error al preparar insert empleado');
            $ins->bind_param('issss', $ci, $nombre, $apellido, $correo, $hash);
            if (!$ins->execute()) throw new Exception('No se pudo insertar en empleado: ' . $ins->error);
            $ins->close();

            // Borrar de cliente
            $del = $db->prepare("DELETE FROM cliente WHERE ci_usuario = ?");
            if (!$del) throw new Exception('Error al preparar delete cliente');
            $del->bind_param('i', $ci);
            if (!$del->execute()) throw new Exception('No se pudo borrar cliente: ' . $del->error);
            $del->close();

            $db->commit();
            header('Location: admin.php?msg=' . urlencode('Usuario convertido a barbero correctamente.') . '&type=success');
            exit;
        }

        if ($in_empleado && $role === 'cliente') {
            // Insertar en cliente (telefono opcional)
            $ins = $db->prepare("INSERT INTO cliente (ci_usuario, nombre, apellido, correo, `contraseña`, telefono) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$ins) throw new Exception('Error al preparar insert cliente');
            $ins->bind_param('issssi', $ci, $nombre, $apellido, $correo, $hash, $telefono);
            if (!$ins->execute()) throw new Exception('No se pudo insertar en cliente: ' . $ins->error);
            $ins->close();

            // Borrar de empleado
            $del = $db->prepare("DELETE FROM empleado WHERE ci_usuario = ?");
            if (!$del) throw new Exception('Error al preparar delete empleado');
            $del->bind_param('i', $ci);
            if (!$del->execute()) throw new Exception('No se pudo borrar empleado: ' . $del->error);
            $del->close();

            $db->commit();
            header('Location: admin.php?msg=' . urlencode('Usuario convertido a cliente correctamente.') . '&type=success');
            exit;
        }

        // Si llegó aquí, algo inesperado
        $db->rollBack();
        header('Location: admin.php?msg=' . urlencode('Operación no completada.') . '&type=error');
        exit;

    } catch (Exception $e) {
        $db->rollback();
        header('Location: admin.php?msg=' . urlencode('Error: ' . $e->getMessage()) . '&type=error');
        exit;
    }

}

// Si llega GET con ?ci= mostrar datos del usuario
$usuario = null;
$current_role = null;
$ci_query = intval($_GET['ci'] ?? 0);
if ($ci_query > 0) {
    // Intentar cliente
    $stmt = $db->prepare("SELECT ci_usuario, nombre, apellido, correo, telefono, `contraseña` FROM cliente WHERE ci_usuario = ?");
    if ($stmt) {
        $stmt->bind_param('i', $ci_query);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $usuario = $res->fetch_assoc();
            $current_role = 'cliente';
        }
        $stmt->close();
    }

    if (!$usuario) {
        $stmt = $db->prepare("SELECT ci_usuario, nombre, apellido, correo, `contraseña` FROM empleado WHERE ci_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('i', $ci_query);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $usuario = $res->fetch_assoc();
                $current_role = 'barbero';
                // asegurarnos que telefono exista como vacío
                $usuario['telefono'] = '';
            }
            $stmt->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Pequeños ajustes para que combine con el panel */
        .editar-contenedor { max-width: 720px; margin: 24px auto; background: #fff; padding: 18px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .editar-contenedor h1 { margin-top: 0; }
        .form-row { display:flex; gap:12px; }
        .form-row input { flex:1; padding:8px 10px; border:1px solid #ddd; border-radius:4px; }
        .form-group { margin-bottom:12px; }
        .btn { background:#c00; color:#fff; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; }
        .btn-secondary { background:#555; }
        .msg { padding:10px; border-radius:6px; margin-bottom:12px; }
        .msg.error { background:#ffecec; color:#c00; }
        .msg.success { background:#e6ffed; color:#1a7f37; }
    </style>
</head>
<body>

<div class="editar-contenedor">
    <h1>Editar usuario</h1>

    <p><a href="admin.php">&larr; Volver al panel</a></p>

    <?php if ($error): ?>
        <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if ($usuario): ?>
        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="ci_usuario" value="<?php echo htmlspecialchars($usuario['ci_usuario']); ?>">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="role" required>
                    <option value="cliente" <?php echo ($current_role === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="barbero" <?php echo ($current_role === 'barbero') ? 'selected' : ''; ?>>Barbero</option>
                </select>
            </div>

            <div class="form-group">
                <label>Contraseña (dejar en blanco para mantener la actual)</label>
                <input type="password" name="contraseña" placeholder="Nueva contraseña">
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn">Guardar cambios</button>
                <a href="admin.php" class="btn btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">Cancelar</a>
            </div>
        </form>
    <?php else: ?>
        <p>Ingrese la cédula del usuario a editar en la URL, por ejemplo: <code>?ci=324723</code></p>
        <form method="GET" action="editar_usuario.php">
            <div class="form-group">
                <input type="number" name="ci" placeholder="Cédula (ci_usuario)" required>
            </div>
            <button type="submit" class="btn">Cargar usuario</button>
        </form>
    <?php endif; ?>

</div>

</body>
</html>

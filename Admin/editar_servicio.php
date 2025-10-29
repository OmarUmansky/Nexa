<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../Login/login.php');
    exit;
}

$error = '';
$service = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && isset($_POST['id_servicio'])) {
    $id = intval($_POST['id_servicio']);
    $nombre = trim($_POST['nombre_servicio'] ?? '');
    $tipo = trim($_POST['tipo_servicio'] ?? '');
    $precio = $_POST['precio'] ?? 0;
    $ci_empleado = trim($_POST['ci_usuario_empleado'] ?? '');

    if ($nombre === '') {
        $error = 'Nombre requerido.';
    } else {
        if ($ci_empleado === '') {
            $stmt = $db->prepare("UPDATE servicio SET precio = ?, tipo_servicio = ?, ci_usuario_empleado = NULL, nombre_servicio = ? WHERE id_servicio = ?");
            if ($stmt) $stmt->bind_param('issi', $precio, $tipo, $nombre, $id);
        } else {
            $ci_int = intval($ci_empleado);
            $stmt = $db->prepare("UPDATE servicio SET precio = ?, tipo_servicio = ?, ci_usuario_empleado = ?, nombre_servicio = ? WHERE id_servicio = ?");
            if ($stmt) $stmt->bind_param('isisi', $precio, $tipo, $ci_int, $nombre, $id);
        }

        if (!$stmt) {
            $error = 'Error en la consulta.';
        } else {
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: admin.php?msg=' . urlencode('Servicio actualizado correctamente.') . '&type=success');
                exit;
            } else {
                $error = 'Error al actualizar servicio: ' . htmlspecialchars($stmt->error);
            }
        }
    }
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['id_servicio']) && !isset($_POST['save'])) {
    $id = intval($_POST['id_servicio']);
} else {
    $id = 0;
}

if ($id > 0) {
    $stmt = $db->prepare("SELECT id_servicio, nombre_servicio, tipo_servicio, precio, ci_usuario_empleado FROM servicio WHERE id_servicio = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $service = $res->fetch_assoc();
        }
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Servicio</title>
  <link rel="stylesheet" href="admin.css">
  <style>.container{max-width:720px;margin:24px auto;padding:16px;background:#fff;border-radius:6px}</style>
</head>
<body>
<div class="container">
  <h1>Editar Servicio</h1>
  <p><a href="admin.php">&larr; Volver</a></p>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($service): ?>
    <form method="POST" action="editar_servicio.php">
      <input type="hidden" name="id_servicio" value="<?php echo htmlspecialchars($service['id_servicio']); ?>">
      <input type="hidden" name="save" value="1">

      <div class="form-group">
        <label>Nombre del servicio</label>
        <input type="text" name="nombre_servicio" value="<?php echo htmlspecialchars($service['nombre_servicio']); ?>" required>
      </div>
      <div class="form-group">
        <label>Tipo de servicio</label>
        <input type="text" name="tipo_servicio" value="<?php echo htmlspecialchars($service['tipo_servicio']); ?>">
      </div>
      <div class="form-group">
        <label>Precio (UYU)</label>
        <input type="number" name="precio" value="<?php echo htmlspecialchars($service['precio']); ?>">
      </div>
      <div class="form-group">
        <label>Barbero (opcional)</label>
        <?php
          // Obtener empleados para el select
          $empleados = $db->query("SELECT ci_usuario, nombre, apellido FROM empleado ORDER BY nombre ASC");
          $selected_ci = $service['ci_usuario_empleado'] ?? '';
        ?>
        <select name="ci_usuario_empleado">
          <option value="">-- Ninguno --</option>
          <?php if ($empleados && $empleados->num_rows > 0):
              while ($e = $empleados->fetch_assoc()):
                $ci_val = htmlspecialchars($e['ci_usuario']);
                $label = htmlspecialchars($e['nombre'] . ' ' . $e['apellido']);
          ?>
            <option value="<?php echo $ci_val; ?>" <?php echo ($ci_val == $selected_ci) ? 'selected' : ''; ?>><?php echo $label; ?></option>
          <?php endwhile; endif; ?>
        </select>
      </div>

      <div style="margin-top:12px;">
        <button type="submit" class="btn btn-agregar">Guardar</button>
        <a href="admin.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  <?php else: ?>
    <p>Servicio no encontrado. Vuelve al <a href="admin.php">panel</a>.</p>
  <?php endif; ?>
</div>
</body>
</html>

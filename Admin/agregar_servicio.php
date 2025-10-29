<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../Login/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_servicio'] ?? '');
    $tipo = trim($_POST['tipo_servicio'] ?? '');
    $precio = $_POST['precio'] ?? 0;
    $ci_empleado = trim($_POST['ci_usuario_empleado'] ?? '');

    if ($nombre === '') {
        $error = 'El nombre del servicio es requerido.';
    } else {
        
        if ($ci_empleado === '') {
            $stmt = $db->prepare("INSERT INTO servicio (precio, tipo_servicio, ci_usuario_empleado, nombre_servicio) VALUES (?, ?, NULL, ?)");
            if (!$stmt) {
                $error = 'Error en la consulta.';
            } else {
                $stmt->bind_param('iss', $precio, $tipo, $nombre);
            }
        } else {
            $ci_int = intval($ci_empleado);
            $stmt = $db->prepare("INSERT INTO servicio (precio, tipo_servicio, ci_usuario_empleado, nombre_servicio) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                $error = 'Error en la consulta.';
            } else {
                $stmt->bind_param('isis', $precio, $tipo, $ci_int, $nombre);
            }
        }

        if (empty($error)) {
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: admin.php?msg=' . urlencode('Servicio agregado correctamente.') . '&type=success');
                exit;
            } else {
                $error = 'Error al insertar servicio: ' . htmlspecialchars($stmt->error);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Agregar Servicio</title>
  <link rel="stylesheet" href="admin.css">
  <style>.container{max-width:720px;margin:24px auto;padding:16px;background:#fff;border-radius:6px}</style>
</head>
<body>
<div class="container">
  <h1>Agregar Servicio</h1>
  <p><a href="admin.php">&larr; Volver</a></p>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="agregar_servicio.php">
    <div class="form-group">
      <label>Nombre del servicio</label>
      <input type="text" name="nombre_servicio" required>
    </div>
    <div class="form-group">
      <label>Tipo de servicio</label>
      <input type="text" name="tipo_servicio">
    </div>
    <div class="form-group">
      <label>Precio (UYU)</label>
      <input type="number" name="precio" value="0">
    </div>
    <div class="form-group">
      <label>Barbero (opcional)</label>
      <?php
        // Obtener lista de empleados para mostrar en el select
        $empleados = $db->query("SELECT ci_usuario, nombre, apellido FROM empleado ORDER BY nombre ASC");
      ?>
      <select name="ci_usuario_empleado">
        <option value="">-- Ninguno --</option>
        <?php if ($empleados && $empleados->num_rows > 0):
            while ($e = $empleados->fetch_assoc()): ?>
              <option value="<?php echo htmlspecialchars($e['ci_usuario']); ?>"><?php echo htmlspecialchars($e['nombre'] . ' ' . $e['apellido']); ?></option>
        <?php endwhile; endif; ?>
      </select>
    </div>
    <div style="margin-top:12px;">
      <button type="submit" class="btn btn-agregar">Agregar</button>
      <a href="admin.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>

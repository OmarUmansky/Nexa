<?php
session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header('Location: ../Login/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];
$message = '';

if ($rol !== 'cliente') {
  $not_supported = true;
} else {
  $not_supported = false;
    $telefono = '';
    $ciudad = null;

    if ($stmt = $db->prepare("SELECT telefono, ciudad FROM cliente WHERE nombre = ? LIMIT 1")) {
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $stmt->bind_result($telefono_db, $ciudad_db);
        if ($stmt->fetch()) {
            $telefono = $telefono_db;
            $ciudad = $ciudad_db;
        }
        $stmt->close();
    }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevo_telefono = trim($_POST['telefono'] ?? '');
        $nuevo_ciudad = intval($_POST['ciudad'] ?? 0);
    if ($nuevo_telefono === '' || !preg_match('/^[0-9]{6,15}$/', $nuevo_telefono)) {
            $message = 'Ingresa un número de teléfono válido (solo dígitos, 6-15 caracteres).';
        } else {
  if ($check = $db->prepare('SELECT id_departamento FROM departamento WHERE id_departamento = ?')) {
        $check->bind_param('i', $nuevo_ciudad);
                $check->execute();
                $check->store_result();
                if ($check->num_rows === 0) {
                    $message = 'El departamento seleccionado no es válido.';
                } else {
                    // Update cliente
                    if ($upd = $db->prepare('UPDATE cliente SET telefono = ?, ciudad = ? WHERE nombre = ?')) {
                        $upd->bind_param('sis', $nuevo_telefono, $nuevo_ciudad, $usuario);
                        if ($upd->execute()) {
                            $message = 'Perfil actualizado correctamente.';
                            $telefono = $nuevo_telefono;
                            $ciudad = $nuevo_ciudad;
                        } else {
                            $message = 'Error al actualizar el perfil.';
                        }
                        $upd->close();
                    } else {
                        $message = 'Error en la consulta de actualización.';
                    }
                }
                $check->close();
            } else {
                $message = 'Error validando departamento.';
            }
        }
    }

  $departamentos = [];
    $res = $db->query('SELECT id_departamento, nombre FROM departamento ORDER BY nombre');
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $departamentos[] = $row;
        }
        $res->free();
    }
}

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar perfil</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    /* lightweight inline styles so it looks decent */
    .perfil-container { max-width:600px; margin:40px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.06);} 
    label { display:block; margin:10px 0 4px; }
    input, select { width:100%; padding:8px; border:1px solid #ddd; border-radius:4px }
    .actions { margin-top:14px; }
    .msg { margin:10px 0; padding:8px; border-radius:4px }
    .msg.success { background:#e6ffed; color:#0b6f3a }
    .msg.error { background:#ffecec; color:#7a1717 }
  </style>
</head>
<body>

<div class="perfil-container">
  <h2>Editar perfil</h2>
  <p>Hola, <strong><?php echo htmlspecialchars($usuario); ?></strong></p>

  <?php if ($not_supported): ?>
    <div class="msg error">La edición de perfil (teléfono y departamento) sólo está disponible para usuarios con rol <strong>cliente</strong>.</div>
    <div style="margin-top:10px; display:flex; align-items:center; gap:12px;">
      <div style="background:#fff4e5; color:#7a4a00; padding:6px 10px; border-radius:6px; font-weight:700;">Próximamente</div>
      <div>Próximamente se implementará un perfil para Administradores.</div>
    </div>
  <?php else: ?>

    <?php if ($message !== ''): ?>
      <div class="msg <?php echo (strpos($message, 'correctamente') !== false) ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <label for="telefono">Teléfono</label>
      <input id="telefono" name="telefono" type="text" value="<?php echo htmlspecialchars($telefono); ?>" required>

      <label for="ciudad">Departamento</label>
      <select id="ciudad" name="ciudad" required>
        <option value="">-- Selecciona tu departamento --</option>
        <?php foreach ($departamentos as $d): ?>
          <option value="<?php echo (int)$d['id_departamento']; ?>" <?php echo ($ciudad == $d['id_departamento']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['nombre']); ?></option>
        <?php endforeach; ?>
      </select>

      <div class="actions">
        <button type="submit">Guardar cambios</button>
        <a href="../index.php" style="margin-left:12px;">Volver</a>
      </div>
    </form>

  <?php endif; ?>

</div>

</body>
</html>

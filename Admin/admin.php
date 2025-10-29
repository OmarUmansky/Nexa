<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Panel de Admin</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <div class="sidebar">
  <h2>Panel Admin</h2>
  <div class="menu-group">
    <button id="userFuncsBtn" class="hamburger">Funciones Usuarios</button>
    <div id="userFuncsMenu" class="submenu">
      <a href="#" onclick="mostrarVentana('agregar')">Agregar usuario</a>
      <a href="#" onclick="mostrarVentana('quitar')">Quitar usuario</a>
      <a href="#" onclick="mostrarVentana('editar')">Editar usuario</a>
      <a href="#" onclick="mostrarVentana('usuarios')">Usuarios</a>
    </div>
  </div>
  <a href="#" onclick="mostrarVentana('servicios')">Servicios</a>
  <a href="#" onclick="mostrarVentana('citas')">Citas</a>
</div>

<div class="content">
  <div id="inicio" class="ventana-activa">
    <h1>Bienvenido administrador de M&L</h1>
    <p>Seleccione en la barra lateral en lo que quiere trabajar..</p>
  </div>

  <div id="agregar" class="ventana">
    <h1>Agregar usuarios</h1>
    <p>Aquí puedes agregar nuevos usuarios (barbero o cliente).</p>
    <form class="agregar" action="agregar.php" method="POST">
      <label for="ci_usuario">Cédula:</label>
      <input type="text" name="ci_usuario" id="ci_usuario" required>

      <label for="nombre">Nombre:</label>
      <input type="text" name="nombre" id="nombre" required>

      <label for="apellido">Apellido:</label>
      <input type="text" name="apellido" id="apellido" required>

      <label for="correo">Correo:</label>
      <input type="email" name="correo" id="correo" required>

      <label for="contraseña">Contraseña:</label>
      <input type="password" name="contraseña" id="contraseña" required>

      <label for="rol">Rol:</label>
      <select name="rol" id="rol" required>
        <option value="">-- Seleccione un rol --</option>
        <option value="barbero">Barbero (empleado)</option>
        <option value="cliente">Cliente</option>
      </select>

      <button type="submit">Agregar Usuario</button>
    </form>
  </div>

  <div id="citas" class="ventana">
    <h1>Lista de Citas</h1>
    <?php
    require_once "../db.php";

  // Traer citas junto con el nombre del servicio (si existe)
  $resultado = $db->query("SELECT c.*, s.nombre_servicio FROM citas c LEFT JOIN servicio s ON c.servicio = s.id_servicio ORDER BY c.fecha DESC");

    if ($resultado->num_rows > 0) {
        echo "<table class='tabla-barberos'>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Creado en</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>";

    // Map de servicios (fallback en caso de que no exista fila en la tabla `servicio`)
    $servicios_map = [
      '1' => 'Corte de cabello',
      '2' => 'Tintado',
      '3' => 'Tratamiento capilar',
      '4' => 'Lavado de cabello',
      '5' => 'Arreglo de barba',
      '6' => 'Brushing',
      '7' => 'Coloración',
      '8' => 'Claritos',
      '9' => 'Servicio maquillaje',
      '10' => 'Mantenimiento de cabello',
      '11' => 'Botox',
      '12' => 'Pelo dañado',
      '13' => 'Keratina',
      '14' => 'Baño de crema',
      '15' => 'Células madre',
      '16' => 'Tratamiento de ampollas'
    ];

    while ($cita = $resultado->fetch_assoc()) {
      // Mostrar nombre del servicio si está disponible desde el JOIN,
      // si no, buscar en el mapa por id; si tampoco existe, mostrar el valor crudo escapado.
      if (!empty($cita['nombre_servicio'])) {
        $serv_label = htmlspecialchars($cita['nombre_servicio']);
      } elseif (isset($servicios_map[(string)$cita['servicio']])) {
        $serv_label = htmlspecialchars($servicios_map[(string)$cita['servicio']]);
      } else {
        $serv_label = htmlspecialchars($cita['servicio']);
      }

            echo "<tr>
                    <td>{$cita['id']}</td>
                    <td>{$cita['usuario']}</td>
                    <td>" . $serv_label . "</td>
                    <td>{$cita['fecha']}</td>
                    <td>{$cita['creado_en']}</td>
                    <td class='acciones'>
                  <form method='POST' action='eliminar_cita.php' style='display:inline;'>
                    <input type='hidden' name='id' value='{$cita['id']}'>
                    <button type='submit' class='btn-rechazar'>Rechazar</button>
                  </form>
                    <form method='POST' action='aceptar_cita.php' style='display:inline;'>
                      <input type='hidden' name='id' value='{$cita['id']}'>
                      <button type='submit' class='btn-aceptar'>Aceptar</button>
                  </form>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No hay citas registradas.</p>";
    }
    ?>
  </div>

  <div id="quitar" class="ventana">
    <h1>Quitar usuarios</h1>
    <p>Acá puedes ver y quitar usuarios (clientes y barberos).</p>
    <?php
    require_once "../db.php";

    // Traer clientes y empleados
    $resClientes = $db->query("SELECT ci_usuario, nombre, apellido, correo FROM cliente ORDER BY nombre ASC");
    $resEmpleados = $db->query("SELECT ci_usuario, nombre, apellido, correo FROM empleado ORDER BY nombre ASC");

    echo "<table class='tabla-barberos'>";
    echo "<thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
          </thead>";
    echo "<tbody>";

    if ($resClientes && $resClientes->num_rows > 0) {
        while ($u = $resClientes->fetch_assoc()) {
            $ci = htmlspecialchars($u['ci_usuario']);
            $nombre = htmlspecialchars($u['nombre']);
            $apellido = htmlspecialchars($u['apellido']);
            $correo = htmlspecialchars($u['correo']);

            echo '<tr>';
            echo '<td>' . $ci . '</td>';
            echo '<td>' . $nombre . '</td>';
            echo '<td>' . $apellido . '</td>';
            echo '<td>' . $correo . '</td>';
            echo '<td>Cliente</td>';
            echo '<td>';
            echo '<form method="POST" action="quitar.php" onsubmit="return confirm(\'¿Eliminar cliente ' . $nombre . '?\');" style="display:inline;">';
            echo '<input type="hidden" name="ci_usuario" value="' . $ci . '">';
            echo '<input type="hidden" name="role" value="cliente">';
            echo '<button type="submit" class="btn-quitar">Eliminar</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
    }

    if ($resEmpleados && $resEmpleados->num_rows > 0) {
        while ($u = $resEmpleados->fetch_assoc()) {
            $ci = htmlspecialchars($u['ci_usuario']);
            $nombre = htmlspecialchars($u['nombre']);
            $apellido = htmlspecialchars($u['apellido']);
            $correo = htmlspecialchars($u['correo']);

            echo '<tr>';
            echo '<td>' . $ci . '</td>';
            echo '<td>' . $nombre . '</td>';
            echo '<td>' . $apellido . '</td>';
            echo '<td>' . $correo . '</td>';
            echo '<td>Barbero</td>';
            echo '<td>';
            echo '<form method="POST" action="quitar.php" onsubmit="return confirm(\'¿Eliminar barbero ' . $nombre . '?\');" style="display:inline;">';
            echo '<input type="hidden" name="ci_usuario" value="' . $ci . '">';
            echo '<input type="hidden" name="role" value="barbero">';
            echo '<button type="submit" class="btn-quitar">Eliminar</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
    }

    echo "</tbody></table>";
    ?>
  </div>

  <div id="editar" class="ventana">
  <h1>Editar usuarios</h1>
  <p>Acá puedes editar registros existentes. Haz click en "Editar" para abrir el formulario.</p>
  <?php
  require_once "../db.php";

  // Obtener usuarios de ambas tablas
  $clientes = $db->query("SELECT ci_usuario, nombre, apellido, correo FROM cliente ORDER BY nombre ASC");
  $empleados = $db->query("SELECT ci_usuario, nombre, apellido, correo FROM empleado ORDER BY nombre ASC");

  echo "<table class='tabla-barberos'>";
  echo "<thead><tr><th>Cédula</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acción</th></tr></thead><tbody>";

  if ($clientes && $clientes->num_rows > 0) {
    while ($u = $clientes->fetch_assoc()) {
      $ci = htmlspecialchars($u['ci_usuario']);
      $nombre = htmlspecialchars($u['nombre'] . ' ' . $u['apellido']);
      $correo = htmlspecialchars($u['correo']);
      echo "<tr><td>{$ci}</td><td>{$nombre}</td><td>{$correo}</td><td>Cliente</td><td><a class='btn-agregar' href='editar_usuario.php?ci={$ci}'>Editar</a></td></tr>";
    }
  }

  if ($empleados && $empleados->num_rows > 0) {
    while ($u = $empleados->fetch_assoc()) {
      $ci = htmlspecialchars($u['ci_usuario']);
      $nombre = htmlspecialchars($u['nombre'] . ' ' . $u['apellido']);
      $correo = htmlspecialchars($u['correo']);
      echo "<tr><td>{$ci}</td><td>{$nombre}</td><td>{$correo}</td><td>Barbero</td><td><a class='btn-agregar' href='editar_usuario.php?ci={$ci}'>Editar</a></td></tr>";
    }
  }

  echo "</tbody></table>";
  ?>
  </div>

  <div id="servicios" class="ventana">
  <h1>Servicios</h1>
  <p>Aquí puedes administrar los servicios existentes.</p>

  <?php
    require_once "../db.php";

    $resultado = $db->query("SELECT id_servicio, nombre_servicio, tipo_servicio, precio, ci_usuario_empleado FROM servicio");

    if ($resultado->num_rows > 0) {
        $empleado_map = [];
        $resEmp = $db->query("SELECT ci_usuario, nombre, apellido FROM empleado");
        if ($resEmp && $resEmp->num_rows > 0) {
            while ($e = $resEmp->fetch_assoc()) {
                $empleado_map[$e['ci_usuario']] = $e['nombre'] . ' ' . $e['apellido'];
            }
        }

        echo "<table class='tabla-barberos'>";
        echo "<thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre del Servicio</th>
                  <th>Tipo de Servicio</th>
                  <th>Precio (UYU)</th>
                  <th>Empleado</th>
                  <th>Acciones</th>
                </tr>
              </thead>";
        echo "<tbody>";
        while ($servicio = $resultado->fetch_assoc()) {
            $emp_ci = $servicio['ci_usuario_empleado'];
            $emp_label = '-';
            if (!empty($emp_ci)) {
                $emp_label = isset($empleado_map[$emp_ci]) ? htmlspecialchars($empleado_map[$emp_ci]) : htmlspecialchars($emp_ci);
            }

            echo "<tr>";
            echo "<td>{$servicio['id_servicio']}</td>";
            echo "<td>" . htmlspecialchars($servicio['nombre_servicio']) . "</td>";
            echo "<td>" . htmlspecialchars($servicio['tipo_servicio']) . "</td>";
            echo "<td>" . htmlspecialchars($servicio['precio']) . "</td>";
            echo "<td>{$emp_label}</td>";
            echo "<td class='acciones'>
                    <form method='POST' action='editar_servicio.php' style='display:inline;'>
                      <input type='hidden' name='id_servicio' value='" . htmlspecialchars($servicio['id_servicio']) . "'>
                      <button type='submit' class='btn-editar'>Editar</button>
                    </form>
                    <form method='POST' action='borrar_servicio.php' style='display:inline;'>
                      <input type='hidden' name='id_servicio' value='" . htmlspecialchars($servicio['id_servicio']) . "'>
                      <button type='submit' class='btn-quitar' style='background-color:#c00;'>Quitar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay servicios registrados.</p>";
    }
  ?>

  <div style="margin-top: 20px;">
    <a href='agregar_servicio.php' class='btn-agregar' style='padding:8px 12px; background-color:#0073e6; color:white; text-decoration:none; border-radius:5px;'>Agregar Servicio</a>
  </div>
</div>

  <div id="usuarios" class="ventana">
    <h1>Lista de Usuarios</h1>
    <p class="icon"><i class="fa-solid fa-magnifying-glass"></i><input class="filtro" type="text" id="buscadorUsuarios" placeholder="Buscar por rol o nombre"></p>
    <?php include("usuarios.php"); ?>
  </div>
</div>

<script src="admin.js"></script>
<?php if (isset($_GET['mensaje'])): ?>
  <script>
    (function(){
      var msg = "<?php echo htmlspecialchars($_GET['mensaje']); ?>";
      if (msg === 'cita_aceptada') {
        var serv = new URLSearchParams(window.location.search).get('servicio');
        if (serv) {
          alert('La cita se aceptó con éxito. Servicio: ' + serv);
        } else {
          alert('La cita se aceptó con éxito.');
        }
      } else if (msg === 'cita_eliminada') {
        alert('La cita fue eliminada.');
      }
    })();
  </script>
<?php endif; ?>
<script>
  // Añadir confirmación en los formularios de eliminación (rechazar cita)
  document.addEventListener('DOMContentLoaded', function(){
    var forms = document.querySelectorAll('form[action="eliminar_cita.php"]');
    forms.forEach(function(f){
      f.addEventListener('submit', function(e){
        if (!confirm('¿Desea rechazar esta cita?')) {
          e.preventDefault();
        }
      });
    });
  });
</script>
</body>
</html>

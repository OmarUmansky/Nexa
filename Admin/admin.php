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
  <a href="#" onclick="mostrarVentana('agregar')">Agregar</a>
  <a href="#" onclick="mostrarVentana('quitar')">Quitar</a>
  <a href="#" onclick="mostrarVentana('editar')">Editar</a>
  <a href="#" onclick="mostrarVentana('servicios')">Servicios</a>
  <a href="#" onclick="mostrarVentana('usuarios')">Usuarios</a>
  <a href="#" onclick="mostrarVentana('citas')">Citas</a>
</div>

<div class="content">
  <div id="inicio" class="ventana-activa">
    <h1>Bienvenido administrador de M&L</h1>
    <p>Seleccione en la barra lateral en lo que quiere trabajar..</p>
  </div>

  <div id="agregar" class="ventana">
    <h1>Agregar</h1>
    <p>Aquí puedes agregar nuevos barberos</p>
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

      <label for="horario_inc">Horario de entrada:</label>
      <input type="time" name="horario_inc" id="horario_inc" required>

      <label for="horario_fin">Horario de salida:</label>
      <input type="time" name="horario_fin" id="horario_fin" required>

      <button type="submit">Agregar Barbero</button>
    </form>
  </div>

  <div id="citas" class="ventana">
    <h1>Lista de Citas</h1>
    <?php
    require_once "../db.php";

    $resultado = $db->query("SELECT * FROM citas ORDER BY fecha DESC");

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

        while ($cita = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$cita['id']}</td>
                    <td>{$cita['usuario']}</td>
                    <td>{$cita['servicio']}</td>
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
    <h1>Quitar</h1>
    <p>Acá podes borrar registros</p>
    <?php
    require_once "../db.php";

    $resultado = $db->query("SELECT ci_usuario, nombre, apellido, correo FROM empleado");

    if ($resultado->num_rows > 0) {
        echo "<table class='tabla-barberos'>";
        echo "<thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Acción</th>
                </tr>
              </thead>";
        echo "<tbody>";
        while ($barbero = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$barbero['ci_usuario']}</td>";
            echo "<td>{$barbero['nombre']}</td>";
            echo "<td>{$barbero['apellido']}</td>";
            echo "<td>{$barbero['correo']}</td>";
            echo "<td> 
                    <form method='POST' action='quitar.php' style='display:inline;'>
                        <input type='hidden' name='ci_usuario' value='{$barbero['ci_usuario']}'>
                        <button type='submit'>Eliminar</button> 
                    </form>
                  </td>";
            echo "</tr>";
        } 
        echo "</tbody></table>";
    } else {
        echo "<p>No hay barberos registrados </p>";
    }
    ?>
  </div>

  <div id="editar" class="ventana">
    <h1>Editar</h1>
    <p>Acá puedes editar registros existentes</p>
  </div>

  <div id="servicios" class="ventana">
  <h1>Servicios</h1>
  <p>Aquí puedes administrar los servicios existentes.</p>

  <?php
    require_once "../db.php";

    $resultado = $db->query("SELECT id_servicio, nombre_servicio, tipo_servicio, precio, ci_usuario_empleado FROM servicio");

    if ($resultado->num_rows > 0) {
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
            echo "<tr>";
            echo "<td>{$servicio['id_servicio']}</td>";
            echo "<td>{$servicio['nombre_servicio']}</td>";
            echo "<td>{$servicio['tipo_servicio']}</td>";
            echo "<td>{$servicio['precio']}</td>";
            echo "<td>{$servicio['ci_usuario_empleado']}</td>";
            echo "<td class='acciones'>
                    <form method='POST' action='editar_servicio.php' style='display:inline;'>
                      <input type='hidden' name='id_servicio' value='{$servicio['id_servicio']}'>
                      <button type='submit' class='btn-editar'>Editar</button>
                    </form>
                    <form method='POST' action='borrar_servicio.php' style='display:inline;'>
                      <input type='hidden' name='id_servicio' value='{$servicio['id_servicio']}'>
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
</body>
</html>

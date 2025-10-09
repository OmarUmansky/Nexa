<?php
require_once "../db.php"; // conecta a la base de datos

// toma los empleados y clientes y los etiqueta segun que son y une los resultados en una lista y la ordena por cédula
$sql = " 
  SELECT ci_usuario, nombre, correo, 'Empleado' as rol FROM empleado
  UNION
  SELECT ci_usuario, nombre, correo, 'Cliente' as rol FROM cliente
  ORDER BY ci_usuario
";

//se ejecuta la cosnulta
$resultado = $db->query($sql);

//verufuca su hay resultados, si es mayor a 1 se muestra la tabla y se construye una tabla de html con los encabezados
if ($resultado && $resultado->num_rows > 0) {
    echo "<table class='tabla-usuarios'>";
    echo "<thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
            </tr>
          </thead>";
    echo "<tbody>";

    //recorre los resultados uno x uno y los imprime en la tabla
    while ($usuario = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$usuario['ci_usuario']}</td>";
        echo "<td>{$usuario['nombre']}</td>";
        echo "<td>{$usuario['correo']}</td>";
        echo "<td>{$usuario['rol']}</td>";
        echo "</tr>";
    }
    echo "</tbody></table>"; // se cierra la tabla
} else { // mensje que sale si no hay usuarios registrados
    echo "<p>No hay usuarios registrados</p>";
}
?>

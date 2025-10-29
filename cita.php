<?php
session_start();
require __DIR__ . '/db.php';

$departamento_name = '';
if (isset($_SESSION['usuario'])) {
    $usuario_for_dep = $_SESSION['usuario'];
    if ($stmt = $db->prepare('SELECT d.nombre FROM cliente c JOIN departamento d ON c.ciudad = d.id_departamento WHERE c.nombre = ? LIMIT 1')) {
        $stmt->bind_param('s', $usuario_for_dep);
        $stmt->execute();
        $stmt->bind_result($dep_name_db);
        if ($stmt->fetch()) {
            $departamento_name = $dep_name_db;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&L | Agendar cita</title>
    <link rel="stylesheet" href="cita.css">
</head>
<body>
    <div class="contenedor">
        <div class="contenedor2">
            <div class="left">
                <img src="assets/fondou.png" alt="">
            </div>
    
            <div class="right">
                <div class="login">
                    <h2>Agendar cita</h2>
                    <form action="Admin/guardar_cita.php" method="POST">
                        <div class="input-icon">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="Nombre" placeholder="Usuario"
                                value="<?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : ''; ?>"
                                <?php echo isset($_SESSION['usuario']) ? 'readonly' : ''; ?>>
                        </div>

                        <div class="input-icon">
                            <i class="fa-solid fa-building"></i>
                            <input type="text" name="Departamento" placeholder="Departamento"
                                value="<?php echo htmlspecialchars($departamento_name); ?>" readonly>
                        </div>
                          
                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                            
                            <h3>Seleccione el servicio</h3>

                            <div class="botones-servicio">
                                <button type="button" class="btn-servicio">Barbería</button>
                                <button type="button" class="btn-servicio">Peluquería</button>
                            </div>

                            <select name="Servicio" id="servicio">
                                <option value="1">Corte de cabello</option>
                                <option value="2">Tintado</option>
                                <option value="3">Tratamiento capilar</option>
                                <option value="4">Lavado de cabello</option>
                                <option value="5">Arreglo de barba</option>
                                <option value="6">Brushing</option>
                                <option value="7">Coloración</option>
                                <option value="8">Claritos</option>
                                <option value="9">Servicio maquillaje</option>
                                <option value="10">Mantenimiento de cabello</option>
                                <option value="11">Botox</option>
                                <option value="12">Pelo dañado</option>
                                <option value="13">Keratina</option>
                                <option value="14">Baño de crema</option>
                                <option value="15">Celulas madre</option>
                                <option value="16">Tratamiento de ampollas</option>
                            </select>

                            <h3>Fecha de la cita</h3>
                            <input type="datetime-local" name="fecha">
                        </div>
                        <button type="submit">Envíar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="cita.js"></script>
</html>

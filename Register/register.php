<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="contenedor">
        <div class="contenedor2">
            <img src="../assets/ChatGPT Image 28 ago 2025, 08_05_41 p.m..png" alt="">
            <div class="login">
                <h2>Registrarse</h2>
                <div class="contenedor3">

                    <div class="right1">
                        <form action="registerl.php" method="POST">
                        
                            <div class="form-grid">
                                <input type="number" name="ci_usuario" placeholder="Cédula / Documento" required>
                                <input type="email" name="correo" placeholder="Correo electrónico" required>
                                <input type="tel" name="telefono" placeholder="Número de telefono" required>
                                <input type="text" name="ciudad" placeholder="Ciudad">
                            </div>
        
                            <div class="form-row">
                                <input type="text" name="nombre" placeholder="Nombre" required>
                                <input type="text" name="apellido" placeholder="Apellido" required>
                                <input type="password" name="contraseña" placeholder="Contraseña" required>
                            </div>
        
                            <button type="submit">Registrarse</button>
        
                            <div class="links">
                                <span>¿Ya tienes una cuenta? <a href="../Login/login.php">Inicia sesión</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="register.js"></script>
</body>
</html>

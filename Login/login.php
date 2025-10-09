<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <div class="contenedor">
        <div class="contenedor2">
            <div class="left">
                <img src="../assets/fondou.png" alt="">
            </div>
    
            <div class="right">
                <div class="login">
                    <h2>Iniciar sesion</h2>
                    <form action="logil.php" method="POST">
                        <div class="input-icon">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="nombre" placeholder="Nombre de usuario">
                          </div>
                          
                          <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="contraseña" placeholder="Contraseña">
                          </div>
                        <button type="submit">Iniciar sesión</button>
        
                        <div class="links">
                            <a href="#">¿Olvidaste tu contraseña?</a>
                            <span>¿No tienes una cuenta? <a href="../Register/register.php">Regístrate</a></span>
                          </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
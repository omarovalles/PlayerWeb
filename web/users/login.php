<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../design/users/stylelogin.css">
    <link rel="icon" href="../../resources/favicon.ico" type="image/x-icon">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <form action="../../controllers/sessions/iniciar_sesion.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
    <p class="register-link">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</body> 
</html>
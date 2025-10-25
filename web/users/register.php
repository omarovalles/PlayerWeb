<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Soreweb</title>
    <link rel="stylesheet" href="../../design/users/styleregister.css">
</head>
<body>
    <div class="register-container">
        <h1>Crear Cuenta</h1>
        <form action="../../controllers/admin/registrar_usuario.php" method="post">
            <label for="nombre">Usuario:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrar</button>
        </form>
        <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>

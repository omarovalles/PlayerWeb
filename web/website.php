<?php

require_once '../library/motor.php';


// VERIFICACION DE ROL 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php"); 
}
$usuarioid = $_SESSION['usuario_id'];

$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);

$rol = $resultado[0]->rol ?? 'U';  

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../design/stylewebsite.css">
    <title>SoreWeb</title>
    <script src="../js/website.js" defer></script>

</head>
<body>
    <audio id="miAudio" src="../resources/videoplayback.mp4" loop></audio>
    <nav class="navigation">
        <ul>
            <li><a href="website.php">Inicio</a></li>
            <li><a href="games/games.php">Juegos</a></li>
            <li><a href="anime/animes.php">Animes</a></li>
            <li><a href="contacts/about.php">Sobre mí</a></li>
            <li><a href="contacts/contactame.php">Contacto</a></li>
             <?php if ($usuarioid): ?>
                        <li><a href="../web/perfil.php?id=<?=$usuarioid?>">Perfil</a></li>
                        <li><a href="../controllers/logout.php">Salir</a></li>
                    <?php else: ?>
                        <li><a href="../web/login.php">Iniciar sesión</a></li>
                        <li><a href="../web/register.php">Registrarse</a></li>
                    <?php endif; ?>
            <?php if ($rol === 'A'): ?>
                <li><a href="users_admin.php">Administracion</a></li>
            <?php endif; ?>
            <li><img id="mutearMusica" src="../resources/speaker.svg" alt="speak logo"></li>
        </ul>
    </nav>
    <main class="grandcontainer">
        <div class="containerwebsite">
            <p id="miParrafo">Entrar</p>
        
        </div>
    </main>
    
    <footer>
        <p>&copy; 2023 SoreWeb. Todos los derechos reservados.</p>
        <p>Desarrollado por <a href="github.com/fullmaps">rin_nightVT</a></p>
    </footer>
</body>

</html>
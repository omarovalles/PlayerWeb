<?php
require_once '../library/motor.php';
Plantilla::aplicar(); 


if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php"); 
    exit;
}

$usuarioid = $_SESSION['usuario_id'];

$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);
$rol = $resultado[0]->rol ?? 'U';  

$animes = conexion::consulta("SELECT id, titulo, descripcion, imagen FROM animes ORDER BY creado_en DESC");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../design/styleanime.css">
    <title>SoreWeb - Animes</title>
</head>

<body>
    <div class="container">
        <h2>Bienvenido a la sección de Animes</h2>
        <h5>Aquí descubrirás una colección de historias épicas y mundos mágicos.</h5>
    </div>
    
    <?php if ($rol === 'A'): ?>
        <div style="text-align:center;">
            <a class="btn-deltarune" href="animes_admin.php">Registrar Anime</a>
        </div>
    <?php endif; ?>

    <div class="grid">
        <?php foreach ($animes as $anime): ?>
            <div class="anime-item">
                <a href="detalle_anime.php?id=<?= $anime->id ?>">
                    <img src="../resources/<?= htmlspecialchars($anime->imagen) ?>" 
                         alt="<?= htmlspecialchars($anime->titulo) ?>">
                </a>
                <h5><?= htmlspecialchars($anime->titulo) ?></h5>
            </div>
        <?php endforeach; ?>
    </div>
</body>

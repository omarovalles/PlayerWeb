<?php
require_once '../library/motor.php';
Plantilla::aplicar();


// VERIFICAR SESIÓN Y ID DEL ANIME
if (!isset($_GET['id'])) {
    die("Anime no especificado.");
}

$id = intval($_GET['id']);

// OBTENER DETALLES DEL ANIME
$sql = "SELECT * FROM animes WHERE id = :id";
$resultado = conexion::consulta($sql, [':id' => $id]);

$anime = $resultado[0] ?? null;

// VERIFICAR QUE EXISTA EL ANIME
if (!$anime) {
    die("Anime no encontrado.");
}

$usuarioid = $_SESSION['usuario_id'] ?? 0;

// VERIFICAR ROL
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);

$rol = $resultado[0]->rol ?? 'U';  

// CARGAR COMENTARIOS DEL ANIME
$sql = "SELECT c.*, u.nombre
        FROM comentarios c
        INNER JOIN usuarios u ON c.usuario_id = u.id
        WHERE c.contenido_id = :id AND c.tipo_contenido = 'anime'
        ORDER BY c.creado_en DESC";
$comentarios = conexion::consulta($sql, [':id' => $id]);

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($anime->titulo) ?> - SoreWeb</title>
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../design/detalles_anime.css">
    <script src="../js/detalles_anime.js" defer></script>
</head>
<body>
    <div class="game-card">
        <?php if ($anime->imagen): ?>
            <img id="image-clickable" src="../resources/<?= htmlspecialchars($anime->imagen) ?>" alt="<?= htmlspecialchars($anime->titulo) ?>">
        <?php endif; ?>

        <div class="game-content">
            <h2><?= htmlspecialchars($anime->titulo) ?></h2>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($anime->descripcion)) ?></p>
            
            <p><strong>Capítulos:</strong> <?= htmlspecialchars($anime->capitulos) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($anime->estado) ?></p>
            <p><strong>Fecha de publicación:</strong> <?= htmlspecialchars($anime->creado_en) ?></p>
            
            <div class="rating">
                <strong>Rating:</strong> 
                <?php if ($anime->rating_custom): ?>
                    <?php for ($i = 0; $i < $anime->rating; $i++): ?>
                        <img src="<?= htmlspecialchars($anime->rating_custom) ?>" alt="Custom Rating" style="width:20px;height:20px;">
                    <?php endfor; ?>
                <?php else: ?>
                    <?php for ($i = 0; $i < $anime->rating; $i++): ?>
                        ⭐
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="animes.php" class="btn-deltarune">Volver al catálogo</a>
        <?php if ($rol === 'A'): ?>
            <a class="btn-deltarune" href="animes_admin.php?id=<?= $anime->id ?>">Editar</a>
            <a class="btn-deltarune" href="../controllers/eliminar_anime.php?id=<?= $anime->id ?>" onclick="return confirm('¿Seguro que quieres eliminar este anime?')">Eliminar</a>
        <?php endif; ?>
    </div>

    <div class="comment-box">
    <h3>💬 Deja tu comentario</h3>
    <form method="post" action="../controllers/guardar_comentario.php?id=<?= $anime->id ?>&tipo=anime">
        <textarea name="comentario" placeholder="Escribe algo..." required></textarea>
        <button type="submit">Publicar</button>
    </form>
</div>

<div class="comment-list">
        <h3>Comentarios</h3>
    <?php if ($comentarios): ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comment">
                <strong><?= htmlspecialchars($comentario->nombre) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($comentario->comentario)) ?></p>
                <small style="color:gray;">Publicado: <?= htmlspecialchars($comentario->creado_en) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;color:white;">No hay comentarios aún. ¡Sé el primero en comentar!</p>
    <?php endif; ?>
</div>
</body>

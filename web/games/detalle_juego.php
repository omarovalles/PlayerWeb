<?php
require_once '../library/motor.php';
Plantilla::aplicar();


// VERIFICAR SESIÓN
if (!isset($_GET['id'])) {
    die("Juego no especificado.");
}

$id = intval($_GET['id']);

// OBTENER DETALLES DEL JUEGO
$sql = "SELECT * FROM juegos WHERE id = :id";
$resultado = conexion::consulta($sql, [':id' => $id]);

$juego = $resultado[0] ?? null;

// VERIFICADOR DEL JUEGO
if (!$juego) {
    die("Juego no encontrado.");
}

$usuarioid = $_SESSION['usuario_id'];

// VERIFICAR ROL
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);

$rol = $resultado[0]->rol ?? 'U';  

// CARGAR COMENTARIOS DEL JUEGO
$sql = "SELECT c.*, u.nombre
        FROM comentarios c
        INNER JOIN usuarios u ON c.usuario_id = u.id
        WHERE c.contenido_id = :id AND c.tipo_contenido = 'juego'
        ORDER BY c.creado_en DESC";

$comentarios = conexion::consulta($sql, [':id' => $id]);

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($juego->nombre) ?> - SoreWeb</title>
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../design/styledetalles.css">
    <script src="../js/detalles_juego.js" defer></script>
</head>
<body>
    <div class="game-card">
        <?php if ($juego->imagen): ?>
            <img id="image-clickable" src="../resources/<?= htmlspecialchars($juego->imagen) ?>" alt="<?= htmlspecialchars($juego->nombre) ?>">
        <?php endif; ?>

        <div class="game-content">
            <h2><?= htmlspecialchars($juego->nombre) ?></h2>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($juego->descripcion)) ?></p>
            
            <p><strong>Horas jugadas:</strong> <?= htmlspecialchars($juego->horas) ?></p>
            <p><strong>Dificultad:</strong> <?= htmlspecialchars($juego->dificultad) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($juego->estado) ?></p>
            <p><strong>Fecha de esta publicacion:</strong> <?= htmlspecialchars($juego->creado_en) ?></p>
            
            <div class="rating">
                <strong>Rating:</strong> 
                    <?php if ($juego->rating_custom): ?>
                        <?php for ($i=0; $i<$juego->rating; $i++): ?>
                            <img src="<?= htmlspecialchars($juego->rating_custom) ?>" alt="Custom Rating" style="width:20px;height:20px;">
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php for ($i=0; $i<$juego->rating; $i++): ?>
                          ⭐
                        <?php endfor; ?>
                    <?php endif; ?>
            </div>
        </div>
    </div>

<div class="text-center">
    <a href="games.php" class="btn-deltarune">Volver al catálogo</a>
    <?php if ($rol === 'A'): ?>
        <a class="btn-deltarune" href="games_admin.php?id=<?= $juego->id ?>">Editar</a>
        <a class="btn-deltarune" href="../controllers/eliminar_juego.php?id=<?= $juego->id ?>" onclick="return confirm('¿Seguro que quieres eliminar este juego?')">Eliminar</a>
    <?php endif; ?>
</div>
 
<div class="comment-box">
    <h3>💬 Deja tu comentario</h3>
    <form method="post" action="../controllers/guardar_comentario.php?id=<?= $juego->id ?>&tipo=juego">
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
</html>

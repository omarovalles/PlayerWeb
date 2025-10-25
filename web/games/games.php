<?php
require_once '../../library/motor.php';
Plantilla::aplicar(); 

// Verificar rol
$usuarioid = $_SESSION['usuario_id'];
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);
$rol = $resultado[0]->rol ?? 'U';  

// Capturar búsqueda
$busqueda = trim($_GET['q'] ?? '');

// Consulta con filtración
if ($busqueda !== '') {
    $juegos = conexion::consulta(
        "SELECT id, nombre, descripcion, imagen 
         FROM juegos 
         WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda
         ORDER BY creado_en DESC",
        [':busqueda' => "%$busqueda%"]
    );
} else {
    $juegos = conexion::consulta("SELECT id, nombre, descripcion, imagen FROM juegos ORDER BY creado_en DESC");
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../design/games/stylegames.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>SoreWeb</title>
</head>

<body>
    <div class="container">
        <h2>¡Bienvenido a la sección de juegos de Sore! </h2>
        <h5>Aquí encontrarás una selección de juegos emocionantes para disfrutar.</h5>
    </div>
    
    <!-- 🔎 Buscador -->
    <form method="get" class="text-center" style="margin-left:50px 0;">
        <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" 
               placeholder="Buscar juego..." 
               style="padding:8px;width:50%;border-radius:8px;">
        <button type="submit" class="btn-deltarune">Buscar</button>
    </form>

    <?php if ($rol === 'A'): ?>
        <div class="btn-container" style="text-align:center;">
            <a class="btn-deltarune" href="games_admin.php">Registrar Juego</a>
        </div>
    <?php endif; ?>

    <div class="grid">
        <?php if ($juegos): ?>
            <?php foreach ($juegos as $juego): ?>
                <div class="game-item">
                    <a href="detalle_juego.php?id=<?= $juego->id ?>">
                        <img src="../../resources/<?= htmlspecialchars($juego->imagen) ?>" 
                             alt="<?= htmlspecialchars($juego->nombre) ?>">
                    </a>
                    <h5 style="text-align:center; color:white; margin-top:10px;">
                        <?= htmlspecialchars($juego->nombre) ?>
                    </h5>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;color:white;">No se encontraron juegos.</p>
        <?php endif; ?>
    </div>
</body>

<?php
require_once '../library/motor.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php"); 
    exit;
}

$usuarioid = $_SESSION['usuario_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "⚠️ ID de usuario no válido.";
    exit;
}

$idPerfil = (int)$_GET['id'];

// Consultar usuario + perfil
$sql = "SELECT u.id, u.nombre, u.correo, u.fecha_creacion, u.activo, u.rol,
               p.descripcion, p.foto, p.background, p.carta, p.color_letra, p.color_caja
        FROM usuarios u
        LEFT JOIN perfil p ON u.id = p.usuario_id
        WHERE u.id = ?";
$resultado = conexion::consulta($sql, [$idPerfil]);

if (count($resultado) === 0) {
    echo "⚠️ Usuario no encontrado.";
    exit;
}

$usuario = $resultado[0];
?>

<link rel="stylesheet" href="../design/perfil.css">
<script src="../js/detalles_anime.js" defer></script>
<style>
main {
    background-image: url('<?php echo !empty($usuario->background) ? $usuario->background : "../resources/malditagatasore.png"; ?>') !important;
    background-size: cover !important;
    background-position: center !important;
}
</style>

<?php Plantilla::aplicar(); ?>

<section class="perfil-container" 
         style="background-color: <?php echo $usuario->carta ?? '#111111'; ?>;
                color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
                border: 2px solid <?php echo $usuario->color_letra ?? '#e0ffe0';?>;">
    <h1 class="perfil-titulo"><?php echo htmlspecialchars($usuario->nombre); ?></h1>

    <div class="perfil-card" 
         style="background-color: <?php echo $usuario->color_caja ?? '#1a1f1a'?> ;
        color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        border: 2px solid <?php echo $usuario->color_letra ?? '#e0ffe0';?>;">

        <img id="image-clickable"src="<?php echo $usuario->foto ?: '../resources/gonermaker.png'; ?>" 
             alt="Foto de perfil" class="perfil-avatar"
             style="border: 2px solid <?php echo $usuario->color_letra ?? '#e0ffe0';?>;"
             >

        <p style="color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        "><strong>Correo:</strong> <?php echo htmlspecialchars($usuario->correo); ?></p>
        <p style="color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        "><strong>Rol:</strong> <?php echo ($usuario->rol === 'A') ? "Administrador" : "Usuario"; ?></p>
        <p style="color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        "><strong>Estado:</strong> <?php echo $usuario->activo ? "Activo " : "Inactivo "; ?></p>
        <p style="color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        "><strong>Miembro desde:</strong> <?php echo $usuario->fecha_creacion; ?></p>

        <?php if (!empty($usuario->descripcion)): ?>
            <p style="color: <?php echo $usuario->color_letra ?? '#e0ffe0'; ?>;
        "><strong>Acerca de mí:</strong> <?php echo htmlspecialchars($usuario->descripcion); ?></p>
        <?php endif; ?>
    </div>

    <?php if ($usuarioid === $idPerfil): ?>
        <a class="btn-deltarune" href="personalizar.php?id=<?php echo $idPerfil; ?>">
            Personalizar perfil
        </a>
    <?php endif; ?>
</section>

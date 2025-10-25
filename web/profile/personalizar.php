<?php
require_once '../library/motor.php';
session_start();

// Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php"); 
    exit;
}

$usuarioid = $_SESSION['usuario_id'];

// Validar ID en URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de usuario no válido.";
    exit;
}

$idUsuarioPerfil = (int)$_GET['id'];

// Solo puedes personalizar tu propio perfil
if ($usuarioid !== $idUsuarioPerfil) {
    echo "No tienes permiso para editar este perfil.";
    exit;
}

// Buscar perfil existente o crear uno vacío si no existe
$sql = "SELECT * FROM perfil WHERE usuario_id = ?";
$resultado = conexion::consulta($sql, [$idUsuarioPerfil]);
$perfil = (count($resultado) > 0) ? $resultado[0] : null;

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'] ?? '';

    // Manejo de foto de perfil
    $foto = $perfil ? $perfil->foto : null;
    if (!empty($_FILES['foto']['name'])) {
        $ruta = "../resources/" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        $foto = $ruta;
    }

    // Manejo de fondo
    $background = $perfil ? $perfil->background : null;
    if (!empty($_FILES['background_file']['name'])) {
        $rutaFondo = "../resources/" . basename($_FILES['background_file']['name']);
        move_uploaded_file($_FILES['background_file']['tmp_name'], $rutaFondo);
        $background = $rutaFondo;
    }

    // Manejo de color de carta, letra y caja interna
    $carta = $_POST['colorCarta'] ?? ($perfil->carta ?? '#111111');
    $color_letra = $_POST['color_letra'] ?? ($perfil->color_letra ?? '#e0ffe0');
    $color_caja  = $_POST['color_caja']  ?? ($perfil->color_caja  ?? '#1a1f1a');

    // Guardar en la base de datos
    if ($perfil) {
        $sql = "UPDATE perfil SET descripcion = ?, foto = ?, background = ?, carta = ?, color_letra = ?, color_caja = ? WHERE usuario_id = ?";
        conexion::consulta($sql, [$descripcion, $foto, $background, $carta, $color_letra, $color_caja, $idUsuarioPerfil]);
    } else {
        $sql = "INSERT INTO perfil (usuario_id, descripcion, foto, background, carta, color_letra, color_caja) VALUES (?, ?, ?, ?, ?, ?, ?)";
        conexion::consulta($sql, [$idUsuarioPerfil, $descripcion, $foto, $background, $carta, $color_letra, $color_caja]);
    }

    header("Location: perfil.php?id=" . $idUsuarioPerfil);
    exit;
}
?>

<link rel="stylesheet" href="../design/perfil.css">
<style>
main {
    background: <?php 
        if (!empty($perfil->background)) {
            echo "url('{$perfil->background}') center/cover no-repeat";
        } else {
            echo "linear-gradient(135deg, #1b1b2f, #2c2c54)";
        }
    ?> !important;
}
</style>

<?php
Plantilla::aplicar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../design/personalizar.css">
<script src="../js/personalizar.js" defer></script>
<title>Personalizar Perfil</title>
</head>
<body>

<section class="perfil-container" style="background-color: <?php echo $perfil->carta ?? '#111111'; ?>; color: <?php echo $perfil->color_letra ?? '#e0ffe0'; ?>;">

    <h1 class="perfil-titulo">Personalizar Perfil</h1>

    <form action="" method="POST" enctype="multipart/form-data" class="perfil-card" style="background-color: <?php echo $perfil->color_caja ?? '#1a1f1a'; ?>;">

        <label>Nombre</label>
        <input type="text" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?>" disabled>

        <label>Descripción</label>
        <textarea name="descripcion" rows="4"><?php echo $perfil->descripcion ?? ''; ?></textarea>

        <label>Foto de perfil</label>
        <input type="file" name="foto">
        <?php if (!empty($perfil->foto)): ?>
            <img src="<?php echo $perfil->foto; ?>" alt="Foto actual" width="120">
        <?php endif; ?>

        <label>Fondo de pantalla</label>
        <input type="file" name="background_file">
        <?php if (!empty($perfil->background)): ?>
            <img src="<?php echo $perfil->background; ?>" alt="Fondo actual" style="width:200px; margin-top:10px;">
        <?php endif; ?>

        <label>Color de carta</label>
        <input type="color" name="colorCarta" id="color-carta" value="<?php echo $perfil->carta ?? '#111111'; ?>">

        <label>Color de letra</label>
        <input type="color" name="color_letra" value="<?php echo $perfil->color_letra ?? '#e0ffe0'; ?>">

        <label>Color de caja interna (.perfil-card)</label>
        <input type="color" name="color_caja" value="<?php echo $perfil->color_caja ?? '#1a1f1a'; ?>">

        <button type="submit" class="btn-deltarune">Guardar cambios</button>
    </form>
</section>

</body>
</html>

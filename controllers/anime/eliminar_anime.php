<?php
require_once '../library/motor.php';
Plantilla::aplicar();

session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}

$usuarioid = $_SESSION['usuario_id'];

// Verificar rol
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);
$rol = $resultado[0]->rol ?? 'U';

if ($rol !== 'A') {
    die("Solo administradores pueden eliminar animes.");
}

// Verificar ID del anime
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Anime no especificado.");
}

$id = intval($_GET['id']);

// Obtener anime para borrar imagen
$anime = conexion::consulta("SELECT imagen FROM animes WHERE id = :id", [':id' => $id])[0] ?? null;

if (!$anime) {
    die("Anime no encontrado.");
}

// Borrar registro
conexion::exec("DELETE FROM animes WHERE id = :id", [':id' => $id]);

// Borrar imagen si existe
if (!empty($anime->imagen) && file_exists(__DIR__ . '/../resources/' . $anime->imagen)) {
    unlink(__DIR__ . '/../resources/' . $anime->imagen);
}

header("Location: ../web/animes.php");
exit;
?>

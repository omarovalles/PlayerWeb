<?php
require_once '../library/motor.php';
Plantilla::aplicar();

session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}

// Verificar rol (solo admin puede borrar)
$usuarioid = $_SESSION['usuario_id'];
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);
$rol = $resultado[0]->rol ?? 'U';

if ($rol !== 'A') {
    die("No tienes permisos para eliminar juegos.");
}

// Verificar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID no válido.");
}
$id = intval($_GET['id']);

// Buscar el juego
$juego = conexion::consulta("SELECT * FROM juegos WHERE id = :id", [':id' => $id]);
$juego = $juego[0] ?? null;

if (!$juego) {
    die("Juego no encontrado.");
}

// Eliminar imagen asociada si existe
if (!empty($juego->imagen)) {
    $rutaImagen = realpath(__DIR__ . '/../resources/' . $juego->imagen);
    if ($rutaImagen && file_exists($rutaImagen)) {
        unlink($rutaImagen);
    }
}

// Eliminar el juego de la base de datos
conexion::exec("DELETE FROM juegos WHERE id = :id", [':id' => $id]);

// Redirigir
header("Location: ../web/games.php");
exit;
?>

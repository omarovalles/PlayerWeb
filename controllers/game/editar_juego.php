<?php
require_once '../library/motor.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso no válido.");
}

$id = intval($_POST['id']);
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$horas = $_POST['horas'];
$dificultad = $_POST['dificultad'];
$estado = $_POST['estado'];
$rating = $_POST['rating'];

// Manejo de imagen
$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $archivo = basename($_FILES['imagen']['name']);
    $rutaDestino = "../resources/" . $archivo;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = $archivo;
    }
}

// Construir consulta
if ($imagen) {
    $sql = "UPDATE juegos 
            SET nombre = :nombre, descripcion = :descripcion, horas = :horas, 
                dificultad = :dificultad, estado = :estado, rating = :rating, imagen = :imagen 
            WHERE id = :id";
    $params = [
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':horas' => $horas,
        ':dificultad' => $dificultad,
        ':estado' => $estado,
        ':rating' => $rating,
        ':imagen' => $imagen,
        ':id' => $id
    ];
} else {
    $sql = "UPDATE juegos 
            SET nombre = :nombre, descripcion = :descripcion, horas = :horas, 
                dificultad = :dificultad, estado = :estado, rating = :rating 
            WHERE id = :id";
    $params = [
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':horas' => $horas,
        ':dificultad' => $dificultad,
        ':estado' => $estado,
        ':rating' => $rating,
        ':id' => $id
    ];
}

conexion::exec($sql, $params);

// Redirigir al detalle del juego
header("Location: game_detalle.php?id=$id");
exit;

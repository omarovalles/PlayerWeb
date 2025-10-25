<?php
require_once '../library/motor.php';
Plantilla::aplicar();

$errores = [];
$exito = null;

// --- Verificar si estamos editando ---
$editando = false;
$juego = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $editando = true;
    $id = intval($_GET['id']);
    $resultado = conexion::consulta("SELECT * FROM juegos WHERE id = :id", [':id' => $id]);
    $juego = $resultado[0] ?? null;

    if (!$juego) {
        die("Juego no encontrado.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = intval($_POST['id'] ?? 0);
    $nombre        = trim($_POST['nombre'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');
    $horas         = trim($_POST['horas'] ?? '');
    $dificultad    = trim($_POST['dificultad'] ?? '');
    $estado        = trim($_POST['estado'] ?? '');
    $rating        = intval($_POST['rating'] ?? 0);
    $rating_custom = trim($_POST['rating_custom'] ?? '');
    $imagen        = $_FILES['imagen']['name'] ?? '';

    // Validaciones básicas
    if ($nombre === '') $errores[] = "El nombre es obligatorio.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";

    $nombreArchivoFinal = $juego->imagen ?? null;

    if ($imagen) {
        $ext = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $permitidas)) {
            $errores[] = "Formato de imagen no permitido.";
        } else {
            $nombreArchivoFinal = 'game_' . time() . '.' . $ext;

            // Ruta absoluta a resources
            $carpeta = realpath(__DIR__ . '/../resources');
            if (!$carpeta) {
                $errores[] = "No se encontró la carpeta resources.";
            } else {
                $destino = $carpeta . DIRECTORY_SEPARATOR . $nombreArchivoFinal;

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    $errores[] = "Error al guardar la imagen en $destino. Revisa permisos.";
                }
            }
        }
    }

    // Guardar en la base de datos si no hubo errores
    if (!$errores) {
        if ($id > 0) {
            // --- UPDATE ---
            conexion::exec(
                "UPDATE juegos SET 
                    nombre = :nombre,
                    descripcion = :descripcion,
                    imagen = :imagen,
                    horas = :horas,
                    dificultad = :dificultad,
                    estado = :estado,
                    rating = :rating,
                    rating_custom = :rating_custom
                 WHERE id = :id",
                [
                    ':nombre'        => $nombre,
                    ':descripcion'   => $descripcion,
                    ':imagen'        => $nombreArchivoFinal,
                    ':horas'         => $horas,
                    ':dificultad'    => $dificultad,
                    ':estado'        => $estado,
                    ':rating'        => $rating,
                    ':rating_custom' => $rating_custom,
                    ':id'            => $id
                ]
            );
            $exito = "Juego actualizado correctamente 🎉";
        } else {
            // --- INSERT ---
            conexion::exec(
                "INSERT INTO juegos (nombre, descripcion, imagen, horas, dificultad, estado, rating, rating_custom, creado_en) 
                VALUES (:nombre, :descripcion, :imagen, :horas, :dificultad, :estado, :rating, :rating_custom, NOW())",
                [
                    ':nombre'        => $nombre,
                    ':descripcion'   => $descripcion,
                    ':imagen'        => $nombreArchivoFinal,
                    ':horas'         => $horas,
                    ':dificultad'    => $dificultad,
                    ':estado'        => $estado,
                    ':rating'        => $rating,
                    ':rating_custom' => $rating_custom
                ]
            );
            $exito = "Juego registrado correctamente 🎉";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../design/game_register.css">
</head>
<body>
<h2 style="color:#25FF08;text-align:center;margin-top:20px">
    <?= $editando ? "Editar Juego" : "Registrar Juego" ?>
</h2>

<?php if ($exito): ?>
    <div style="background:#d4edda;color:#155724;padding:10px;margin:15px auto;width:60%;border-radius:5px;">
        <?= htmlspecialchars($exito) ?>
    </div>
<?php endif; ?>

<?php if ($errores): ?>
    <div style="background:#f8d7da;color:#721c24;padding:10px;margin:15px auto;width:60%;border-radius:5px;">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="width:60%;margin:20px auto;background:#111;color:#fff;padding:20px;border-radius:10px">
    <input type="hidden" name="id" value="<?= $juego->id ?? 0 ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($juego->nombre ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" style="width:100%;padding:8px;margin:5px 0"><?= htmlspecialchars($juego->descripcion ?? '') ?></textarea><br><br>

    <?php if (!empty($juego->imagen)): ?>
        <p>Imagen actual:</p>
        <img src="../resources/<?= htmlspecialchars($juego->imagen) ?>" alt="" width="150"><br><br>
    <?php endif; ?>

    <label>Imagen (subir nueva si quieres reemplazarla):</label><br>
    <input type="file" name="imagen" style="margin:5px 0"><br><br>

    <label>Horas jugadas:</label><br>
    <input type="text" name="horas" value="<?= htmlspecialchars($juego->horas ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Dificultad:</label><br>
    <select name="dificultad" style="width:100%;padding:8px;margin:5px 0">
        <option value="">-- Seleccionar --</option>
        <?php
        $dificultades = ["Fácil", "Normal", "Difícil", "Extremo"];
        foreach ($dificultades as $dif) {
            $sel = ($juego && $juego->dificultad === $dif) ? "selected" : "";
            echo "<option value='$dif' $sel>$dif</option>";
        }
        ?>
    </select><br><br>

    <label>Estado:</label><br>
    <select name="estado" style="width:100%;padding:8px;margin:5px 0">
        <option value="">-- Seleccionar --</option>
        <?php
        $estados = ["En progreso", "Terminado", "Abandonado", "Pendiente"];
        foreach ($estados as $est) {
            $sel = ($juego && $juego->estado === $est) ? "selected" : "";
            echo "<option value='$est' $sel>$est</option>";
        }
        ?>
    </select><br><br>

    <label>Rating (1 a 5):</label><br>
    <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($juego->rating ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Rating personalizado (URL o nombre de imagen):</label><br>
    <input type="text" name="rating_custom" value="<?= htmlspecialchars($juego->rating_custom ?? '') ?>" style="width:100%;padding:8px;margin:5px 0" placeholder="ej: estrella.png o https://..."><br><br>

    <button type="submit" class="btn-deltarune">
        <?= $editando ? "Actualizar" : "Guardar" ?>
    </button>
</form>
</body>
</html>

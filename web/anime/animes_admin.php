<?php
require_once '../library/motor.php';
Plantilla::aplicar();

$errores = [];
$exito = null;

// --- Verificar si estamos editando ---
$editando = false;
$anime = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $editando = true;
    $id = intval($_GET['id']);
    $resultado = conexion::consulta("SELECT * FROM animes WHERE id = :id", [':id' => $id]);
    $anime = $resultado[0] ?? null;

    if (!$anime) {
        die("Anime no encontrado.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = intval($_POST['id'] ?? 0);
    $titulo        = trim($_POST['titulo'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');
    $capitulos     = trim($_POST['capitulos'] ?? '');
    $estado        = trim($_POST['estado'] ?? '');
    $rating        = intval($_POST['rating'] ?? 0);
    $rating_custom = trim($_POST['rating_custom'] ?? '');
    $imagen        = $_FILES['imagen']['name'] ?? '';

    // Validaciones básicas
    if ($titulo === '') $errores[] = "El título es obligatorio.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";

    $nombreArchivoFinal = $anime->imagen ?? null;

    if ($imagen) {
        $ext = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $permitidas)) {
            $errores[] = "Formato de imagen no permitido.";
        } else {
            $nombreArchivoFinal = 'anime_' . time() . '.' . $ext;

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
                "UPDATE animes SET 
                    titulo = :titulo,
                    descripcion = :descripcion,
                    imagen = :imagen,
                    capitulos = :capitulos,
                    estado = :estado,
                    rating = :rating,
                    rating_custom = :rating_custom
                 WHERE id = :id",
                [
                    ':titulo'        => $titulo,
                    ':descripcion'   => $descripcion,
                    ':imagen'        => $nombreArchivoFinal,
                    ':capitulos'     => $capitulos,
                    ':estado'        => $estado,
                    ':rating'        => $rating,
                    ':rating_custom' => $rating_custom,
                    ':id'            => $id
                ]
            );
            $exito = "Anime actualizado correctamente 🎉";
        } else {
            // --- INSERT ---
            conexion::exec(
                "INSERT INTO animes (titulo, descripcion, imagen, capitulos, estado, rating, rating_custom, creado_en) 
                VALUES (:titulo, :descripcion, :imagen, :capitulos, :estado, :rating, :rating_custom, NOW())",
                [
                    ':titulo'        => $titulo,
                    ':descripcion'   => $descripcion,
                    ':imagen'        => $nombreArchivoFinal,
                    ':capitulos'     => $capitulos,
                    ':estado'        => $estado,
                    ':rating'        => $rating,
                    ':rating_custom' => $rating_custom
                ]
            );
            $exito = "Anime registrado correctamente 🎉";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../design/anime_register.css">
</head>

<h2 class="titulo">
    <?= $editando ? "Editar Anime" : "Registrar Anime" ?>
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
    <input type="hidden" name="id" value="<?= $anime->id ?? 0 ?>">

    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?= htmlspecialchars($anime->titulo ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" style="width:100%;padding:8px;margin:5px 0"><?= htmlspecialchars($anime->descripcion ?? '') ?></textarea><br><br>

    <?php if (!empty($anime->imagen)): ?>
        <p>Imagen actual:</p>
        <img src="../resources/<?= htmlspecialchars($anime->imagen) ?>" alt="" width="150"><br><br>
    <?php endif; ?>

    <label>Imagen (subir nueva si quieres reemplazarla):</label><br>
    <input type="file" name="imagen" style="margin:5px 0"><br><br>

    <label>Capítulos:</label><br>
    <input type="text" name="capitulos" value="<?= htmlspecialchars($anime->capitulos ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Estado:</label><br>
    <select name="estado" style="width:100%;padding:8px;margin:5px 0">
        <option value="">-- Seleccionar --</option>
        <?php
        $estados = ["Viendo", "Terminado", "Pendiente", "Abandonado"];
        foreach ($estados as $est) {
            $sel = ($anime && $anime->estado === $est) ? "selected" : "";
            echo "<option value='$est' $sel>$est</option>";
        }
        ?>
    </select><br><br>

    <label>Rating (1 a 5):</label><br>
    <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($anime->rating ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

    <label>Rating personalizado (URL o nombre de imagen):</label><br>
    <input type="text" name="rating_custom" value="<?= htmlspecialchars($anime->rating_custom ?? '') ?>" style="width:100%;padding:8px;margin:5px 0" placeholder="ej: estrella.png o https://..."><br><br>

    <button type="submit" class="btn-deltarune">
        <?= $editando ? "Actualizar" : "Guardar" ?>
    </button>
</form>
</html>

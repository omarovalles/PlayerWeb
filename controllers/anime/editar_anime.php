<?php
require_once '../library/motor.php';
Plantilla::aplicar();

session_start();

// Verificar rol de admin
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php");
    exit;
}

$usuarioid = $_SESSION['usuario_id'];
$sql = "SELECT rol FROM usuarios WHERE id = :usuarioid";
$resultado = conexion::consulta($sql, [':usuarioid' => $usuarioid]);
$rol = $resultado[0]->rol ?? 'U';
if ($rol !== 'A') die("Acceso denegado.");

$errores = [];
$exito = null;

// --- Editando anime ---
$editando = false;
$anime = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $editando = true;
    $id = intval($_GET['id']);
    $resultado = conexion::consulta("SELECT * FROM animes WHERE id = :id", [':id' => $id]);
    $anime = $resultado[0] ?? null;
    if (!$anime) die("Anime no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $episodios = trim($_POST['episodios'] ?? '');
    $imagen = $_FILES['imagen']['name'] ?? '';

    if ($nombre === '') $errores[] = "El nombre es obligatorio.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";

    $nombreArchivoFinal = $anime->imagen ?? null;

    if ($imagen) {
        $ext = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $permitidas)) {
            $errores[] = "Formato de imagen no permitido.";
        } else {
            $nombreArchivoFinal = 'anime_' . time() . '.' . $ext;
            $carpeta = realpath(__DIR__ . '/../resources');
            if (!$carpeta) $errores[] = "No se encontró la carpeta resources.";
            else {
                $destino = $carpeta . DIRECTORY_SEPARATOR . $nombreArchivoFinal;
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    $errores[] = "Error al guardar la imagen.";
                }
            }
        }
    }

    if (!$errores) {
        if ($id > 0) {
            // UPDATE
            conexion::exec(
                "UPDATE animes SET nombre=:nombre, descripcion=:descripcion, imagen=:imagen, estado=:estado, episodios=:episodios WHERE id=:id",
                [
                    ':nombre'=>$nombre,
                    ':descripcion'=>$descripcion,
                    ':imagen'=>$nombreArchivoFinal,
                    ':estado'=>$estado,
                    ':episodios'=>$episodios,
                    ':id'=>$id
                ]
            );
            $exito = "Anime actualizado correctamente ✅";
        }
    }
}
?>

<h2 style="color:#25FF08;text-align:center;margin-top:20px"><?= $editando ? "Editar Anime" : "Registrar Anime" ?></h2>

<?php if ($exito): ?>
<div style="background:#d4edda;color:#155724;padding:10px;margin:15px auto;width:60%;border-radius:5px;">
    <?= htmlspecialchars($exito) ?>
</div>
<?php endif; ?>

<?php if ($errores): ?>
<div style="background:#f8d7da;color:#721c24;padding:10px;margin:15px auto;width:60%;border-radius:5px;">
    <ul>
    <?php foreach($errores as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="width:60%;margin:20px auto;background:#111;color:#fff;padding:20px;border-radius:10px">
<input type="hidden" name="id" value="<?= $anime->id ?? 0 ?>">

<label>Nombre:</label><br>
<input type="text" name="nombre" value="<?= htmlspecialchars($anime->nombre ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

<label>Descripción:</label><br>
<textarea name="descripcion" rows="4" style="width:100%;padding:8px;margin:5px 0"><?= htmlspecialchars($anime->descripcion ?? '') ?></textarea><br><br>

<?php if(!empty($anime->imagen)): ?>
<p>Imagen actual:</p>
<img src="../resources/<?= htmlspecialchars($anime->imagen) ?>" width="150"><br><br>
<?php endif; ?>

<label>Imagen (subir nueva si quieres reemplazarla):</label><br>
<input type="file" name="imagen" style="margin:5px 0"><br><br>

<label>Estado:</label><br>
<select name="estado" style="width:100%;padding:8px;margin:5px 0">
    <option value="">-- Seleccionar --</option>
    <?php
    $estados = ["En emisión", "Finalizado", "Suspendido"];
    foreach($estados as $est){
        $sel = ($anime && $anime->estado === $est) ? "selected" : "";
        echo "<option value='$est' $sel>$est</option>";
    }
    ?>
</select><br><br>

<label>Episodios:</label><br>
<input type="number" name="episodios" value="<?= htmlspecialchars($anime->episodios ?? '') ?>" style="width:100%;padding:8px;margin:5px 0"><br><br>

<button type="submit" class="btn-deltarune"><?= $editando ? "Actualizar" : "Guardar" ?></button>
</form>

<?php
require_once("../controllers/motor.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    $msg = "";

    if (isset($_POST["activar"])) {
        conexion::exec("UPDATE usuarios SET activo = 1 WHERE id = ?", [$id]);
        $msg = "Usuario #$id activado ✅";
    } elseif (isset($_POST["inactivar"])) {
        conexion::exec("UPDATE usuarios SET activo = 0 WHERE id = ?", [$id]);
        $msg = "Usuario #$id inactivado ⏸️";
    } elseif (isset($_POST["banear"])) {
        conexion::exec("UPDATE usuarios SET baneado = 1 WHERE id = ?", [$id]);
        $msg = "Usuario #$id baneado 🚫";
    } elseif (isset($_POST["desbanear"])) {
        conexion::exec("UPDATE usuarios SET baneado = 0 WHERE id = ?", [$id]);
        $msg = "Usuario #$id desbaneado ✅";
    } elseif (isset($_POST["reset"])) {
        $nueva = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $nuevaHash = password_hash($nueva, PASSWORD_BCRYPT);
        conexion::exec("UPDATE usuarios SET password = ? WHERE id = ?", [$nuevaHash, $id]);
        $msg = "Nueva contraseña para usuario #$id: $nueva";
    } elseif (isset($_POST["delete"])) {
        conexion::exec("DELETE FROM usuarios WHERE id = ?", [$id]);
        $msg = "Usuario #$id eliminado ❌";
    }

    header("Location: admin.php?msg=" . urlencode($msg));
    exit;
} else {
    header("Location: admin.php");
    exit;
}

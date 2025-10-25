<?php

include ('../../library/conexion.php');

// Validar si el usuario coincide con los datos de la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    // Validar campos
    if (empty($nombre) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Consultar usuario
    $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
    $usuarios = conexion::consulta($sql, ['nombre' => $nombre]);

    if (count($usuarios) === 0) {
        echo "Usuario no encontrado.";
        exit;
    }

    // Verificar contraseña
    $usuario = $usuarios[0];
    if (password_verify($password, $usuario->password)) {
        session_start();
        $_SESSION['usuario_id'] = $usuario->id;
        $_SESSION['usuario_nombre'] = $usuario->nombre;
        header("Location: ../../web/website.php");
        exit;
    } else {
        echo "Contraseña incorrecta.";
        exit;
    }
}

?>
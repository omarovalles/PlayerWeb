<?php

include ('../../library/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validar campos
    if (empty($nombre) || empty($correo) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE nombre = :nombre OR correo = :correo";
    $usuarios = conexion::consulta($sql, ['nombre' => $nombre, 'correo' => $correo]);

    if (count($usuarios) > 0) {
        echo "El usuario o correo ya está registrado."; 
        exit;
    }

    // Insertar nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
    $result = conexion::exec($sql, ['nombre' => $nombre, 'correo' => $correo, 'password' => $password]);

    if ($result > 0) {
        echo "Usuario registrado exitosamente.";
        header("Location: ../../web/users/login.php");
        exit;
    } else {
        echo "Error al registrar el usuario.";
        exit;
    }
}
?>
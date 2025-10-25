<?php
require_once("../library/motor.php");
Plantilla::aplicar();

// Leer mensajes pasados por GET (acciones)
$msg = isset($_GET["msg"]) ? urldecode($_GET["msg"]) : "";

// Capturar búsqueda
$busqueda = trim($_GET['q'] ?? '');

// Consulta con filtración
if ($busqueda !== '') {
    $usuarios = conexion::consulta(
        "SELECT * FROM usuarios 
         WHERE nombre LIKE :busqueda 
            OR correo LIKE :busqueda 
            OR id LIKE :busqueda
         ORDER BY id ASC",
        [':busqueda' => "%$busqueda%"]
    );
} else {
    $usuarios = conexion::consulta("SELECT * FROM usuarios ORDER BY id ASC");
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../design/users_manager.css">
    <title>Administración de Usuarios - SoreWeb</title>
</head>

<h1 class="panel-title">Panel de Administración</h1>

<?php if ($msg): ?>
    <div class="panel-msg"><?= $msg ?></div>
<?php endif; ?>

<!-- 🔎 Buscador -->
<form method="get" class="search-form" style="text-align:center; margin:20px 0;">
    <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" 
           placeholder="Buscar por nombre, correo o ID..." 
           style="padding:8px; width:50%; border-radius:8px;">
    <button type="submit" class="btn-search">Buscar</button>
</form>

<div class="panel-container">
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Baneado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($usuarios): ?>
            <?php foreach ($usuarios as $u): ?>
                <tr class="<?= $u->activo ? 'fila-activo' : 'fila-inactivo' ?>">
                    <td><?= $u->id ?></td>
                    <td><?= htmlspecialchars($u->nombre) ?></td>
                    <td><?= htmlspecialchars($u->correo) ?></td>
                    <td><?= $u->activo ? "Activo ✅" : "Inactivo ⏸️" ?></td>
                    <td><?= isset($u->baneado) && $u->baneado ? "Sí 🚫" : "No ✅" ?></td>
                    <td>
                        <form method="post" action="acciones.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $u->id ?>">

                            <!-- Activar/Inactivar -->
                            <?php if ($u->activo): ?>
                                <button class="btn-inactivar" name="inactivar">Inactivar</button>
                            <?php else: ?>
                                <button class="btn-activar" name="activar">Activar</button>
                            <?php endif; ?>

                            <!-- Banear/Desbanear -->
                            <?php if (isset($u->baneado) && $u->baneado): ?>
                                <button class="btn-desban" name="desbanear">Desbanear</button>
                            <?php else: ?>
                                <button class="btn-ban" name="banear">Banear</button>
                            <?php endif; ?>
                            
                            <!-- Resetear contraseña / Eliminar usuario -->
                            <button class="btn-reset" name="reset">Resetear</button>
                            <button class="btn-delete" name="delete" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>

                            <!-- Ver perfil -->
                            <a class="btn-view" href="perfil.php?id=<?= $u->id ?>">Ver Perfil</a>

                            <!-- Cambiar rol -->
                            <select name="nuevo_rol" class="role-select">
                                <option value="U" <?= $u->rol === 'U' ? 'selected' : '' ?>>Usuario</option>
                                <option value="M" <?= $u->rol === 'M' ? 'selected' : '' ?>>Moderador</option>
                                <option value="A" <?= $u->rol === 'A' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No se encontraron usuarios</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
?>

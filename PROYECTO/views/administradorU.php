<?php

include '../src/Administracion.php';
session_start();
// Crear instancia de la clase Administracion
$admin = new Administracion();

// Obtener todos los usuarios
$usuarios = $admin->obtenerUsuarios();

// Verificar si se desea eliminar un usuario
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id_usuario = $_POST['id'];
    $admin->eliminarUsuario($id_usuario);
    header("Location: administradorU.php"); // Redirigir a la misma página para actualizar la lista
    exit();
}

// Verificar si se desea modificar un usuario
if (isset($_POST['accion']) && $_POST['accion'] === 'editar' && isset($_POST['id'])) {
    $id_usuario = $_POST['id'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo_electronico = $_POST['correo_electronico'];
    $confirmado = isset($_POST['confirmado']) ? 1 : 0;
    $permiso = $_POST['permiso'];

    $admin->modificarUsuario($id_usuario, $nombre_usuario, $correo_electronico, $confirmado, $permiso);
    header("Location: administradorU.php"); // Redirigir a la misma página para actualizar la lista
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link href="../public/assets/css/administradorU.css" rel="stylesheet">    
</head>
<body>
<header>
        <h1>Panel de Administración - Usuario</h1>
    </header>

    <nav>
        <!-- Logo a la izquierda -->
        <div class="nav-logo">
            <a href="../index.php"><img src="../public/assets/css/img/logo.jpg" alt="Logo" style="height: 70px;"></a>
        </div>
    <div class="nav-links">
    <a href="../index.php">Inicio</a>

        <a href="habitaciones.php">Ver Habitaciones</a>
        <a href="gestionReservas.php">Gestionar Reservas</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['permiso'] === "admin"): // Ejemplo de rol de administrador ?>
            <a href="administradorH.php">Administrar Habitaciones</a>
            <a href="administradorU.php">Administrar Usuario</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesión</a>
            <a href="registrarCuenta.php">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>
    
    <!-- Mostrar la lista de usuarios -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Correo Electrónico</th>
                <th>Confirmado</th>
                <th>Permiso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['correo_electronico']); ?></td>
                    <td><?php echo $usuario['confirmado'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo htmlspecialchars($usuario['permiso']); ?></td>
                    <td>
                        <!-- Formulario para eliminar usuario -->
                        <form method="POST" style="display: inline-block;" enctype="multipart/form-data">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                        </form>

                        <!-- Formulario para editar usuario -->
                        <form method="POST" style="display: inline-block;" enctype="multipart/form-data">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

                            <!-- Campos para editar el usuario -->
                            <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
                            <input type="email" name="correo_electronico" value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>" required>
                            <label>
                                <input type="checkbox" name="confirmado" <?php echo $usuario['confirmado'] ? 'checked' : ''; ?>> Confirmado
                            </label>
                            <select name="permiso">
                                <option value="usuario" <?php echo $usuario['permiso'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                <option value="admin" <?php echo $usuario['permiso'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
include '../src/Administracion.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['permiso'] !== 'admin') {
    exit("<p>No tienes permisos para acceder a esta página. <a href='../index.php'>Volver al inicio</a></p>");
}
// Instanciar la clase AdministrarHabitaciones.
$administrador = new Administracion();

// Manejar operaciones CRUD.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion === 'agregar') {
            $nombre = htmlspecialchars($_POST['nombre']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $precio = floatval($_POST['precio']);
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            // Manejar la subida de imagen.
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $rutaDestino = '../public/assets/css/img' . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = $rutaDestino;
                }
            }

            $administrador->agregarHabitacion($nombre, $descripcion, $precio, $imagen, $disponible);
            echo "<p>Habitación creada exitosamente.</p>";
        } elseif ($accion === 'editar') {
            $id = intval($_POST['id']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $precio = floatval($_POST['precio']);
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            // Manejar la actualización de imagen.
            $imagen = htmlspecialchars($_POST['imagen_actual']);
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $rutaDestino = '../public/assets/css/img/' . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = $rutaDestino;
                }
            }

            $administrador->editarHabitacion($id, $nombre, $descripcion, $precio, $imagen, $disponible);
            echo "<p>Habitación actualizada exitosamente.</p>";
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id']);
            $administrador->eliminarHabitacion($id);
            echo "<p>Habitación eliminada exitosamente.</p>";
        }
    }
}

// Obtener todas las habitaciones.
$habitaciones = $administrador->obtenerHabitaciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Habitaciones</title>
    <link href="../public/assets/css/administradorH.css" rel="stylesheet">    </head>
<body>
    <header>
        <h1>Panel de Administración - Habitaciones</h1>
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
    <main class="contenedor">    
        <section class="form-section">
        <h2>Agregar Habitación</h2>
        <form method="POST" enctype="multipart/form-data" class="form-agregar">
            <input type="hidden" name="accion" value="agregar">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" required></textarea>
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" step="0.01" required>
            <label for="disponible">
                <input type="checkbox" name="disponible" id="disponible"> Disponible
            </label>
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" id="imagen" accept="img/*">
            <button class="btn" type="submit">Agregar</button>
        </form>
        </section>
    <div class="contenedor">
        <h2>Lista de Habitaciones</h2>
        <?php if (empty($habitaciones)): ?>
            <p>No hay habitaciones registradas.</p>
        <?php else: ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($habitaciones as $habitacion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($habitacion['id']); ?></td>
                            <td><?php echo htmlspecialchars($habitacion['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($habitacion['descripcion']); ?></td>
                            <td>$<?php echo htmlspecialchars($habitacion['precio']); ?></td>
                            <td>
                                <?php if ($habitacion['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($habitacion['imagen']); ?>" alt="Imagen" width="50">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                            <td><?php echo $habitacion['disponible'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <!-- Botón de eliminar -->
                                <form method="POST" style="display: inline-block;" enctype="multipart/form-data">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $habitacion['id']; ?>">
                                    <button class="btn" type="submit" onclick="return confirm('¿Estás seguro de eliminar esta habitación?')">Eliminar</button>
                                </form>
                                <!-- Formulario de editar -->
                                <form method="POST" style="display: inline-block;" enctype="multipart/form-data">
                                    <input type="hidden" name="accion" value="editar">
                                    <input type="hidden" name="id" value="<?php echo $habitacion['id']; ?>">
                                    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($habitacion['imagen']); ?>">
                                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($habitacion['nombre']); ?>" required>
                                    <textarea name="descripcion" required><?php echo htmlspecialchars($habitacion['descripcion']); ?></textarea>
                                    <input type="number" name="precio" step="0.01" value="<?php echo htmlspecialchars($habitacion['precio']); ?>" required>
                                    <label>
                                        <input type="checkbox" name="disponible" <?php echo $habitacion['disponible'] ? 'checked' : ''; ?>> Disponible
                                    </label>
                                    <input type="file" name="imagen" accept="image/*">
                                    <button class="btn" type="submit">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
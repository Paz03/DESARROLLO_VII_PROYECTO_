<?php  
session_start();
require_once '../src/config.php';
require_once "../src/reserva.php";

if (!isset($_SESSION['user_id'])) {
    echo "<p>Debes iniciar sesión para gestionar tus reservas.</p>";
    exit;
}

$usuario_id = $_SESSION['user_id'];
$reserva = new Reserva($db);

// Verificar si el usuario es administrador
$es_admin = $_SESSION['permiso'] == 'admin'; // Suponiendo que el permiso está en la sesión

// Manejar eliminación de reservas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva'])) {
    $reserva_id = $_POST['reserva_id'];
    echo "<p>Intentando eliminar la reserva con ID: " . $reserva_id . "</p>";  // Para depuración

    try {
        $resultado = $reserva->eliminarReserva($reserva_id, $usuario_id, $es_admin);
        
        if ($resultado) {
            echo "<p>Reserva eliminada con éxito.</p>";
        } else {
            echo "<p>Error: No se pudo eliminar la reserva.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

// Obtener las reservas del usuario o de todos los usuarios (si es admin)
$reservas = $reserva->obtenerReservas($usuario_id, $es_admin);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <link href="../public/assets/css/gestionReserva.css" rel="stylesheet">    
</head>
<body>
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
<h1>Gestión de Reservas</h1>

<div class="reservas-container">
    <?php if (empty($reservas)): ?>
        <p>No tienes reservas actualmente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Habitación</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Usuario</th> <!-- Nueva columna para mostrar el usuario -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reserva['habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['descripcion']); ?></td>
                        <td>$<?php echo htmlspecialchars($reserva['precio']); ?></td>
                        <td><?php echo $reserva['confirmada'] ? 'Confirmada' : 'Pendiente'; ?></td>
                        <td><?php echo htmlspecialchars($reserva['nombre_usuario'] ?? 'N/A'); ?></td> <!-- Muestra el nombre del usuario si es admin -->
                        <td>
                            <form action="gestionReservas.php" method="POST">
                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                <button type="submit" name="eliminar_reserva">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>

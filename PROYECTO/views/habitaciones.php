<?php

session_start();
$usuarioAutenticado = isset($_SESSION['user_id']);
require_once '../src/config.php';
require_once '../src/Administracion.php';


// Crear instancia de la clase AdministrarHabitaciones
$adminHabitaciones = new Administracion();

// Recoger los valores del formulario de filtro
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$precioMinimo = isset($_GET['precio_min']) ? $_GET['precio_min'] : '';
$precioMaximo = isset($_GET['precio_max']) ? $_GET['precio_max'] : '';
$soloDisponibles = isset($_GET['disponible']) && $_GET['disponible'] == '1' ? true : false;

// Filtrar habitaciones usando la clase
$habitaciones = $adminHabitaciones->filtrarHabitaciones($nombre, $precioMinimo, $precioMaximo, $soloDisponibles);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="../public/assets/css/habitaciones.css" rel="stylesheet">    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones Disponibles</title>
</head>
<body>
    <nav>
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

    <!-- Contenido principal -->
    <div class="container">
        <h2>Habitaciones Disponibles</h2>

        <!-- Formulario de filtro -->
        <form action="habitaciones.php" method="GET">
            <input type="text" name="nombre" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            <input type="number" name="precio_min" placeholder="Precio mínimo" value="<?php echo htmlspecialchars($precioMinimo); ?>" min="0">
            <input type="number" name="precio_max" placeholder="Precio máximo" value="<?php echo htmlspecialchars($precioMaximo); ?>" min="0">
            <label for="disponible">Solo disponibles:</label>
            <input type="checkbox" name="disponible" value="1" <?php if ($soloDisponibles) echo 'checked'; ?>>
            <button type="submit">Filtrar</button>
        </form>
    </div>
        <!-- Mostrar habitaciones en una cuadrícula -->
    <div class="habitaciones-container">
        <?php if (empty($habitaciones)): ?>
            <p>No hay habitaciones disponibles con los filtros aplicados.</p>
        <?php else: ?>
            <?php foreach ($habitaciones as $habitacion): ?>
                <div class="habitacion">
                    <h3><?php echo htmlspecialchars($habitacion['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($habitacion['descripcion']); ?></p>
                    <p>Precio: $<?php echo htmlspecialchars($habitacion['precio']); ?></p>

                    <!-- Mostrar la imagen de la habitación siempre, incluso si está reservada -->
                    <?php if (!empty($habitacion['imagen'])): ?>
                        <img src="<?php echo htmlspecialchars($habitacion['imagen']); ?>" alt="Imagen de la habitación" class="imagen-habitacion">
                    <?php endif; ?>

                    <!-- Verificamos si la habitación está disponible o no -->
                    <?php if ($habitacion['disponible'] == 1): ?>
                        <!-- Si está disponible, mostramos un enlace para reservar -->
                        <a class="btn" href="reservar.php?id=<?php echo $habitacion['id']; ?>">Reservar</a>
                    <?php else: ?>
                        <!-- Si no está disponible, mostramos un mensaje de reservado -->
                        <p class="reservada">Esta habitación está reservada.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <footer >
        <div class="containerf">
            <a href="contacto.php" style="color: white; text-decoration: underline;">Soporte y Contacto</a>        
         </div>
    </footer>
</body>
</html>
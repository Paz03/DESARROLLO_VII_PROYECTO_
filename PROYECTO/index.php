<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservas</title>
    <link href="public/assets/css/index.css" rel="stylesheet">
</head>
<body>  
    <!-- Navegación -->
    <nav>
        <!-- Logo a la izquierda -->
        <div class="nav-logo">
            <a href="index.php"><img src="public/assets/css/img/logo.jpg" alt="Logo" style="height: 70px;"></a>
        </div>
    <div class="nav-links">
        <a href="index.php">
        <a href="views/habitaciones.php">Ver Habitaciones</a>
        <a href="views/gestionReservas.php">Gestionar Reservas</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['permiso'] === "admin"): // Ejemplo de rol de administrador ?>
            <a href="views/administradorH.php">Administrar Habitaciones</a>
            <a href="views/administradorU.php">Administrar Usuario</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="views/logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="views/login.php">Iniciar Sesión</a>
            <a href="views/registrarCuenta.php">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>

    <!-- Contenido principal -->
    <div class="cta-section1">
        <h1>¡Descubre un nuevo mundo de diversión!</h1>
        <p>No hay nada más emocionante que pasar un día entero en nuestro increíble parque acuático.</p>
        <div class="cta-buttons">
            <a href="views/habitaciones.php">¡RESERVAR HOTEL!</a>
        </div>
    </div>

    <div class="content-sections">
        <div>
            <h3>Servicios del Hotel</h3>
            Habitaciones de lujo con vista al mar<br>
            Estacionamiento privado<br>
            Spa y zona de masajes<br>
            Restaurante gourmet<br>
            Servicio de habitaciones 24/7
        </div>
        <div>
            <h3>Actividades Hotelarias</h3>
            Piscinas climatizadas<br>
            Clases de yoga y pilates<br>
            Noches temáticas<br>
            Talleres de cocina y cata de vinos<br>
            Excursiones organizadas        
        </div>
        <div>
            <h3>Atracciones del Hotel</h3>
            Terraza panorámica con bar<br>
            Zona de juegos infantiles<br>
            Cine al aire libre<br>
            Jardines tropicales<br>
            Actividades acuáticas como kayak y paddleboarding
        </div>
    </div>

    <div class="cta-section">
        <h2>Deja atrás tus preocupaciones y disfruta de una estadía inolvidable.</h2>
        <p>Ofrecemos experiencias únicas con el confort y la tranquilidad que mereces. Relájate en nuestras exclusivas habitaciones y disfruta de servicios diseñados para tu bienestar.</p>
        <a href="views/habitaciones.php" class="cta-buttons">Reservar Ahora</a>
    </div>

    <!-- Sección 1: Imagen a la izquierda y contenido a la derecha -->
    <div class="image-text-section">
        <img src="public/assets/css/img/relajado.jpg" alt="Imagen izquierda">
        <div class="text-content">
            <h2>Relájate y vive una experiencia única en nuestro hotel.</h2>
            <p>Disfruta de un servicio excepcional, habitaciones acogedoras y una amplia gama de opciones de ocio diseñadas para tu comodidad. Relájate en nuestras instalaciones o aprovecha nuestras actividades recreativas.</p>
            <a href="views/habitaciones.php" class="cta-buttons">Reservar Hotel</a>
        </div>
    </div>

    <!-- Sección 2: Imagen a la derecha y contenido a la izquierda (corregido) -->
    <div class="image-text-section">
        <div class="text-content">
            <h2>¡Toda la garantia a nuestros huespedes!</h2>
            <p>Todos aquellos huespedes tendran la garantia de ser atendidos con excelncia y con toda la confianza que le podamos brindar como servidores.</p>
            <a href="views/habitaciones.php" class="cta-buttons">¡Reservar Ya!</a>
        </div>
        <img src="public/assets/css/img/cliente.png" alt="Imagen derecha">
    </div>

    <!-- Sección de Experiencias Destacadas -->
    <div class="experience-sections">
        <div>
            <h3>Reserva tu habitación</h3>
            <img src="public/assets/css/img/reserva.jpg" alt="Parque Acuático">
            <p>Disfruta del máximo confort y una experiencia inolvidable en nuestras habitaciones diseñadas para tu comodidad.</p>
        </div>
        <div>
            <h3>Habitaciones de la más alta calidad</h3>
            <img src="public/assets/css/img/calidad.jpg" alt="Área de Juegos">
            <p>Ambientes elegantes, decorados con estilo y equipados con todas las comodidades modernas.</p>
        </div>
    </div>

    <!-- Sección de Testimonios -->
    <div class="testimonial-section">
        <h2>Lo que nuestros clientes dicen</h2>
        <div class="testimonial-sections">
            <div>
                <img src="public/assets/css/img/Ana.jpg" alt="Cliente feliz">
                <p>““¡Una experiencia inolvidable! Desde el momento en que llegamos al hotel, nos recibieron con una cálida bienvenida y un servicio excepcional. Las habitaciones eran amplias, cómodas y con una vista increíble al mar. Disfrutamos muchísimo el desayuno buffet, con una variedad que complació a toda la familia. Definitivamente volveremos en nuestras próximas vacaciones.” – Ana R.</p>
            </div>
            <div>
                <img src="public/assets/css/img/carlos.webp" alt="Cliente feliz">
                <p>            “El lugar perfecto para una escapada de fin de semana. La ubicación del hotel es ideal, cerca de todas las atracciones principales, pero lo suficientemente tranquilo para descansar. Las instalaciones, como la piscina y el spa, estaban impecables, y el personal siempre estuvo atento a nuestras necesidades. Recomendado al 100% para una experiencia relajante y sin preocupaciones.” – Carlos G.</p>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer>
        <p>¡Ven a divertirte con nuestras atracciones! | <a href="views/contacto.php" style="color: white; text-decoration: underline;">Soporte y Contacto</a></p>
    </footer>
</body>
</html>

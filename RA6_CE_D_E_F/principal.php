<?php
/*
 * principal.php
 * Página principal de la aplicación, accesible solo para usuarios logueados.
 * Muestra un mensaje de bienvenida y enlaces a otras secciones.
 */

session_start();    // Iniciar la sesión para poder leer los datos guardados

// Comprobar si el usuario NO ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hay usuario en sesión, redirigir al login
    header('Location: index.php');
    exit();
}

// Guardar el nombre de usuario en una variable para mostrarlo en la página
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Principal</title>
</head>
<body>
    <h1>Página principal</h1>

    <!-- Mostrar el nombre del usuario autenticado -->
    <p>Bienvenido, <?php echo htmlspecialchars($usuario); ?></p>

    <!-- Enlaces a otras secciones de la aplicación -->
    <ul>
        <li><a href="aplicaciones.php">Gestión de aplicaciones</a></li>
        <!-- Aquí se podrían añadir más links a otras páginas -->
    </ul>

    <!-- Enlace para cerrar sesión (opcional, si creas logout.php) -->
    <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>

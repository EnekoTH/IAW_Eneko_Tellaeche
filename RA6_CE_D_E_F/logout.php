<?php
/*
 * logout.php
 * Página encargada de cerrar la sesión del usuario.
 * - Destruye todos los datos de la sesión.
 * - Redirige al usuario a la página de login (index.php).
 */

// Iniciar o reanudar la sesión para poder manipularla
session_start();

// Vaciar el array de sesión (eliminar todas las variables de sesión)
$_SESSION = [];

// Si se usa cookie de sesión, eliminarla también por seguridad
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // Crear una cookie caducada con el mismo nombre que la de sesión
    setcookie(
        session_name(),      // nombre de la cookie de sesión
        '',                  // valor vacío
        time() - 42000,      // tiempo en el pasado para que caduque
        $params["path"],     // misma ruta
        $params["domain"],   // mismo dominio
        $params["secure"],   // misma configuración de HTTPS
        $params["httponly"]  // misma configuración de HttpOnly
    );
}

// Destruir la sesión completamente en el servidor
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: index.php");
exit();

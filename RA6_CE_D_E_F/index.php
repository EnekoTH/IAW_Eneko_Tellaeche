<?php
/*
 * index.php
 * Página de inicio de sesión (login).
 * Solo permite el acceso a usuarios registrados correctamente.
 * Si el login funciona, redirige a principal.php.
 */

session_start();                // Iniciar o reanudar la sesión
require_once 'connect.php';     // Incluir funciones de BD y usuarios

$mensaje = "";                  // Mensaje informativo o de error para el usuario

// Comprobar si el formulario de login ha sido enviado
if (isset($_POST['entrar'])) {
    // Recoger datos del formulario y eliminar espacios sobrantes
    $usuario  = trim($_POST['usuario'] ?? "");
    $password = trim($_POST['password'] ?? "");

    // Verificar que los dos campos tengan contenido
    if ($usuario !== "" && $password !== "") {

        // Validar usuario y contraseña usando la función de connect.php
        if (usuarioCorrecto($usuario, $password)) {
            // Credenciales correctas: guardamos el usuario en la sesión
            $_SESSION['usuario'] = $usuario;

            // Redirigir a la página principal protegida
            header("Location: principal.php");
            exit();
        } else {
            // Si el usuario o la contraseña no son correctos
            $mensaje = "Usuario o contraseña incorrectos.";
        }
    } else {
        // Algún campo está vacío
        $mensaje = "Debes rellenar todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso</title>
</head>
<body>
    <h1>Acceso a la aplicación</h1>

    <!-- Mostrar mensaje en caso de error o información -->
    <?php if ($mensaje !== ""): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- Formulario de inicio de sesión -->
    <form action="index.php" method="post">
        <label>Usuario:
            <input type="text" name="usuario" maxlength="50" required>
        </label><br>
        <label>Contraseña:
            <input type="password" name="password" maxlength="50" required>
        </label><br>
        <input type="submit" name="entrar" value="Entrar">
    </form>

    <!-- Enlace para ir a la página de registro de nuevos usuarios -->
    <p>¿No tienes cuenta? <a href="registrarse.php">Crear usuario</a></p>
</body>
</html>

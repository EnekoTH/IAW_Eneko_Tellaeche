<?php
/*
 * registrarse.php
 * Página para registrar nuevos usuarios en la aplicación.
 * Comprueba:
 *   - Que todos los campos estén rellenos.
 *   - Que las contraseñas coincidan.
 *   - Que el usuario no exista ya en la base de datos.
 * Si todo es correcto, inserta el usuario y redirige al login.
 */

session_start();                // Iniciar o reanudar la sesión
require_once 'connect.php';     // Incluir las funciones de conexión y usuarios

$mensaje = "";                  // Mensaje para mostrar errores o información

// Comprobar si el formulario de registro ha sido enviado
if (isset($_POST['registrarse'])) {
    // Recoger y limpiar datos enviados por el formulario
    $usuario = trim($_POST['usuario']   ?? "");
    $pass1   = trim($_POST['password1'] ?? "");
    $pass2   = trim($_POST['password2'] ?? "");

    // Verificar que ningún campo esté vacío
    if ($usuario === "" || $pass1 === "" || $pass2 === "") {
        $mensaje = "Debes rellenar todos los campos.";
    }
    // Comprobar que las dos contraseñas coinciden
    elseif ($pass1 !== $pass2) {
        $mensaje = "Las contraseñas no coinciden.";
    }
    else {
        // Intentar registrar el nuevo usuario en la BD
        if (registrarUsuario($usuario, $pass1)) {
            // Guardamos un mensaje en la sesión para mostrarlo en el login
            $_SESSION['mensaje_login'] = "Insertado con éxito. Ya puedes iniciar sesión.";
            // Redirigir a la página de login
            header("Location: index.php");
            exit();
        } else {
            // Si la función devuelve false, es que el usuario ya existe
            $mensaje = "El usuario ya existe.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h1>Registro de usuario</h1>

    <!-- Mostrar el mensaje de error o de aviso, si lo hay -->
    <?php if ($mensaje !== ""): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- Formulario de registro de nuevo usuario -->
    <form action="registrarse.php" method="post">
        <label>Usuario:
            <input type="text" name="usuario" maxlength="50" required>
        </label><br>
        <label>Contraseña:
            <input type="password" name="password1" maxlength="50" required>
        </label><br>
        <label>Repite contraseña:
            <input type="password" name="password2" maxlength="50" required>
        </label><br>
        <input type="submit" name="registrarse" value="Registrarse">
    </form>

    <!-- Enlace para volver a la pantalla de login -->
    <p><a href="index.php">Volver al login</a></p>
</body>
</html>

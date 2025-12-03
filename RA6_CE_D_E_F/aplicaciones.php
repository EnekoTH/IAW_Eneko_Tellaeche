<?php
/*
 * aplicaciones.php
 * Página para gestionar las aplicaciones:
 *   - Solo accesible para usuarios logueados.
 *   - Muestra el listado de aplicaciones.
 *   - Permite insertar nuevas aplicaciones.
 *   - Permite borrar aplicaciones existentes.
 */

session_start();    // Iniciar o reanudar la sesión

// Si no hay usuario logueado, redirigir a la página de login
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

// Incluir el archivo con las funciones de BD
require_once 'connect.php';

$mensaje = "";      // Mensaje de información o error para el usuario

/*********** BORRAR APLICACIÓN ***********/

// Comprobar si llega una petición de borrado por GET (?borrar=ID)
if (isset($_GET['borrar'])) {
    // Convertir el valor recibido a entero para mayor seguridad
    $id = intval($_GET['borrar']);

    // Llamar a la función que borra la aplicación por id_app
    if (borrarAplicacion($id)) {
        $mensaje = "Aplicación borrada correctamente.";
    } else {
        $mensaje = "No se pudo borrar la aplicación.";
    }
}

/*********** INSERTAR APLICACIÓN ***********/

// Comprobar si se ha enviado el formulario de inserción
if (isset($_POST['insertar'])) {
    // Recoger y limpiar datos del formulario
    $nombre      = trim($_POST['nombre']      ?? "");
    $descripcion = trim($_POST['descripcion'] ?? "");

    // Verificar que los campos no estén vacíos
    if ($nombre !== "" && $descripcion !== "") {
        // Llamar a la función que inserta la aplicación
        if (insertarAplicacion($nombre, $descripcion)) {
            $mensaje = "Aplicación insertada correctamente.";
        } else {
            $mensaje = "No se pudo insertar la aplicación.";
        }
    } else {
        // Algún campo está vacío
        $mensaje = "Debes rellenar todos los campos.";
    }
}

/*********** OBTENER LISTADO DE APLICACIONES ***********/

// Obtener todas las aplicaciones para mostrarlas en una tabla HTML
$aplicaciones = getAplicaciones();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aplicaciones</title>
</head>
<body>
    <h1>Gestión de aplicaciones</h1>

    <!-- Mostrar el mensaje de éxito o error si existe -->
    <?php if ($mensaje !== ""): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- LISTADO DE APLICACIONES -->
    <h2>Listado de aplicaciones</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre aplicación</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>

        <!-- Recorrer el array de aplicaciones y mostrarlas en filas -->
        <?php foreach ($aplicaciones as $app): ?>
            <tr>
                <td><?php echo $app['id_app']; ?></td>
                <td><?php echo htmlspecialchars($app['nombre_aplicacion']); ?></td>
                <td><?php echo htmlspecialchars($app['descripcion']); ?></td>
                <td>
                    <!-- Enlace que lanza el borrado de la aplicación seleccionada -->
                    <a href="aplicaciones.php?borrar=<?php echo $app['id_app']; ?>"
                       onclick="return confirm('¿Borrar esta aplicación?');">
                        Borrar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- FORMULARIO PARA INSERTAR NUEVA APLICACIÓN -->
    <h2>Nueva aplicación</h2>
    <form action="aplicaciones.php" method="post">
        <label>Nombre:
            <input type="text" name="nombre" maxlength="50" required>
        </label><br>
        <label>Descripción:
            <input type="text" name="descripcion" maxlength="300" required>
        </label><br>
        <input type="submit" name="insertar" value="Insertar">
    </form>

    <!-- Enlace para regresar a la página principal -->
    <p><a href="principal.php">Volver a principal</a></p>
</body>
</html>

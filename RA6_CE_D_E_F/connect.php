<?php
/*
 * connect.php
 * Archivo con todas las funciones relacionadas con la base de datos:
 *  - Conexión MySQLi
 *  - Gestión de usuarios (login y registro)
 *  - Gestión de aplicaciones (listar, insertar, borrar)
 */

// Función que crea y devuelve una conexión MySQLi
function getConexionMySQLi() {
    // Datos de conexión a la base de datos
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "control_accesos";

    // Crear objeto de conexión
    $conn = new mysqli($host, $user, $pass, $db);

    // Comprobar si ha ocurrido algún error al conectar
    if ($conn->connect_errno) {
        die("Error de conexión MySQLi: " . $conn->connect_error);
    }

    // Establecer el juego de caracteres a UTF-8
    $conn->set_charset("utf8mb4");

    // Devolver la conexión para que otras funciones la usen
    return $conn;
}

/*********** USUARIOS ***********/

/*
 * usuarioCorrecto
 * Comprueba si un usuario y contraseña son válidos.
 * Parámetros:
 *   - $usuario: nombre de usuario (string)
 *   - $password: contraseña en texto plano (string)
 * Devuelve:
 *   - true si el usuario existe y la contraseña coincide
 *   - false en caso contrario
 */
function usuarioCorrecto($usuario, $password) {
    // Obtener una conexión
    $conn = getConexionMySQLi();

    // Preparar consulta para obtener el hash de la contraseña del usuario
    $stmt = $conn->prepare(
        "SELECT passwd FROM logins WHERE usuario = ?"
    );
    // Asociar el parámetro (s = string)
    $stmt->bind_param("s", $usuario);
    // Ejecutar la consulta
    $stmt->execute();
    // Vincular la columna 'passwd' al argumento $hash
    $stmt->bind_result($hash);

    $ok = false;

    // Si se obtiene una fila, significa que el usuario existe
    if ($stmt->fetch()) {
        // Comparar el md5 de la contraseña introducida con el hash almacenado
        $ok = (md5($password) == $hash);
    }

    // Cerrar sentencia y conexión
    $stmt->close();
    $conn->close();

    return $ok;
}

/*
 * registrarUsuario
 * Inserta un nuevo usuario en la tabla logins si no existe.
 * Parámetros:
 *   - $usuario: nombre de usuario (string)
 *   - $password: contraseña en texto plano (string)
 * Devuelve:
 *   - true si se inserta correctamente
 *   - false si el usuario ya existe o falla la inserción
 */
function registrarUsuario($usuario, $password) {
    // Conectar a la base de datos
    $conn = getConexionMySQLi();

    // Comprobar si el usuario ya existe
    $stmt = $conn->prepare(
        "SELECT usuario FROM logins WHERE usuario = ?"
    );
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    // Guardar el resultado para poder usar num_rows
    $stmt->store_result();

    // Si hay al menos una fila, el usuario ya existe
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return false;
    }

    // Cerrar la sentencia previa
    $stmt->close();

    // Calcular el hash md5 de la contraseña
    $hash = md5($password);

    // Preparar la sentencia de inserción
    $stmt = $conn->prepare(
        "INSERT INTO logins (usuario, passwd) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $usuario, $hash);

    // Ejecutar y guardar si fue bien o mal
    $ok = $stmt->execute();

    // Cerrar recursos
    $stmt->close();
    $conn->close();

    return $ok;
}

/*********** APLICACIONES ***********/

/*
 * getAplicaciones
 * Obtiene todas las aplicaciones de la tabla 'aplicaciones'.
 * Devuelve:
 *   - Array de arrays asociativos, cada uno con id_app, nombre_aplicacion, descripcion
 */
function getAplicaciones() {
    // Conectar a la base de datos
    $conn = getConexionMySQLi();

    // Ejecutar consulta para obtener todas las aplicaciones
    $resultado = $conn->query(
        "SELECT id_app, nombre_aplicacion, descripcion
         FROM aplicaciones
         ORDER BY id_app"
    );

    $apps = [];

    // Recorrer todas las filas y guardarlas en un array
    while ($fila = $resultado->fetch_assoc()) {
        $apps[] = $fila;
    }

    // Liberar el resultado y cerrar conexión
    $resultado->free();
    $conn->close();

    return $apps;
}

/*
 * insertarAplicacion
 * Inserta una nueva aplicación en la tabla 'aplicaciones'.
 * Parámetros:
 *   - $nombre: nombre de la aplicación
 *   - $descripcion: descripción de la aplicación
 * Devuelve:
 *   - true si se inserta correctamente
 *   - false si ocurre algún error
 */
function insertarAplicacion($nombre, $descripcion) {
    // Conectar a la base de datos
    $conn = getConexionMySQLi();

    // Preparar sentencia de inserción
    $stmt = $conn->prepare(
        "INSERT INTO aplicaciones (nombre_aplicacion, descripcion)
         VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $nombre, $descripcion);

    // Ejecutar y comprobar si todo fue bien
    $ok = $stmt->execute();

    // Cerrar recursos
    $stmt->close();
    $conn->close();

    return $ok;
}

/*
 * borrarAplicacion
 * Borra una aplicación concreta según su id_app.
 * Parámetros:
 *   - $id_app: identificador numérico de la aplicación
 * Devuelve:
 *   - true si se borra alguna fila
 *   - false si no se borra nada o hay error
 */
function borrarAplicacion($id_app) {
    // Conectar a la base de datos
    $conn = getConexionMySQLi();

    // Preparar sentencia de borrado
    $stmt = $conn->prepare(
        "DELETE FROM aplicaciones WHERE id_app = ?"
    );
    $stmt->bind_param("i", $id_app);

    // Ejecutar y guardar estado
    $ok = $stmt->execute();

    // Cerrar recursos
    $stmt->close();
    $conn->close();

    return $ok;
}
?>

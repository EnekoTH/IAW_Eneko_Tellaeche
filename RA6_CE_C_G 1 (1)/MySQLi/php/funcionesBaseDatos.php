<?php

include_once 'constantes.php';

function getConexionPDO()
{
	
}

function getConexionPDO_sin_bbdd()
{
    try {
        $dsn = 'mysql:host=' . HOST . ';charset=utf8mb4';
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, USERNAME, PASSWORD, $opciones);
    } catch (PDOException $e) {
        die('Error de conexión PDO (sin bbdd): ' . $e->getMessage());
    }  
}

function getConexionMySQLi()
{
    $conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    if ($conn->connect_error) {
        die('Error de conexión MySQLi: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function getConexionMySQLi_sin_bbdd()
{
    $conn = new mysqli(HOST, USERNAME, PASSWORD);
    if ($conn->connect_error) {
        die('Error de conexión MySQLi (sin bbdd): ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;    
}


function crearBBDD_MySQLi($basedatos){

    $conn = getConexionMySQLi_sin_bbdd();

    // ¿Existe ya la base de datos?
    $sqlExiste = "SHOW DATABASES LIKE ?";
    $stmt = $conn->prepare($sqlExiste);
    $stmt->bind_param("s", $basedatos);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Ya existe
        $stmt->close();
        $conn->close();
        return 1;
    }

    $stmt->close();

    // Crear la base de datos
    $sqlCrear = "CREATE DATABASE `$basedatos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $ok = $conn->query($sqlCrear);

    $conn->close();
    return $ok ? 0 : -1;   
    
}

function crearTablas_MySQLi($basedatos){
 
$conn = getConexionMySQLi_sin_bbdd();

    // Seleccionamos la base de datos
    if (!$conn->select_db($basedatos)) {
        $conn->close();
        return -1;
    }

    // Tabla logins
    $sqlLogins = "CREATE TABLE IF NOT EXISTS logins (
                    usuario VARCHAR(50) PRIMARY KEY,
                    passwd  CHAR(32)   NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Tabla libros
    $sqlLibros = "CREATE TABLE IF NOT EXISTS libros (
                    numero_ejemplar   INT AUTO_INCREMENT PRIMARY KEY,
                    titulo            VARCHAR(255) NOT NULL,
                    anyo_edicion      INT NOT NULL,
                    precio            DECIMAL(10,2) NOT NULL,
                    fecha_adquisicion DATE NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $ok1 = $conn->query($sqlLogins);
    $ok2 = $conn->query($sqlLibros);

    $conn->close();

    return ($ok1 && $ok2) ? 1 : -1;    

}



function crearBBDD($basedatos) {

     $pdo = getConexionPDO_sin_bbdd();

    // ¿Existe ya la base de datos?
    $sqlExiste = "SHOW DATABASES LIKE :bd";
    $stmt = $pdo->prepare($sqlExiste);
    $stmt->execute([':bd' => $basedatos]);

    if ($stmt->rowCount() > 0) {
        // Ya existe
        return 1;
    }

    // Crear BBDD
    $sqlCrear = "CREATE DATABASE `$basedatos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $ok = $pdo->exec($sqlCrear);

    return $ok === false ? -1 : 0;
}


function crearTablas($basedatos) {

$pdo = getConexionPDO_sin_bbdd();
    $pdo->exec("USE `$basedatos`");

    // Tabla logins
    $sqlLogins = "CREATE TABLE IF NOT EXISTS logins (
                    usuario VARCHAR(50) PRIMARY KEY,
                    passwd  CHAR(32)   NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Tabla libros
    $sqlLibros = "CREATE TABLE IF NOT EXISTS libros (
                    numero_ejemplar   INT AUTO_INCREMENT PRIMARY KEY,
                    titulo            VARCHAR(255) NOT NULL,
                    anyo_edicion      INT NOT NULL,
                    precio            DECIMAL(10,2) NOT NULL,
                    fecha_adquisicion DATE NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $ok1 = $pdo->exec($sqlLogins);
    $ok2 = $pdo->exec($sqlLibros);

    return ($ok1 === false || $ok2 === false) ? -1 : 1;

}


function usuarioCorrecto_MySQLi($usuario, $password)
{
    $conn = getConexionMySQLi();

    $sql = "SELECT * FROM logins WHERE usuario = ? AND passwd = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error en prepare: ' . $conn->error);
    }

    $pass_hash = md5($password);

    $stmt->bind_param("ss", $usuario, $pass_hash);
    $stmt->execute();
    $res = $stmt->get_result();

    $valido = ($res->num_rows === 1);

    $stmt->close();
    $conn->close();

    return $valido;   
}

function usuarioCorrecto($usuario, $password)
{
    $pdo = getConexionPDO();

    $sql = "SELECT * FROM logins WHERE usuario = :usuario AND passwd = :passwd";
    $stmt = $pdo->prepare($sql);
    $pass_hash = md5($password);
    $stmt->execute([
        ':usuario' => $usuario,
        ':passwd'  => $pass_hash
    ]);

    return ($stmt->rowCount() === 1);  
}



function registrarUsuario_MySQLi($usuario, $password)
{
    $conn = getConexionMySQLi();

    // ¿Ya existe?
    $sqlCheck = "SELECT usuario FROM logins WHERE usuario = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    if (!$stmtCheck) {
        throw new Exception('Error en prepare (check): ' . $conn->error);
    }
    $stmtCheck->bind_param("s", $usuario);
    $stmtCheck->execute();
    $res = $stmtCheck->get_result();

    if ($res->num_rows > 0) {
        $stmtCheck->close();
        $conn->close();
        throw new Exception("El usuario ya existe");
    }
    $stmtCheck->close();

    // Insertar usuario
    $sqlInsert = "INSERT INTO logins (usuario, passwd) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    if (!$stmtInsert) {
        $conn->close();
        throw new Exception('Error en prepare (insert): ' . $conn->error);
    }

    $pass_hash = md5($password);
    $stmtInsert->bind_param("ss", $usuario, $pass_hash);

    $ok = $stmtInsert->execute();
    if (!$ok) {
        $mensajeError = $stmtInsert->error;
        $stmtInsert->close();
        $conn->close();
        throw new Exception('Error al insertar: ' . $mensajeError);
    }

    $stmtInsert->close();
    $conn->close();
    return true;  
        
}

function registrarUsuario($usuario, $password)
{
    $pdo = getConexionPDO();

    // ¿Ya existe?
    $sqlCheck = "SELECT usuario FROM logins WHERE usuario = :usuario";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([':usuario' => $usuario]);

    if ($stmtCheck->rowCount() > 0) {
        throw new Exception("El usuario ya existe");
    }

    // Insertar
    $sqlInsert = "INSERT INTO logins (usuario, passwd) VALUES (:usuario, :passwd)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $pass_hash  = md5($password);

    if (!$stmtInsert->execute([':usuario' => $usuario, ':passwd' => $pass_hash])) {
        throw new Exception("Error al insertar usuario");
    }

    return true;  
}

function insertarLibro_MySQLi($titulo, $anyo, $precio, $fechaAdquisicion)
{
    $conn = getConexionMySQLi();

    $sql = "INSERT INTO libros (titulo, anyo_edicion, precio, fecha_adquisicion)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }

    $stmt->bind_param("sids", $titulo, $anyo, $precio, $fechaAdquisicion);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $ok; 
}


function insertarLibro($titulo, $anyo, $precio, $fechaAdquisicion)
{
    $pdo = getConexionPDO();

    $sql = "INSERT INTO libros (titulo, anyo_edicion, precio, fecha_adquisicion)
            VALUES (:titulo, :anyo, :precio, :fecha)";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':titulo' => $titulo,
        ':anyo'   => $anyo,
        ':precio' => $precio,
        ':fecha'  => $fechaAdquisicion
    ]);   
}




function getLibros_MySQLi()
{
    $conn = getConexionMySQLi();

    $sql = "SELECT numero_ejemplar, titulo, anyo_edicion, precio, fecha_adquisicion
            FROM libros";
    $res = $conn->query($sql);

    $libros = [];
    if ($res) {
        while ($fila = $res->fetch_object()) {
            $libros[] = $fila;
        }
        $res->free();
    }

    $conn->close();
    return $libros;	
}

function getLibros()
{
    $pdo = getConexionPDO();

    $sql = "SELECT numero_ejemplar, titulo, anyo_edicion, precio, fecha_adquisicion
            FROM libros";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_OBJ);    
}

function getLibrosTitulo_MySQLi()
{
    $conn = getConexionMySQLi();

    $sql = "SELECT numero_ejemplar, titulo FROM libros";
    $res = $conn->query($sql);

    $libros = [];
    if ($res) {
        while ($fila = $res->fetch_object()) {
            $libros[] = $fila;
        }
        $res->free();
    }

    $conn->close();
    return $libros;
    
}


function getLibrosTitulo()
{
    $pdo = getConexionPDO();

    $sql = "SELECT numero_ejemplar, titulo FROM libros";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_OBJ);   
}



function borrarLibro($numeroEjemplar)
{
    $pdo = getConexionPDO();

    $sql = "DELETE FROM libros WHERE numero_ejemplar = :num";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([':num' => $numeroEjemplar]);  
}

function borrarLibro_MySQLi($numeroEjemplar)
{
  
    $conn = getConexionMySQLi();

    $sql = "DELETE FROM libros WHERE numero_ejemplar = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }

    $stmt->bind_param("i", $numeroEjemplar);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $ok;    
   
}


function modificarLibro_MySQLi($numero_ejemplar,$precio)
{
    $conn = getConexionMySQLi();

    $sql = "UPDATE libros SET precio = ? WHERE numero_ejemplar = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }

    $stmt->bind_param("di", $precio, $numero_ejemplar);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $ok;  
}


function modificarLibroAnyo_MySQLi($numero_ejemplar,$anyo_edicion)
{
    $conn = getConexionMySQLi();

    $sql = "UPDATE libros SET anyo_edicion = ? WHERE numero_ejemplar = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }

    $stmt->bind_param("ii", $anyo_edicion, $numero_ejemplar);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $ok;   
}

function arrayFlotante($array) {
    $resultado = [];
    foreach ($array as $valor) {
        $resultado[] = floatval($valor);
    }
    return $resultado;
}


function modificarLibro($numero_ejemplar, $precio)
{
    $pdo = getConexionPDO();

    $sql = "UPDATE libros SET precio = :precio WHERE numero_ejemplar = :num";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':precio' => $precio,
        ':num'    => $numero_ejemplar
    ]);
}



function modificarLibroAnyo($numero_ejemplar, $anyo_edicion)
{
    $pdo = getConexionPDO();

    $sql = "UPDATE libros SET anyo_edicion = :anyo WHERE numero_ejemplar = :num";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':anyo' => $anyo_edicion,
        ':num'  => $numero_ejemplar
    ]);
 
}






function getLibrosPrecio_MySQLi($libro)
{
    $conn = getConexionMySQLi();

    $sql = "SELECT precio FROM libros WHERE titulo = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return null;
    }

    $stmt->bind_param("s", $titulo);
    $stmt->execute();
    $res = $stmt->get_result();

    $precio = null;
    if ($fila = $res->fetch_assoc()) {
        $precio = (float)$fila['precio'];
    }

    $stmt->close();
    $conn->close();

    return $precio;
}

function getLibrosAnyo_MySQLi($libro)
{
    $conn = getConexionMySQLi();

    $sql = "SELECT anyo_edicion FROM libros WHERE titulo = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return null;
    }

    $stmt->bind_param("s", $titulo);
    $stmt->execute();
    $res = $stmt->get_result();

    $anyo = null;
    if ($fila = $res->fetch_assoc()) {
        $anyo = (int)$fila['anyo_edicion'];
    }

    $stmt->close();
    $conn->close();

    return $anyo;
}


function getLibrosPrecio($libro)
{
    $pdo = getConexionPDO();

    $sql = "SELECT precio FROM libros WHERE titulo = :titulo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo]);

    $fila = $stmt->fetch();
    return $fila ? (float)$fila['precio'] : null;    
}

function getLibrosAnyo($libro)
{
    $pdo = getConexionPDO();

    $sql = "SELECT anyo_edicion FROM libros WHERE titulo = :titulo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo]);

    $fila = $stmt->fetch();
    return $fila ? (int)$fila['anyo_edicion'] : null;    
}


?>

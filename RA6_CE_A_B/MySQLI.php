<?php
// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "";
$database = "ejemplo_db";

// Creación de instancia de conexión
$conn = new mysqli($servername, $username, $password, $database);

// Validación de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configuración de codificación de caracteres
$conn->set_charset("utf8mb4");

// Ejemplo de consulta preparada
$stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE estado = ?");
$stmt->bind_param("s", $estado);
$estado = "activo";
$stmt->execute();
$resultado = $stmt->get_result();

// Procesamiento de resultados
while ($fila = $resultado->fetch_assoc()) {
    echo "ID: " . $fila['id'] . " - Nombre: " . $fila['nombre'] . "<br>";
}

// Liberación de recursos y cierre
$stmt->close();
$conn->close();
?>

<?php
// Configuración de conexión
$dsn = 'mysql:host=localhost;dbname=ejemplo_db;charset=utf8mb4';
$username = 'root';
$password = '';

// Manejo de excepciones
try {
    // Creación de instancia PDO
    $conn = new PDO($dsn, $username, $password);
    
    // Configuración de modo de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configuración de fetching
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Ejemplo de transacción
    $conn->beginTransaction();
    
    // Consulta preparada
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)");
    $stmt->execute([
        ':nombre' => 'Juan Pérez',
        ':email' => 'juan@example.com'
    ]);
    
    $conn->commit();
    echo "Registro insertado exitosamente. ID: " . $conn->lastInsertId();
    
} catch (PDOException $e) {
    // Rollback en caso de error
    if ($conn->inTransaction()) {
        $conn->rollback();
    }
    echo "Error en la operación: " . $e->getMessage();
}

// Cierre de conexión
$conn = null;
?>

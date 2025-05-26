<?php
header('Content-Type: application/json');

// Conexión a la base de datos
require '../db/conexion.php';

$conexion = obtenerConexion();

// Verificamos si la solicitud es GET (obtener clientes)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Consultamos todos los clientes
    $stmt = $conexion->prepare("SELECT id, nombre, email, telefono FROM clientes");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos los resultados como JSON
    echo json_encode($clientes);

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si es una solicitud POST, creamos un nuevo cliente
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nombre = $data['nombre'];
    $email = $data['email'];
    $telefono = $data['telefono'];
    
    // Verificamos si los campos están completos
    if (empty($nombre) || empty($email) || empty($telefono)) {
        echo json_encode(['message' => 'Faltan campos requeridos']);
        exit;
    }
    
    // Insertamos el cliente
    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, email, telefono) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $email, $telefono]);

    echo json_encode(['message' => 'Cliente creado correctamente']);

} else {
    // Si no es GET ni POST, devolvemos un mensaje de error
    echo json_encode(['message' => 'Método no permitido']);
}
?>

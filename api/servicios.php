<?php
header('Content-Type: application/json');

// Conexión a la base de datos
require '../db/conexion.php';

$conexion = obtenerConexion();

// Verificamos si la solicitud es GET (obtener servicios)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Consultamos todos los servicios
    $stmt = $conexion->prepare("SELECT s.id, c.nombre AS cliente, s.descripcion, s.estado FROM servicios s
    JOIN clientes c ON s.cliente_id = c.id ORDER BY s.id DESC");
    $stmt->execute();
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos los resultados como JSON
    echo json_encode($servicios);

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si es una solicitud POST, creamos un nuevo servicio
    $data = json_decode(file_get_contents('php://input'), true);

    $cliente_id = $data['cliente_id'];
    $descripcion = $data['descripcion'];
    $estado = "pendiente"; // Estado por defecto del servicio

    // Verificamos si los campos están completos
    if (empty($cliente_id) || empty($descripcion)) {
        echo json_encode(['message' => 'Faltan campos requeridos']);
        exit;
    }

    // Insertamos el servicio
    $stmt = $conexion->prepare("INSERT INTO servicios (cliente_id, descripcion, estado) VALUES (?, ?, ?)");
    $stmt->execute([$cliente_id, $descripcion, $estado]);

    echo json_encode(['message' => 'Servicio creado correctamente']);

} else {
    // Si no es GET ni POST, devolvemos un mensaje de error
    echo json_encode(['message' => 'Método no permitido']);
}
?>

<?php
session_start();
require_once '../includes/validar_sesion.php';
require_once '../db/conexion.php';

$id_cliente = $_GET['id'];
$conexion = obtenerConexion(); // Conexión con PDO

try {
    $stmt = $conexion->prepare("DELETE FROM clientes WHERE id = :id_cliente");
    $stmt->bindParam(':id_cliente', $id_cliente);
    $stmt->execute();

    header("Location: listar_clientes.php?mensaje=Cliente eliminado correctamente");
    exit();
} catch (PDOException $e) {
    echo "Error al eliminar el cliente: " . $e->getMessage();
}
?>

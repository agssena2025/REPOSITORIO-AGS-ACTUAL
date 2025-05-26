<?php
session_start();

require_once '../db/conexion.php';
require_once '../includes/validar_sesion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID de servicio no proporcionado.";
    exit;
}

$conexion = obtenerConexion(); //para conectar con la base de datos

$stmt = $conexion->prepare("DELETE FROM servicios WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo "Servicio eliminado correctamente.";

} else {
    echo "No se encontró el servicio o no se pudo eliminar.";
}

$conexion = null;

header("Location: listar_servicios.php?mensaje=Servicio eliminado correctamente");
exit; 



?>
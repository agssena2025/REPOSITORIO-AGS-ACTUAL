<?php
session_start();

require_once '../includes/validar_sesion.php';
require_once '../db/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID de Cotizaci´n no proporcionado.";
    exit;
}

$conexion = obtenerConexion(); //para conectar con la base de datos

$stmt = $conexion->prepare("DELETE FROM cotizaciones WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo "Cotización eliminada correctamente.";

} else {
    echo "No se encontró La cotización o no se pudo eliminar.";
}

$conexion = null;

header("Location: listar_cotizaciones.php?mensaje=Cotización eliminada correctamente");
exit;

?>
<?php
session_start();
require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conexion = obtenerConexion();

//con este codigo se muestran los servicios registrados
$stmt = $conexion->prepare("SELECT s.id, c.nombre AS cliente, s.descripcion, s.estado FROM servicios s
JOIN clientes c ON s.cliente_id = c.id ORDER BY s.id DESC");

$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Servicios</title>
</head>
<body>
    <h1>Lista de Servicios</h1>
    <!--<a href="crear_servicios.php">Registrar Nuevo Servicio</a>-->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th> 
                <th>Cliente</th>
                <th>Descripcion</th>
                <th>Estado</th>
                <th>Acciones</th>             
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicios as $servicio): ?>
                <tr>
                <td><?= $servicio['id'] ?></td>
                <td><?= $servicio['cliente'] ?></td>
                <td><?= $servicio['descripcion'] ?></td>
                <td><?= $servicio['estado'] ?></td>
                <td>
                    <a href="editar_servicios.php?id=<?= $servicio['id'] ?>">Editar</a>
                    <a href="eliminar_servicios.php?id=<?= $servicio['id'] ?>" onclick="return confirm('¿Seguro que deseas elimar este servicio?')">Eliminar</a>

                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="crear_servicios.php">Agregar Servicio</a></p>
    <p><a href="../index.php">Volver al Dashboard</a></p>
</body>
</html>
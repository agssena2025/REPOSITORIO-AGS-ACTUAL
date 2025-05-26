<?php 

session_start();
require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conexion = obtenerConexion();

$sql = "SELECT c.id, cl.nombre AS cliente, c.tipo, c.descripcion, c.total, c.fecha_creacion FROM cotizaciones c
JOIN clientes cl ON c.cliente_id = cl.id
ORDER BY c.fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Cotizaciones</title>
</head>
<body>
    <h1>Listado de Cotizaciones</h1>

    <table border="1" cellpadding="10" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th>Total</th>
                <th>Fecha de Creacion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cotizaciones as $cotizacion): ?>
                <tr>
                    <td><?= htmlspecialchars($cotizacion['id']) ?></td>
                    <td><?= htmlspecialchars($cotizacion['cliente']) ?></td>
                    <td><?= htmlspecialchars($cotizacion['tipo']) ?></td>
                    <td><?= htmlspecialchars($cotizacion['descripcion']) ?></td>
                    <td><?= htmlspecialchars($cotizacion['total']) ?></td>
                    <td><?= htmlspecialchars($cotizacion['fecha_creacion']) ?></td>

                    <td>
                        <a href="editar_cotizaciones.php?id=<?= $cotizacion['id'] ?>">Editar></a>
                        <a href="eliminar_cotizaciones.php?id=<?= $cotizacion['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">Eliminar</Eliminar></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="crear_cotizaciones.php">Agregar Cotización</a></p>
    <p><a href="../index.php">Volver al Dashboard</a></p>
</body>
</html>
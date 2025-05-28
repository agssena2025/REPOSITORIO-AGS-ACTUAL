<?php
session_start();
require_once '../includes/validar_sesion.php';
require_once '../db/conexion.php';

// Solo usuarios con permisos (ejemplo)
if (!in_array($_SESSION['rol'], ['administrador', 'coordinador', 'tecnico'])) {
    header("Location: ../index.php");
    exit();
}

$conn = obtenerConexion();

$sql = "SELECT r.id, r.fecha_reporte, c.nombre AS cliente_nombre, u.nombre AS tecnico_nombre, r.descripcion, r.estado 
        FROM reportes r
        JOIN clientes c ON r.cliente_id = c.id
        LEFT JOIN usuarios u ON r.tecnico_id = u.id
        ORDER BY r.fecha_reporte DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Reportes - AGS</title>
</head>
<body>
    <h2>Lista de Reportes</h2>

    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'creado'): ?>
        <p style="color:green;">Reporte creado exitosamente.</p>
    <?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'): ?>
        <p style="color:green;">Reporte eliminado exitosamente.</p>
    <?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] == 'editado'): ?>
        <p style="color:green;">Reporte actualizado exitosamente.</p>
    <?php endif; ?>

    <p><a href="crear_reportes.php">Crear Nuevo Reporte</a></p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Técnico</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reportes) > 0): ?>
                <?php foreach ($reportes as $reporte): ?>
                    <tr>
                        <td><?= htmlspecialchars($reporte['id']) ?></td>
                        <td><?= htmlspecialchars($reporte['fecha_reporte']) ?></td>
                        <td><?= htmlspecialchars($reporte['cliente_nombre']) ?></td>
                        <td><?= htmlspecialchars($reporte['tecnico_nombre'] ?? 'Sin asignar') ?></td>
                        <td><?= htmlspecialchars(substr($reporte['descripcion'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($reporte['estado']) ?></td>
                        <td>
                            <a href="editar_reportes.php?id=<?= $reporte['id'] ?>">Editar</a> | 
                            <a href="eliminar_reportes.php?id=<?= $reporte['id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este reporte?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No hay reportes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

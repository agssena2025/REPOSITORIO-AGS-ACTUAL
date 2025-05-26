<?php
session_start();

require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conn = obtenerConexion();
$sql = "SELECT * FROM clientes";
$stmt = $conn->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="../css/estilo.css">

</head>

<body>

    <?php if (isset($_GET['mensaje'])): ?>
        <p style="color:green;"><?= $_GET['mensaje'] ?></p>
    <?php endif; ?>

    <h1>Listado de Clientes</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= $cliente['nombre'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                    <td><?= $cliente['telefono'] ?></td>
                    <td>
                        <a href="editar_clientes.php?id=<?= $cliente['id'] ?>">Editar></a>
                        <a href="eliminar_clientes.php?id=<?= $cliente['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">Eliminar</Eliminar></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <p><a href="crear_clientes.php">Agregar Cliente</a></p>
    <p><a href="../index.php">Volver al Dashboard</a></p>

</body>

</html>
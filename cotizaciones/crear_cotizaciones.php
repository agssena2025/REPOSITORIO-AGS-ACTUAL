<?php
session_start();
require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conexion = obtenerConexion();

//codigo para obtener clientes para el formulario
$stmt = $conexion->prepare("SELECT id, nombre FROM clientes");
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $total = $_POST['total'] ?? '';

    if (empty($cliente_id) || empty($tipo) || empty($descripcion) || empty($total)) {
        $error = "Todos los campos son obligatorios";
    } else {
        $stmt = $conexion->prepare("INSERT INTO cotizaciones (cliente_id, tipo, descripcion, total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $tipo, $descripcion, $total]);
        header("Location: listar_cotizaciones.php?mensaje=creado");
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cotizaciones</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php include '../includes/menu.php'; ?>
    <h2>Crear Cotización</h2>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Cliente</label>
        <select name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente) { ?>
                <option value="<?= $cliente['id'] ?>">  <?= $cliente['nombre'] ?></option>
            <?php } ?>
        </select>
        <label>Tipo:</label>
        <select name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="producto">Producto</option>
                <option value="servicio">Servicio</option>
        </select>
        <label>Descripcion:</label>
        <textarea name="descripcion" required></textarea>
        <label>Total:</label>
        <input type="number" step="0.01" name="total" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
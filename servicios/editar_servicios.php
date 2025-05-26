<?php
session_start();
require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conexion = obtenerConexion();

if (isset($_GET['id'])) {
    $id_servicio = $_GET['id'];

    // Obtener el servicio por su ID
    $stmt = $conexion->prepare("SELECT * FROM servicios WHERE id = :id_servicio");
    $stmt->bindParam(':id_servicio', $id_servicio);
    $stmt->execute();
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$servicio) {
        echo "Servicio no encontrado";
        exit;
    }

    // Si el formulario ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validación de datos
        $cliente_id = trim($_POST['cliente_id']);
        $descripcion = trim($_POST['descripcion']);
        $estado = $_POST['estado'];

        if (empty($cliente_id) || empty($descripcion)) {
            $error = "Todos los campos son obligatorios.";
        } else {
            try {
                // Actualizar el servicio en la base de datos
                $stmt = $conexion->prepare("UPDATE servicios SET cliente_id = :cliente_id, descripcion = :descripcion, estado = :estado WHERE id = :id_servicio");
                $stmt->bindParam(':cliente_id', $cliente_id);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':id_servicio', $id_servicio);
                $stmt->execute();

                // Redirigir después de la actualización
                header("Location: listar_servicios.php?mensaje=Servicio actualizado correctamente");
                exit;

            } catch (PDOException $e) {
                $error = "Error al actualizar el servicio: " . $e->getMessage();
            }
        }
    }

    // Obtener la lista de clientes
    $stmt = $conexion->prepare("SELECT id, nombre FROM clientes");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID de servicio no especificado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar servicio</title>
</head>
<body>
    <h1>Editar servicio</h1>

    <?php if (!empty($error)): ?>
        <div style="color: red;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?=$cliente['id'] ?>" <?= $cliente['id'] == $servicio['cliente_id'] ? 'selected' : '' ?>>
                    <?= $cliente['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Descripción</label>
        <textarea name="descripcion" required><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
        <br><br>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="pendiente" <?= $servicio['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="en_proceso" <?= $servicio['estado'] == 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="completado" <?= $servicio['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
        </select>
        <br><br>

        <button type="submit">Actualizar Servicio</button>
        <a href="listar_servicios.php">Cancelar</a>
    </form>
</body>
</html>

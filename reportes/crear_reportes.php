<?php
session_start();
require_once '../includes/validar_sesion.php';
require_once '../db/conexion.php';

$fecha_reporte = $cliente_id = $tecnico_id = $descripcion = $estado = $observaciones = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_reporte = $_POST['fecha_reporte'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? '';
    $tecnico_id = $_POST['tecnico_id'] ?? null; 
    $descripcion = $_POST['descripcion'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';
    $observaciones = $_POST['observaciones'] ?? '';

    // Validaciones simples
    if (empty($fecha_reporte)) {
        $errores[] = "La fecha del reporte es obligatoria.";
    }
    if (empty($cliente_id)) {
        $errores[] = "Debe seleccionar un cliente.";
    }
    if (empty($descripcion)) {
        $errores[] = "La descripción es obligatoria.";
    }
    if (!in_array($estado, ['pendiente', 'en proceso', 'cerrado'])) {
        $errores[] = "Estado inválido.";
    }

    // Si no hay errores, insertar en BD
    if (empty($errores)) {
        try {
            $conn = obtenerConexion();
            $sql = "INSERT INTO reportes (fecha_reporte, cliente_id, tecnico_id, descripcion, estado, observaciones)
                    VALUES (:fecha_reporte, :cliente_id, :tecnico_id, :descripcion, :estado, :observaciones)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha_reporte', $fecha_reporte);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            if ($tecnico_id) {
                $stmt->bindParam(':tecnico_id', $tecnico_id, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':tecnico_id', null, PDO::PARAM_NULL);
            }
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':observaciones', $observaciones);

            $stmt->execute();

            // Redirigir a listar_reportes.php tras éxito
            header("Location: listar_reportes.php?mensaje=creado");
            exit();
        } catch (PDOException $e) {
            $errores[] = "Error al guardar el reporte: " . $e->getMessage();
        }
    }
}

// Cargar clientes para el select
$conn = obtenerConexion();
$clientes = $conn->query("SELECT id, nombre FROM clientes ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Cargar técnicos para el select
$tecnicos = $conn->query("SELECT id, nombre FROM usuarios WHERE rol = 'tecnico' ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Reporte - AGS</title>
</head>
<body>
    <h2>Crear Nuevo Reporte</h2>

    <?php if (!empty($errores)): ?>
        <ul style="color:red;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="crear_reportes.php" method="post">
        <label for="fecha_reporte">Fecha del Reporte:</label>
        <input type="date" id="fecha_reporte" name="fecha_reporte" value="<?= htmlspecialchars($fecha_reporte) ?>" required><br><br>

        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">-- Seleccionar cliente --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>" <?= $cliente_id == $cliente['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="tecnico_id">Técnico Asignado:</label>
        <select id="tecnico_id" name="tecnico_id">
            <option value="">-- Sin asignar --</option>
            <?php foreach ($tecnicos as $tecnico): ?>
                <option value="<?= $tecnico['id'] ?>" <?= $tecnico_id == $tecnico['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tecnico['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" rows="4" cols="50" required><?= htmlspecialchars($descripcion) ?></textarea><br><br>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="pendiente" <?= $estado == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="en proceso" <?= $estado == 'en proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="cerrado" <?= $estado == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
        </select><br><br>

        <label for="observaciones">Observaciones:</label><br>
        <textarea id="observaciones" name="observaciones" rows="3" cols="50"><?= htmlspecialchars($observaciones) ?></textarea><br><br>

        <button type="submit">Guardar Reporte</button>
    </form>

    <p><a href="listar_reportes.php">Volver a la lista de reportes</a></p>
</body>
</html>

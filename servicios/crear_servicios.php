<?php

session_start();
require '../includes/validar_sesion.php';
require '../db/conexion.php';

$conexion = obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $descripcion = $_POST['descripcion'];
    $estado = "pendiente"; //estado inicial del servicio

try {
    $stmt = $conexion->prepare("INSERT INTO servicios (cliente_id, descripcion, estado) VALUES (:cliente_id, :descripcion, :estado)");
    $stmt->bindParam(':cliente_id', $cliente_id);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':estado', $estado);
    $stmt->execute();

    echo "<script>alert('Servicio registrado correctamente'); window.location.href = 'listar_servicios.php'</script>";

    } catch (PDOException $e) {

    echo "Error al registrar el servicio : " . $e->getMessage();

    }

}

//obtener listar de clientes para el formulario

$stmt = $conexion->prepare("SELECT id, nombre FROM clientes");
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar servicios</title>
</head>
<body>
    <h1>Registrar nuevo servicio</h1>
    <form method="POST">
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?=$cliente['id'] ?>"><?= $cliente['nombre'] ?></option>
                <?php endforeach; ?>
        </select>
        <br><br>

        <label>descripción</label>
        <textarea name="descripcion" required></textarea>
        <br><br>

        <button type="submit">Registrar Servicio</button>
        <a href="listar_servicios.php">Cancelar</a>
    </form>
</body>
</html>


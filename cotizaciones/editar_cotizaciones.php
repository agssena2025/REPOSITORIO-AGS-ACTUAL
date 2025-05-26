<?php
session_start();

require '../includes/validar_sesion.php';
require '../db/conexion.php';

$id = $_GET['id'];
$conexion = obtenerConexion();

$stmt = $conexion->prepare("SELECT * FROM  cotizaciones WHERE id = :id");
$stmt->bindParam(':id' , $id);
$stmt->execute();
$cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $total = $_POST['total'];

    $stmt =$conexion->prepare("UPDATE cotizaciones SET tipo = :tipo, descripcion = :descripcion, total = :total WHERE id = :id");
    $stmt->bindParam(':tipo',$tipo);
    $stmt->bindParam(':descripcion',$descripcion);
    $stmt->bindParam(':total',$total);
    $stmt->bindParam(':id',$id);
    //$stmt->bindParam(':cliente_id',$cliente_id); error a revisar

    if ($stmt->execute()) {
        echo "<script>alert('Cotización actualizado correctamente'); window.location.href = 'listar_cotizaciones.php'</script>";
        

    } else {
        echo "<script>alert('Error al actualizar cotización');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cotizaciones</title>
</head>
<body>
<h1>Editar Cotización</h1>
    <form method="POST">
    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="">Seleccione un tipo:</option>
        <option value="producto" <?= $cotizacion['tipo'] === 'producto' ? 'selected' : '' ?>>Producto</option>
        <option value="servicio" <?= $cotizacion['tipo'] === 'servicio' ? 'selected' : '' ?>>Servicio</option>
    </select>


        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= $cotizacion['descripcion'] ?>" required><br><br>

        <label>Total:</label>
        <input type="number" step="0.01" name="total" value="<?= $cotizacion['total'] ?>" required><br><br>

        <button type="submit">Actualizar Cotización</button>
        <a href="listar_cotizaciones.php">Cancelar</a>
    </form>
</body>
</html>
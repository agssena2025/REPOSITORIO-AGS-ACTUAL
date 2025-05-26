<?php
session_start();

require '../includes/validar_sesion.php';
require '../db/conexion.php';

$id = $_GET['id'];
$conexion = obtenerConexion();

$stmt = $conexion->prepare("SELECT * FROM  clientes WHERE id = :id");
$stmt->bindParam(':id' , $id);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $stmt =$conexion->prepare("UPDATE clientes SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id");
    $stmt->bindParam(':nombre',$nombre);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':telefono',$telefono);
    $stmt->bindParam(':id',$id);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente actualizado correctamente'); window.location.href = 'listar_clientes.php'</script>";
        

    } else {
        echo "<script>alert('Error al actualizar cliente');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
</head>
<body>
    <h1>Editar Cliente</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $cliente['nombre'] ?>" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $cliente['email'] ?>" required><br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $cliente['telefono'] ?>" required><br><br>

        <button type="submit">Actualizar Cliente</button>
        <a href="listar_clientes.php">Cancelar</a>
    </form>
</body>
</html>

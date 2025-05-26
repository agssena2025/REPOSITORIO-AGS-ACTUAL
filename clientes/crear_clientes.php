<?php
require '../includes/validar_sesion.php';
require '../db/conexion.php';
$error = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    try {
        $conn = obtenerConexion();
        $sql = "INSERT INTO clientes (nombre, email, telefono) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $telefono]);

        $mensaje = 'cliente registrado correctamente.';

    } catch (PDOException $e) {
        $error = 'error al registrar el cliente.';
        error_log('error al registrar el cliente: ' . $e->getMessage());

    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente</title>

</head>
<body>
    <h1>Registrar Cliente</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($mensaje): ?>
        <p style="color:green;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" action="crear_clientes.php">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>


        <label>telefono:</label>
        <input type="text" name="telefono" required><br>

        <button type="submit">Guardar Cliente</button>
    </form>

    <p><a href="listar_clientes.php">Volver al listado de clientes</a></p>

</body>
</html>

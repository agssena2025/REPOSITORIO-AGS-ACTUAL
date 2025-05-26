<?php
session_start();
require '../db/conexion.php';

$error = '';
$mensaje = '';

if (isset($_GET['registro']) && $_GET['registro'] == 'exitoso') {
    $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = trim($_POST['documento']);
    $clave = $_POST['clave'];

    try {
        $conn = obtenerConexion();
        $sql = "SELECT * FROM usuarios WHERE documento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$documento]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario !== false && password_verify($clave, $usuario['clave'])) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['id_usuario'] = $usuario['id'];
            
            header('Location: ../index.php');
            exit;
        } else {
            $error = 'Documento o clave incorrectos';
        }
    } catch (PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        $error = 'Error al conectar con la base de datos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AGS</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Ingreso al Sistema AGS</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($mensaje): ?>
        <p style="color:green;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Documento:</label>
        <input type="text" name="documento" required><br>

        <label>Clave:</label>
        <input type="password" name="clave" required><br>

        <button type="submit">Ingresar</button>
    </form>

    <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
</body>
</html>

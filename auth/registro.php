<?php
require '../db/conexion.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['identificacion'];
    $correo = $_POST['correo'];
    $clave = $_POST['contraseña'];
    $rol = $_POST['rol'];

    // Concatenamos el nombre completo
    $nombreCompleto = $nombre . ' ' . $apellido;

    // Encriptamos la contraseña
    $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);

    try {
        $conn = obtenerConexion();

        $sql = "INSERT INTO usuarios (documento, nombre, email, clave, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$documento, $nombreCompleto, $correo, $claveEncriptada, $rol]);

        // Redirige al login después de registrar
        header('Location: login.php?registro=exitoso');
        exit;

    } catch (PDOException $e) {
        echo "Error al registrar usuario: " . $e->getMessage();
    }
}

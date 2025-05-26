<?php
session_start();

require 'includes/validar_sesion.php'; // Asegura que solo usuarios autenticados accedan
require 'includes/menu.php'; // Menú dinámico según el rol

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Dashboard - AGS</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Bienvenido al Sistema AGS</h1>

    <p>Usuario: <strong><?= $_SESSION['usuario'] ?></strong></p>
    <p>Rol: <strong><?= $_SESSION['rol'] ?></strong></p>

    <h2>Panel de Control</h2>


    <?php if (trim($_SESSION['rol']) === 'Administrador'): ?>
        <p><a href="clientes/listar_clientes.php">Gestión de Clientes</a></p>
        <p><a href="servicios/listar_servicios.php">Gestión de Servicios</a></p>
        <p><a href="cotizaciones/listar_cotizaciones.php">Gestión de Cotizaciones</a></p>
        <p><a href="activos/listar_activos.php">Gestión de Activos</a></p>
        <p><a href="reportes/listar_reportes.php">Generación de Reportes</a></p>
    <?php elseif (trim($_SESSION['rol']) === 'Coordinador'): ?>
        <p><a href="clientes/listar_clientes.php">Gestión de Clientes</a></p>
        <p><a href="servicios/listar_servicios.php">Gestión de Servicios</a></p>
        <p><a href="cotizaciones/listar_cotizaciones.php">Gestión de Cotizaciones</a></p>
        <p><a href="activos/listar_activos.php">Gestión de Activos</a></p>
        <p><a href="reportes/listar_reportes.php">Generación de Reportes</a></p>
    <?php elseif (trim($_SESSION['rol']) === 'Técnico'): ?>
        <p><a href="servicios/mis_ordenes.php">Mis Órdenes de Trabajo</a></p>
    <?php endif; ?>

    <p><a href="auth/logout.php">Cerrar Sesión</a></p>
</body>
</html>



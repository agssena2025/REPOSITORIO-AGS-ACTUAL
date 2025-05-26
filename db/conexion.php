<?php
function obtenerConexion() {
    static $conn = null;

    if ($conn === null) {
        $host = 'localhost';
        $dbname = 'ags_db';
        $username = 'root';  // Ajustar si hay un usuario diferente
        $password = '';      // Ajustar si hay una contraseña en MySQL

        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die('Error al conectar a la base de datos.');
        }
    }
    return $conn;
}

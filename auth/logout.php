<?php
session_start();

// Destruir todas las variables de sesión
session_unset();
session_destroy();

// Borrar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redireccionar al login
header('Location: login.php');
exit;



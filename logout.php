<?php
session_start();

// Comprobar si hay una sesión activa
if(session_status() === PHP_SESSION_ACTIVE) {
    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Finalmente, destruir la sesión
    session_destroy();
}

// Redirigir al usuario a la página de inicio o a donde desees, excepto si ya está en login.php
if(basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: login.php");
    exit;
}




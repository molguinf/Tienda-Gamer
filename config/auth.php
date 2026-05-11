<?php
session_start();

/**
 * Verifica si el usuario ha iniciado sesión y ha pasado el 2FA
 */
function estaLogueado() {
    return isset($_SESSION['id_usuario']) && $_SESSION['estado_2fa_verificado'] === true;
}

/**
 * Redirige si el usuario no ha iniciado sesión
 */
function requerirLogin() {
    if (!estaLogueado()) {
        header("Location: /TiendaGamer/views/auth/login.php");
        exit();
    }
}

/**
 * Verifica si el usuario tiene el rol de Administrador
 */
function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

/**
 * Protege rutas exclusivas para el Administrador
 */
function requerirAdmin() {
    requerirLogin();
    if (!esAdmin()) {
        // Si no es admin, lo mandamos al index o a una página de error
        header("Location: /TiendaGamer/index.php?error=acceso_denegado");
        exit();
    }
}

/**
 * Limpia la sesión (Logout)
 */
function cerrarSesion() {
    session_unset();
    session_destroy();
    header("Location: /TiendaGamer/index.php");
    exit();
}
?>
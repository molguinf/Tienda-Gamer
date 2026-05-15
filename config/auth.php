<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario completó TODO el proceso (Login + 2FA)
 */
function estaLogueado() {
    return isset($_SESSION['id_usuario']) && 
           isset($_SESSION['estado_2fa_verificado']) && 
           $_SESSION['estado_2fa_verificado'] === true;
}

/**
 * NUEVA FUNCIÓN: Verifica si el usuario está "en proceso" de 2FA
 * Esto permite que el usuario entre a la página de verificar_2fa.php
 */
function estaEnProceso2FA() {
    return isset($_SESSION['temp_id_usuario']);
}

/**
 * Redirige si el usuario no ha iniciado sesión completa
 */
function requerirLogin() {
    if (!estaLogueado()) {
        header("Location: /TiendaGamer/views/auth/login.php");
        exit();
    }
}

/**
 * Protege la página de verificación 2FA
 * Solo deja entrar si el usuario ya puso bien su clave pero falta el código
 */
function requerirProceso2FA() {
    // Si ya está logueado completo, lo mandamos al index
    if (estaLogueado()) {
        header("Location: /TiendaGamer/index.php");
        exit();
    }
    // Si NO tiene ni siquiera el inicio temporal, al login
    if (!isset($_SESSION['temp_id_usuario'])) {
        header("Location: /TiendaGamer/views/auth/login.php?error=sesion_expirada");
        exit();
    }
}

// ... el resto de tus funciones (esAdmin, requerirAdmin, etc.) se quedan igual

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
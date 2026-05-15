<?php
// 1. Iniciar el manejo de sesiones para poder destruirlas
session_start();

// 2. Limpiar todas las variables de sesión (carrito, favoritos, datos de usuario)
$_SESSION = array();

// 3. Si se desea destruir la cookie de sesión por completo (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión en el servidor
session_destroy();

// 5. Redirigir al usuario a la página de inicio o al login
header("Location: ../index.php");
exit();
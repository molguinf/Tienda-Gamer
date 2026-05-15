<?php
/**
 * Controlador de Verificación 2FA
 * Valida el código temporal y otorga acceso final al sistema.
 */

require_once '../config/db.php';
require_once '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_ingresado = trim($_POST['codigo']);
    $id_usuario = $_SESSION['temp_id_usuario'];

    try {
        // 1. Consultar el código real guardado en la base de datos
        $stmt = $pdo->prepare("SELECT codigo_2fa, nombre, correo, rol FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch();

        // 2. Comparar el código
        if ($usuario && $usuario['codigo_2fa'] === $codigo_ingresado) {
            
            // ¡ÉXITO! Convertimos la sesión temporal en sesión OFICIAL
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['rol']        = $usuario['rol'];
            $_SESSION['correo']     = $usuario['correo'];
            $_SESSION['estado_2fa_verificado'] = true; // El escudo de auth.php ahora nos dejará pasar

            // Limpiamos el código de la base de datos por seguridad
            $clear = $pdo->prepare("UPDATE usuario SET codigo_2fa = NULL WHERE id_usuario = ?");
            $clear->execute([$id_usuario]);

            // Limpiar variables temporales
            unset($_SESSION['temp_id_usuario']);
            unset($_SESSION['temp_nombre']);

            // Redirigir al inicio del sistema
            header("Location: ../index.php");
            exit();

        } else {
            // Código incorrecto
            header("Location: ../views/auth/verificar_2fa.php?error=codigo_invalido");
            exit();
        }

    } catch (PDOException $e) {
        die("Error en la verificación: " . $e->getMessage());
    }
} else {
    header("Location: ../views/auth/login.php");
    exit();
}
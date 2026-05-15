<?php
/**
 * Controlador de Registro - Tienda Gamer
 * Procesa la creación de nuevos usuarios de forma segura.
 */

// 1. Incluir la conexión y el manejo de sesiones
require_once '../config/db.php';
require_once '../config/auth.php';

// 2. Verificar que los datos vengan por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Limpiar entradas para evitar espacios en blanco o caracteres extraños
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirm_pass = $_POST['confirm_contrasena'];

    // 3. Validaciones de Seguridad
    
    // Verificar campos vacíos
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        header("Location: ../views/auth/registro.php?error=campos_vacios");
        exit();
    }
    // Verificar que las contraseñas coincidan
    if ($contrasena !== $confirm_pass) {
        header("Location: ../views/auth/registro.php?error=pass_no_coinciden");
        exit();
    }

    try {
        // 4. Verificar si el correo ya existe en la base de datos
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: ../views/auth/registro.php?error=email_existe");
            exit();
        }

        // 5. Encriptar la contraseña (Uso obligatorio de password_hash)
        // Esto crea un hash seguro que no se puede "desencriptar" a simple vista
        $password_segura = password_hash($contrasena, PASSWORD_BCRYPT);

        // 6. Insertar el nuevo usuario
        // El rol por defecto es 'cliente' y estado_2fa es 0 (falso)
        $sql = "INSERT INTO usuario (nombre, correo, contrasena, rol, estado_2fa) 
                VALUES (:nombre, :correo, :pass, 'cliente', 0)";
        
        $insert = $pdo->prepare($sql);
        $resultado = $insert->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':pass'   => $password_segura
        ]);

        if ($resultado) {
            // Registro exitoso - Redirigir al login con mensaje de éxito
            header("Location: ../views/auth/login.php?registro=exito");
            exit();
        } else {
            header("Location: ../views/auth/registro.php?error=db_error");
            exit();
        }

    } catch (PDOException $e) {
        // Error técnico (puedes loguearlo, aquí lo mostramos para desarrollo)
        die("Error en el sistema: " . $e->getMessage());
    }

} else {
    // Si alguien intenta entrar a este archivo por URL sin enviar el formulario
    header("Location: ../views/auth/registro.php");
    exit();
}
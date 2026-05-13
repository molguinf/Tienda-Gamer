<?php
require_once '../config/db.php';
require_once '../config/auth.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id_usuario = $_SESSION['id_usuario'];

    if ($accion === 'actualizar_datos') {
        $nombre = trim($_POST['nombre']);
        
        try {
            $stmt = $pdo->prepare("UPDATE Usuario SET nombre = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $id_usuario]);
            
            // Actualizamos la sesión para que el header cambie el nombre al instante
            $_SESSION['nombre'] = $nombre;
            
            header("Location: ../views/cliente/perfil.php?msj=datos_actualizados");
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($accion === 'cambiar_pass') {
        $actual = $_POST['pass_actual'];
        $nueva = $_POST['nueva_pass'];
        $confirm = $_POST['confirm_pass'];

        // 1. Validar que las nuevas coincidan
        if ($nueva !== $confirm) {
            header("Location: ../views/cliente/perfil.php?error=no_coinciden");
            exit();
        }

        // 2. Validar complejidad (mínimo 8 caracteres)
        if (strlen($nueva) < 8) {
            header("Location: ../views/cliente/perfil.php?error=pass_debil");
            exit();
        }

        try {
            // 3. Verificar si la contraseña actual es correcta
            $stmt = $pdo->prepare("SELECT contrasena FROM Usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $user = $stmt->fetch();

            if (password_verify($actual, $user['contrasena'])) {
                // 4. Encriptar y guardar la nueva
                $hash = password_hash($nueva, PASSWORD_BCRYPT);
                $update = $pdo->prepare("UPDATE Usuario SET contrasena = ? WHERE id_usuario = ?");
                $update->execute([$hash, $id_usuario]);

                header("Location: ../views/cliente/perfil.php?msj=pass_actualizada");
            } else {
                header("Location: ../views/cliente/perfil.php?error=pass_actual_incorrecta");
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
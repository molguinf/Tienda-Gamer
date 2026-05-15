<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/Exception.php';
require '../libs/PHPMailer/PHPMailer.php';
require '../libs/PHPMailer/SMTP.php';

require_once '../config/db.php';
require_once '../config/auth.php'; // Aquí inicia session_start()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    if (empty($correo) || empty($contrasena)) {
        header("Location: ../views/auth/login.php?error=campos_vacios");
        exit();
    }

    try {
        // 1. Buscar al usuario
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        // 2. Verificar si existe y la contraseña es correcta
        // 2. Verificar si existe y la contraseña es correcta
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            
            // 3. GENERAR CÓDIGO 2FA
            $codigo_2fa = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // 4. Guardar código en la DB
            $update = $pdo->prepare("UPDATE usuario SET codigo_2fa = ? WHERE id_usuario = ?");
            $update->execute([$codigo_2fa, $usuario['id_usuario']]);

            $mail = new PHPMailer(true);
            try {
                // ... Configuración de PHPMailer (Host, Username, Password, etc.) ...
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'constanzaolguinflores.1@gmail.com'; 
                $mail->Password   = 'lfug jhcf bnso smpr'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('no-reply@tiendagamer.com', 'Seguridad Tienda Gamer');
                $mail->addAddress($usuario['correo'], $usuario['nombre']);
                $mail->isHTML(true);
                $mail->Subject = 'Codigo de Acceso: ' . $codigo_2fa;
                $mail->Body    = "Tu código es: <b>$codigo_2fa</b>";

                $mail->send();

                // 5. Crear Sesión TEMPORAL
                $_SESSION['temp_id_usuario'] = $usuario['id_usuario'];
                $_SESSION['temp_nombre'] = $usuario['nombre'];
                $_SESSION['temp_rol'] = $usuario['rol'];
                $_SESSION['estado_2fa_verificado'] = false; 

                header("Location: ../views/auth/verificar_2fa.php");
                exit();

            } catch (Exception $e) {
                header("Location: ../views/auth/login.php?error=error_correo");
                exit();
            } // <--- AQUÍ TERMINA EL CATCH DE PHPMAILER

        } else {
            // Este else es si el password_verify falló
            header("Location: ../views/auth/login.php?error=datos_incorrectos");
            exit();
        } // <--- AQUÍ CIERRA EL IF DEL USUARIO

    } catch (PDOException $e) {
        // ESTE ES TU BLOQUE DE LA LÍNEA 83
        die("Error en el login: " . $e->getMessage());
    } // <--- AQUÍ TERMINA EL TRY PRINCIPAL
} else {
    header("Location: ../views/auth/login.php");
    exit();
}
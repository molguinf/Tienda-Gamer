<?php 
require_once '../../config/auth.php'; // Para leer la sesión temporal

requerirProceso2FA(); 
include '../includes/header.php'; 

// Si no hay un login previo, lo mandamos afuera
if (!isset($_SESSION['temp_id_usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <i class="bi bi-shield-check text-primary display-4 mb-3"></i>
                <h3 class="fw-bold">Verificación 2FA</h3>
                <p class="text-muted small">Ingresa el código de 6 dígitos que aparece en tu base de datos (Simulación).</p>

                <form action="../../controllers/Verificar2FAController.php" method="POST">
                    <div class="mb-3">
                        <input type="text" name="codigo" class="form-control form-control-lg text-center fw-bold" placeholder="000000" maxlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Verificar Acceso</button>
                </form>
                
                <div class="mt-3">
                    <a href="login.php" class="text-decoration-none small text-secondary">Cancelar Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
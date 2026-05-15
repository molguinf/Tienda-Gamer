<?php 
include '../includes/header.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock-fill text-primary display-1"></i>
                        <h2 class="fw-bold mt-2">Bienvenido</h2>
                        <p class="text-muted">Ingresa a tu cuenta Gamer</p>
                    </div>

                    <!-- Alertas de estado -->
                    <?php if(isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
                        <div class="alert alert-success py-2 small text-center">¡Registro exitoso! Ya puedes iniciar sesión.</div>
                    <?php endif; ?>

                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger py-2 small text-center">
                            <?php echo ($_GET['error'] == 'datos_incorrectos') ? "Correo o contraseña inválidos." : "Acceso denegado."; ?>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/LoginController.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                                <input type="email" name="correo" class="form-control bg-light border-start-0" placeholder="usuario@gmail.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                                <input type="password" name="contrasena" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">Iniciar Sesión</button>
                        </div>

                        <div class="text-center">
                            <p class="small text-muted">¿No tienes cuenta? <a href="registro.php" class="text-primary fw-bold text-decoration-none">Regístrate aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
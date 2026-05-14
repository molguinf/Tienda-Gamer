<?php 
// Incluimos el header que ya contiene el inicio de sesión y los estilos
include '../includes/header.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary display-1"></i>
                        <h2 class="fw-bold mt-2">Crear Cuenta</h2>
                        <p class="text-muted">Únete a la comunidad gamer más grande</p>
                    </div>

                    <!-- Mensajes de Error/Éxito (Se activarán con el controlador) -->
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger small py-2">
                            <?php 
                                echo ($_GET['error'] == 'email_existe') ? "El correo ya está registrado." : "Ocurrió un error inesperado.";
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/RegistroController.php" method="POST" id="formRegistro">
                        
                        <!-- Nombre Completo -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                                <input type="text" name="nombre" class="form-control bg-light border-start-0" placeholder="Ej. Constanza Mariana" required>
                            </div>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                                <input type="email" name="correo" class="form-control bg-light border-start-0" placeholder="usuario@gmail.com" required>
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-secondary"></i></span>
                                <input type="password" name="contrasena" id="pass" class="form-control bg-light border-start-0" placeholder="••••••••" required minlength="8">
                                <span class="input-group-text bg-light border-start-0" style="cursor: pointer;" onclick="togglePass()">
                                    <i class="bi bi-eye-slash text-secondary" id="eyeIcon"></i>
                                </span>
                            </div>
                            <div class="form-text mt-1" style="font-size: 0.75rem;">Mínimo 8 caracteres.</div>
                        </div>

                        <!-- Confirmar Contraseña (Validación futura) -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-secondary"></i></span>
                                <input type="password" name="confirm_contrasena" id="confirm_pass" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Botón de Registro -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                Registrarme Ahora
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="small text-muted">¿Ya tienes cuenta? <a href="login.php" class="text-primary fw-bold text-decoration-none">Inicia Sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para mejorar la experiencia del usuario -->
<script>
    // Función para ver/ocultar contraseña
    function togglePass() {
        const passInput = document.getElementById('pass');
        const icon = document.getElementById('eyeIcon');
        if (passInput.type === "password") {
            passInput.type = "text";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passInput.type = "password";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }

    // Validación básica de contraseñas iguales
    <script>
    document.getElementById('formRegistro').onsubmit = function() {
        const pass = document.getElementById('pass').value;
        const confirm = document.getElementById('confirm_pass').value;
        
        // REGLA: Mínimo 8 caracteres, al menos una letra, un número y un carácter especial
        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!strongRegex.test(pass)) {
            alert("La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, un número y un carácter especial (@$!%*?&).");
            return false;
        }

        if (pass !== confirm) {
            alert("Las contraseñas no coinciden.");
            return false;
        }
        return true;
    };
</script>
</script>

<?php 
include '../includes/footer.php'; 
?>
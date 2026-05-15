<?php 
include '../includes/header.php'; 
?>

<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card auth-card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <!-- HEADER -->
                    <div class="text-center mb-4">

                        <i class="bi bi-person-plus-fill auth-icon"></i>

                        <h2 class="fw-bold mt-3 text-white">

                            Crear Cuenta

                        </h2>

                        <p class="text-muted">

                            Únete a la comunidad gamer más grande

                        </p>

                    </div>

                    <!-- ALERTAS -->
                    <?php if(isset($_GET['error'])): ?>

                        <div class="alert alert-danger small py-2 rounded-3">

                            <?php 
                            
                            echo ($_GET['error'] == 'email_existe') 
                            
                            ? "El correo ya está registrado." 
                            
                            : "Ocurrió un error inesperado.";
                            
                            ?>

                        </div>

                    <?php endif; ?>

                    <!-- FORM -->
                    <form 
                        action="../../controllers/RegistroController.php"
                        method="POST"
                        id="formRegistro"
                    >

                        <!-- NOMBRE -->
                        <div class="mb-3">

                            <label class="form-label small fw-bold">

                                Nombre Completo

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-person"></i>

                                </span>

                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control"
                                    required
                                >

                            </div>

                        </div>

                        <!-- CORREO -->
                        <div class="mb-3">

                            <label class="form-label small fw-bold">

                                Correo Electrónico

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>

                                <input
                                    type="email"
                                    name="correo"
                                    class="form-control"
                                    placeholder="usuario@gmail.com"
                                    required
                                >

                            </div>

                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="mb-3">

                            <label class="form-label small fw-bold">

                                Contraseña

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-lock"></i>

                                </span>

                                <input
                                    type="password"
                                    name="contrasena"
                                    id="pass"
                                    class="form-control"
                                    placeholder="••••••••"
                                    required
                                    minlength="8"
                                >

                                <span
                                    class="input-group-text"
                                    style="cursor:pointer;"
                                    onclick="togglePass()"
                                >

                                    <i
                                        class="bi bi-eye-slash"
                                        id="eyeIcon"
                                    ></i>

                                </span>

                            </div>

                            <div class="form-text mt-2">

                                Mínimo 8 caracteres.

                            </div>

                        </div>

                        <!-- CONFIRMAR -->
                        <div class="mb-4">

                            <label class="form-label small fw-bold">

                                Confirmar Contraseña

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-shield-lock"></i>

                                </span>

                                <input
                                    type="password"
                                    name="confirm_contrasena"
                                    id="confirm_pass"
                                    class="form-control"
                                    placeholder="••••••••"
                                    required
                                >

                            </div>

                        </div>

                        <!-- BOTON -->
                        <div class="d-grid mb-3">

                            <button
                                type="submit"
                                class="btn auth-btn btn-lg fw-bold"
                            >

                                <i class="bi bi-controller me-2"></i>

                                Registrarme Ahora

                            </button>

                        </div>

                        <!-- LOGIN -->
                        <div class="text-center">

                            <p class="small text-muted mb-0">

                                ¿Ya tienes cuenta?

                                <a
                                    href="login.php"
                                    class="text-primary fw-bold text-decoration-none"
                                >

                                    Inicia Sesión

                                </a>

                            </p>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- SCRIPT -->
<script>

    // MOSTRAR / OCULTAR PASSWORD
    function togglePass() {

        const passInput = document.getElementById('pass');

        const icon = document.getElementById('eyeIcon');

        if (passInput.type === "password") {

            passInput.type = "text";

            icon.classList.replace(
                'bi-eye-slash',
                'bi-eye'
            );

        } else {

            passInput.type = "password";

            icon.classList.replace(
                'bi-eye',
                'bi-eye-slash'
            );

        }

    }


    // VALIDACION
    document.getElementById('formRegistro').onsubmit = function() {

        const pass = document.getElementById('pass').value;

        const confirm = document.getElementById('confirm_pass').value;

        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!strongRegex.test(pass)) {

            alert(
                "La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, un número y un carácter especial."
            );

            return false;

        }

        if (pass !== confirm) {

            alert("Las contraseñas no coinciden.");

            return false;

        }

        return true;

    };

</script>

<?php 
include '../includes/footer.php'; 
?>
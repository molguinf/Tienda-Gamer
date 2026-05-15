<?php 
include '../includes/header.php'; 
?>

<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card auth-card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <!-- HEADER -->
                    <div class="text-center mb-4">

                        <i class="bi bi-shield-lock-fill auth-icon"></i>

                        <h2 class="fw-bold mt-3 text-white">

                            Bienvenido

                        </h2>

                        <p class="text-muted">

                            Ingresa a tu cuenta Gamer

                        </p>

                    </div>

                    <!-- ALERTAS -->
                    <?php if(isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>

                        <div class="alert alert-success py-2 small text-center rounded-3">

                            ¡Registro exitoso! Ya puedes iniciar sesión.

                        </div>

                    <?php endif; ?>

                    <?php if(isset($_GET['error'])): ?>

                        <div class="alert alert-danger py-2 small text-center rounded-3">

                            <?php 
                            
                            echo ($_GET['error'] == 'datos_incorrectos') 
                            
                            ? "Correo o contraseña inválidos." 
                            
                            : "Acceso denegado."; 
                            
                            ?>

                        </div>

                    <?php endif; ?>

                    <!-- FORM -->
                    <form 
                        action="../../controllers/LoginController.php"
                        method="POST"
                    >

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

                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <label class="form-label small fw-bold">

                                Contraseña

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-key"></i>

                                </span>

                                <input
                                    type="password"
                                    name="contrasena"
                                    id="loginPass"
                                    class="form-control"
                                    placeholder="••••••••"
                                    required
                                >

                                <span
                                    class="input-group-text"
                                    style="cursor:pointer;"
                                    onclick="toggleLoginPass()"
                                >

                                    <i
                                        class="bi bi-eye-slash"
                                        id="loginEye"
                                    ></i>

                                </span>

                            </div>

                        </div>

                        <!-- BOTON -->
                        <div class="d-grid mb-3">

                            <button
                                type="submit"
                                class="btn auth-btn btn-lg fw-bold"
                            >

                                <i class="bi bi-controller me-2"></i>

                                Iniciar Sesión

                            </button>

                        </div>

                        <!-- REGISTRO -->
                        <div class="text-center">

                            <p class="small text-muted mb-0">

                                ¿No tienes cuenta?

                                <a
                                    href="registro.php"
                                    class="text-primary fw-bold text-decoration-none"
                                >

                                    Regístrate aquí

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

    function toggleLoginPass(){

        const input = document.getElementById('loginPass');

        const icon = document.getElementById('loginEye');

        if(input.type === "password"){

            input.type = "text";

            icon.classList.replace(
                'bi-eye-slash',
                'bi-eye'
            );

        }else{

            input.type = "password";

            icon.classList.replace(
                'bi-eye',
                'bi-eye-slash'
            );

        }

    }

</script>

<?php include '../includes/footer.php'; ?>
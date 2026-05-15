<?php 

require_once '../../config/auth.php';

requerirProceso2FA();

include '../includes/header.php';

// Verificar sesión temporal
if (!isset($_SESSION['temp_id_usuario'])) {

    header("Location: login.php");

    exit();

}

?>

<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-md-5 col-lg-4">

            <div class="card auth-card shadow-lg border-0 rounded-4">

                <div class="card-body p-5 text-center">

                    <!-- ICON -->
                    <div class="mb-4">

                        <i class="bi bi-shield-lock-fill auth-icon"></i>

                    </div>

                    <!-- TITULO -->
                    <h2 class="fw-bold text-white mb-3">

                        Verificación 2FA

                    </h2>

                    <!-- TEXTO -->
                    <p class="text-muted mb-4">

                        Ingresa el código de 6 dígitos enviado a tu correo electrónico.

                    </p>

                    <!-- FORM -->
                    <form 
                        action="../../controllers/Verificar2FAController.php"
                        method="POST"
                    >

                        <!-- CODIGO -->
                        <div class="mb-4">

                            <input
                                type="text"
                                name="codigo"
                                class="form-control text-center fw-bold fs-3"
                                placeholder="000000"
                                maxlength="6"
                                required
                                autocomplete="off"
                            >

                        </div>

                        <!-- BOTON -->
                        <div class="d-grid mb-3">

                            <button
                                type="submit"
                                class="btn auth-btn btn-lg fw-bold"
                            >

                                <i class="bi bi-shield-check me-2"></i>

                                Verificar Acceso

                            </button>

                        </div>

                    </form>

                    <!-- CANCELAR -->
                    <div class="mt-3">

                        <a
                            href="login.php"
                            class="text-decoration-none text-secondary small"
                        >

                            Cancelar Inicio de Sesión

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
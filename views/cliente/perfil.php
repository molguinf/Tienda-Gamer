<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/db.php';
require_once '../../config/auth.php';

// Solo usuarios logueados
requerirLogin();

include '../includes/header.php';

// Obtener datos del usuario
$stmt = $pdo->prepare("
    SELECT 
        nombre,
        correo,
        rol,
        fecha_registro
    FROM usuario 
    WHERE id_usuario = ?
");

$stmt->execute([
    $_SESSION['id_usuario']
]);

$user = $stmt->fetch();
?>

<div class="container my-5">

    <div class="row g-4">

        <!-- SIDEBAR PERFIL -->
        <div class="col-lg-4">

            <div class="card profile-card shadow-lg rounded-4 text-center p-4 h-100">

                <div class="mb-3">

                    <i 
                        class="bi bi-person-circle"
                        style="
                            font-size: 6rem;
                            color: #4cc9f0;
                        "
                    ></i>

                </div>

                <h3 class="fw-bold mb-2">

                    <?php
                    echo htmlspecialchars(
                        $user['nombre']
                    );
                    ?>

                </h3>

                <p class="text-muted mb-4">

                    <?php
                    echo htmlspecialchars(
                        $user['correo']
                    );
                    ?>

                </p>

                <div class="mb-4">

                    <span class="badge bg-primary px-4 py-2 rounded-pill fs-6">

                        Rol:
                        <?php
                        echo strtoupper(
                            $user['rol']
                        );
                        ?>

                    </span>

                </div>

                <hr>

                <p class="small text-muted mb-0">

                    Miembro desde:
                    <?php
                    echo date(
                        'd/m/Y',
                        strtotime(
                            $user['fecha_registro']
                        )
                    );
                    ?>

                </p>

            </div>

        </div>

        <!-- CONTENIDO -->
        <div class="col-lg-8">

            <div class="card profile-card shadow-lg rounded-4 p-4">

                <!-- TABS -->
                <ul class="nav nav-pills gap-2 mb-4">

                    <li class="nav-item">

                        <button
                            class="nav-link active fw-bold"
                            data-bs-toggle="tab"
                            data-bs-target="#datos"
                        >

                            <i class="bi bi-person me-2"></i>
                            Mis Datos

                        </button>

                    </li>

                    <li class="nav-item">

                        <button
                            class="nav-link fw-bold"
                            data-bs-toggle="tab"
                            data-bs-target="#seguridad"
                        >

                            <i class="bi bi-shield-lock me-2"></i>
                            Seguridad

                        </button>

                    </li>

                </ul>

                <div class="tab-content">

                    <!-- DATOS -->
                    <div 
                        class="tab-pane fade show active"
                        id="datos"
                    >

                        <form 
                            action="../../controllers/PerfilController.php"
                            method="POST"
                        >

                            <input
                                type="hidden"
                                name="accion"
                                value="actualizar_datos"
                            >

                            <!-- NOMBRE -->
                            <div class="mb-4">

                                <label class="form-label fw-bold">

                                    Nombre Completo

                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control"
                                    value="<?php
                                    echo htmlspecialchars(
                                        $user['nombre']
                                    );
                                    ?>"
                                    required
                                >

                            </div>

                            <!-- CORREO -->
                            <div class="mb-4">

                                <label class="form-label fw-bold">

                                    Correo Electrónico

                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    value="<?php
                                    echo htmlspecialchars(
                                        $user['correo']
                                    );
                                    ?>"
                                    disabled
                                >

                                <div class="form-text text-warning small mt-2">

                                    El correo no se puede cambiar por seguridad.

                                </div>

                            </div>

                            <!-- BOTON -->
                            <button
                                type="submit"
                                class="btn btn-primary profile-btn shadow"
                            >

                                <i class="bi bi-check-circle me-2"></i>

                                Actualizar Información

                            </button>

                        </form>

                    </div>

                    <!-- SEGURIDAD -->
                    <div 
                        class="tab-pane fade"
                        id="seguridad"
                    >

                        <h4 class="fw-bold mb-4">

                            <i class="bi bi-lock me-2"></i>

                            Cambiar Contraseña

                        </h4>

                        <form
                            action="../../controllers/PerfilController.php"
                            method="POST"
                            id="formPass"
                        >

                            <input
                                type="hidden"
                                name="accion"
                                value="cambiar_pass"
                            >

                            <!-- PASS ACTUAL -->
                            <div class="mb-3">

                                <label class="form-label">

                                    Contraseña Actual

                                </label>

                                <input
                                    type="password"
                                    name="pass_actual"
                                    class="form-control"
                                    required
                                >

                            </div>

                            <!-- NUEVA PASS -->
                            <div class="mb-3">

                                <label class="form-label">

                                    Nueva Contraseña

                                </label>

                                <input
                                    type="password"
                                    name="nueva_pass"
                                    id="nueva_pass"
                                    class="form-control"
                                    required
                                >

                            </div>

                            <!-- CONFIRMAR -->
                            <div class="mb-4">

                                <label class="form-label">

                                    Confirmar Nueva Contraseña

                                </label>

                                <input
                                    type="password"
                                    name="confirm_pass"
                                    class="form-control"
                                    required
                                >

                            </div>

                            <!-- BOTON -->
                            <button
                                type="submit"
                                class="btn btn-outline-danger profile-btn"
                            >

                                <i class="bi bi-shield-check me-2"></i>

                                Actualizar Contraseña

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
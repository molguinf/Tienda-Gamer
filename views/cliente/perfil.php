<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once '../../config/db.php';
require_once '../../config/auth.php';
// Aseguramos que solo usuarios logueados entren
requerirLogin(); 

include '../includes/header.php'; 

// Obtener datos frescos del usuario desde la BD
$stmt = $pdo->prepare("SELECT nombre, correo, rol, fecha_registro FROM usuario WHERE id_usuario = ?");
$stmt->execute([$_SESSION['id_usuario']]);
$user = $stmt->fetch();
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar de Perfil -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-circle display-1 text-primary"></i>
                </div>
                <h4 class="fw-bold"><?php echo htmlspecialchars($user['nombre']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($user['correo']); ?></p>
                <span class="badge bg-primary-subtle text-primary px-3 rounded-pill">
                    Rol: <?php echo strtoupper($user['rol']); ?>
                </span>
                <hr>
                <p class="small text-muted">Miembro desde: <?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></p>
            </div>
        </div>

        <!-- Formulario de Edición y Seguridad -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#datos">Mis Datos</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#seguridad">Seguridad</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab Datos Personales -->
                    <div class="tab-pane fade show active" id="datos">
                        <form action="../../controllers/PerfilController.php" method="POST">
                            <input type="hidden" name="accion" value="actualizar_datos">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['correo']); ?>" disabled>
                                <div class="form-text text-warning font-monospace small">El correo no se puede cambiar por seguridad.</div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">Actualizar Información</button>
                        </form>
                    </div>

                    <!-- Tab Seguridad -->
                    <div class="tab-pane fade" id="seguridad">
                        <h5 class="fw-bold mb-3">Cambiar Contraseña</h5>
                        <form action="../../controllers/PerfilController.php" method="POST" id="formPass">
                            <input type="hidden" name="accion" value="cambiar_pass">
                            <div class="mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" name="pass_actual" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="nueva_pass" id="nueva_pass" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="confirm_pass" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
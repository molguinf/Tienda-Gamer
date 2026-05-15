<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 

include '../includes/header.php'; 

// 1. Obtener todos los usuarios registrados
try {
    // Ordenamos por rol y luego por nombre para facilitar la lectura
    $sql = "SELECT id_usuario, nombre, correo, rol, fecha_registro FROM usuario ORDER BY rol ASC, nombre ASC";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener usuarios: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>Usuarios Registrados</h2>
            <p class="text-muted">Lista completa de clientes y administradores del sistema.</p>
        </div>
        <div class="badge bg-dark p-2 px-3 rounded-pill">
            Total: <?php echo count($usuarios); ?> usuarios
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $u['id_usuario']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <span class="fw-bold"><?php echo htmlspecialchars($u['nombre']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($u['correo']); ?></td>
                            <td>
                                <?php if ($u['rol'] === 'admin'): ?>
                                    <span class="badge bg-danger px-3 rounded-pill"><i class="bi bi-shield-lock me-1"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark px-3 rounded-pill">Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($u['fecha_registro'])); ?></td>
                            <td class="text-center">
                                <span class="text-success fw-bold small"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Activo</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
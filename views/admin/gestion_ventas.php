<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
// Seguridad: Solo administradores
requerirAdmin(); 

include '../includes/header.php'; 

// Obtener todas las ventas con los nombres de los usuarios
try {
    $sql = "SELECT v.*, u.nombre as cliente, u.correo 
            FROM Venta v 
            JOIN Usuario u ON v.id_usuario = u.id_usuario 
            ORDER BY v.fecha DESC";
    $stmt = $pdo->query($sql);
    $ventas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Gestión de Ventas</h2>
            <p class="text-muted">Administra los pedidos y actualiza los estados de envío.</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary shadow-sm" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Exportar Reporte
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?php echo $v['id_venta']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($v['cliente']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($v['correo']); ?></div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($v['fecha'])); ?></td>
                            <td class="fw-bold text-success"><?php echo number_format($v['total'], 2); ?> Bs.</td>
                            <td><span class="badge bg-light text-dark border"><?php echo $v['metodo_pago']; ?></span></td>
                            <td>
                                <form action="../../controllers/VentaController.php" method="POST" class="d-inline">
                                    <input type="hidden" name="accion" value="actualizar_estado">
                                    <input type="hidden" name="id_venta" value="<?php echo $v['id_venta']; ?>">
                                    <select name="nuevo_estado" class="form-select form-select-sm d-inline-block w-auto border-0 bg-light fw-bold" onchange="this.form.submit()">
                                        <option value="Pendiente" <?php echo ($v['estado'] == 'Pendiente') ? 'selected' : ''; ?>>🟡 Pendiente</option>
                                        <option value="Enviado" <?php echo ($v['estado'] == 'Enviado') ? 'selected' : ''; ?>>🔵 Enviado</option>
                                        <option value="Entregado" <?php echo ($v['estado'] == 'Entregado') ? 'selected' : ''; ?>>🟢 Entregado</option>
                                        <option value="Cancelado" <?php echo ($v['estado'] == 'Cancelado') ? 'selected' : ''; ?>>🔴 Cancelado</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="../cliente/detalle_pedido.php?id=<?php echo $v['id_venta']; ?>&admin=true" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirLogin();

include '../includes/header.php'; 

// Obtener el historial del usuario logueado
try {
    $stmt = $pdo->prepare("SELECT * FROM Venta WHERE id_usuario = ? ORDER BY fecha DESC");
    $stmt->execute([$_SESSION['id_usuario']]);
    $pedidos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar pedidos: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-bag-check me-2 text-primary"></i>Mis Pedidos</h2>
        <a href="../../index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Seguir Comprando</a>
    </div>

    <?php if(isset($_GET['msj']) && $_GET['msj'] == 'compra_exitosa'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><strong>¡Pedido realizado!</strong> Tu compra se ha registrado con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">ID Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pedidos) > 0): ?>
                        <?php foreach ($pedidos as $p): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?php echo $p['id_venta']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($p['fecha'])); ?></td>
                                <td class="fw-bold text-primary"><?php echo number_format($p['total'], 2); ?> Bs.</td>
                                <td><span class="badge bg-light text-dark border"><?php echo $p['metodo_pago']; ?></span></td>
                                <td>
                                    <?php 
                                        $color = ($p['estado'] == 'Pendiente') ? 'warning' : 'success';
                                        echo "<span class='badge bg-$color'>{$p['estado']}</span>";
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="detalle_pedido.php?id=<?php echo $p['id_venta']; ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                                        Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x display-4 d-block mb-2"></i>
                                Aún no has realizado ninguna compra.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
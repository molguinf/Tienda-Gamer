<?php 
require_once '../../config/auth.php';
requerirLogin();

// Si el carrito está vacío, no hay nada que pagar
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

include '../includes/header.php'; 

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Formulario de Envío y Pago -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h3 class="fw-bold mb-4"><i class="bi bi-truck me-2 text-primary"></i>Datos de Envío</h3>
                <form action="../../controllers/VentaController.php" method="POST">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Dirección de Entrega</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej. Av. América y Santa Cruz #123" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ciudad</label>
                            <input type="text" class="form-control" value="Cochabamba" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Teléfono de contacto</label>
                            <input type="text" name="telefono" class="form-control" placeholder="777XXXXX" required>
                        </div>
                    </div>

                    <h3 class="fw-bold my-4"><i class="bi bi-credit-card me-2 text-primary"></i>Método de Pago</h3>
                    <div class="list-group mb-4">
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="radio" name="metodo_pago" value="QR" checked>
                            <span>
                                <strong class="d-block text-gray-dark">Pago por QR (Simple)</strong>
                                <small>Se generará un código para tu banca móvil.</small>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="radio" name="metodo_pago" value="Transferencia">
                            <span>
                                <strong class="d-block text-gray-dark">Transferencia Bancaria</strong>
                                <small>Abono directo a cuenta BNB o BCP.</small>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        FINALIZAR PEDIDO <i class="bi bi-check-all ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Resumen Lateral -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h4 class="fw-bold mb-3">Resumen del Pedido</h4>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted"><?php echo $item['cantidad']; ?>x <?php echo htmlspecialchars($item['nombre']); ?></small>
                        <span class="fw-bold"><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?> Bs.</span>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h4 fw-bold">Total a pagar:</span>
                    <span class="h4 fw-bold text-primary"><?php echo number_format($total, 2); ?> Bs.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
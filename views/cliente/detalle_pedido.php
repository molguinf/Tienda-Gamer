<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirLogin();

if (!isset($_GET['id'])) { header("Location: mis_pedidos.php"); exit(); }

$id_venta = (int)$_GET['id'];

try {
    // 1. Obtener cabecera de la venta (validando que sea del usuario)
    $stmtVenta = $pdo->prepare("SELECT * FROM Venta WHERE id_venta = ? AND id_usuario = ?");
    $stmtVenta->execute([$id_venta, $_SESSION['id_usuario']]);
    $venta = $stmtVenta->fetch();

    if (!$venta) { header("Location: mis_pedidos.php"); exit(); }

    // 2. Obtener los productos del detalle con sus nombres
    $stmtDetalle = $pdo->prepare("SELECT d.*, p.nombre, p.imagen 
                                  FROM Detalle_Venta d 
                                  JOIN Producto p ON d.id_producto = p.id_producto 
                                  WHERE d.id_venta = ?");
    $stmtDetalle->execute([$id_venta]);
    $items = $stmtDetalle->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

include '../includes/header.php'; 
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="mis_pedidos.php">Mis Pedidos</a></li>
        <li class="breadcrumb-item active">Detalle #<?php echo $id_venta; ?></li>
      </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-4">Productos en este pedido</h4>
                <?php foreach ($items as $item): ?>
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <img src="../../assets/img/productos/<?php echo $item['imagen']; ?>" class="rounded me-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($item['nombre']); ?></h6>
                            <small class="text-muted">Cantidad: <?php echo $item['cantidad']; ?></small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-primary"><?php echo number_format($item['precio_unitario'] * $item['cantidad'], 2); ?> Bs.</span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="text-end pt-2">
                    <h5 class="fw-bold">Total Pagado: <span class="text-primary"><?php echo number_format($venta['total'], 2); ?> Bs.</span></h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h5 class="fw-bold mb-3">Información de Entrega</h5>
                <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-warning"><?php echo $venta['estado']; ?></span></p>
                <p class="mb-1"><strong>Método:</strong> <?php echo $venta['metodo_pago']; ?></p>
                <p class="mb-1"><strong>Dirección:</strong><br> <?php echo htmlspecialchars($venta['direccion']); ?></p>
                <p class="mb-0"><strong>Teléfono:</strong> <?php echo htmlspecialchars($venta['telefono']); ?></p>
                <hr>
                <div class="text-center">
                    <p class="small text-muted">Si tienes dudas sobre tu pedido, contáctanos indicando tu ID de venta.</p>
                    <button class="btn btn-outline-dark btn-sm w-100" onclick="window.print()"><i class="bi bi-printer me-2"></i>Imprimir Comprobante</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
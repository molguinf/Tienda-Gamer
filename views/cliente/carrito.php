<?php 
require_once '../../config/auth.php';
requerirLogin();

include '../includes/header.php'; 

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Tu Carrito de Compras</h2>

    <?php if (count($carrito) > 0): ?>
        <div class="row">
            <!-- Tabla de Productos -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrito as $id => $item): 
                                $subtotal = $item['precio'] * $item['cantidad'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="../../assets/img/productos/<?php echo $item['imagen']; ?>" 
                                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span class="fw-bold"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['precio'], 2); ?> Bs.</td>
                                        <td>

                                            <div class="d-flex align-items-center gap-2">

                                                <!-- RESTAR -->
                                                <a 
                                                    href="../../controllers/CarritoController.php?restar=<?php echo $id; ?>"
                                                    class="btn btn-sm btn-outline-light rounded-circle"
                                                >
                                                    <i class="bi bi-dash"></i>
                                                </a>

                                                <!-- CANTIDAD -->
                                                <span 
                                                    class="badge bg-primary px-3 py-2 fs-6"
                                                >
                                                    <?php echo $item['cantidad']; ?>
                                                </span>

                                                <!-- SUMAR -->
                                                <a 
                                                    href="../../controllers/CarritoController.php?sumar=<?php echo $id; ?>"
                                                    class="btn btn-sm btn-outline-light rounded-circle"
                                                >
                                                    <i class="bi bi-plus"></i>
                                                </a>

                                            </div>

                                        </td>
                                    <td class="fw-bold text-primary"><?php echo number_format($subtotal, 2); ?> Bs.</td>
                                    <td class="text-end pe-4">
                                        <a href="../../controllers/CarritoController.php?eliminar=<?php echo $id; ?>" class="text-danger">
                                            <i class="bi bi-trash fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="../../index.php" class="btn btn-outline-primary rounded-pill">
                        <i class="bi bi-arrow-left me-2"></i>Seguir Comprando
                    </a>
                    <a href="../../controllers/CarritoController.php?vaciar=1" class="btn btn-outline-danger rounded-pill">
                        <i class="bi bi-x-circle me-2"></i>Vaciar Carrito
                    </a>
                </div>
            </div>

            <!-- Resumen de Pago -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-dark text-white">
                    <h4 class="fw-bold mb-4">Resumen de Compra</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span><?php echo number_format($total, 2); ?> Bs.</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Envío</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold">Total</span>
                        <span class="h4 fw-bold text-primary"><?php echo number_format($total, 2); ?> Bs.</span>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        Proceder al Pago <i class="bi bi-credit-card ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3 text-muted">Tu carrito está vacío</h3>
            <a href="../../index.php" class="btn btn-primary mt-3 px-5 rounded-pill">Ir a la tienda</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
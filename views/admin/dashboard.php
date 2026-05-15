<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 

include '../includes/header.php'; 

// 1. Obtener estadísticas para las tarjetas
try {
    // Total de Ventas (Bs.)
    $totalVentas = $pdo->query("SELECT SUM(total) FROM Venta WHERE estado_venta != 'Cancelado'")->fetchColumn() ?: 0;

    // 2. Pedidos Pendientes (Corregido: estado_venta)
    $pedidosPendientes = $pdo->query("SELECT COUNT(*) FROM Venta WHERE estado_venta = 'Pendiente'")->fetchColumn() ?: 0;

    // 3. Cantidad de Usuarios (Corregido: Tabla Usuario con Mayúscula)
    $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM Usuario WHERE rol = 'cliente'")->fetchColumn();

    // 4. Productos con Stock Crítico
    $stockCriticoCount = $pdo->query("SELECT COUNT(*) FROM Producto WHERE stock <= 5")->fetchColumn();

    // 5. Últimas 5 ventas (Corregido: Tabla Usuario y columna id_usuario)
    $sqlVentas = "SELECT v.*, u.nombre 
                  FROM Venta v 
                  JOIN Usuario u ON v.id_usuario = u.id_usuario 
                  ORDER BY v.fecha DESC LIMIT 5";

    $ultimasVentas = $pdo->query($sqlVentas)->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar estadísticas: " . $e->getMessage());
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Panel de Control <span class="text-primary">Admin</span></h2>

    <!-- Fila de Tarjetas de Resumen -->
    <div class="row g-4 mb-5">
        <!-- Tarjeta Ingresos -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Ventas Totales</h6>
                        <h3 class="fw-bold mb-0"><?php echo number_format($totalVentas, 2); ?> Bs.</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Pedidos -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-dark bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Pendientes</h6>
                        <h3 class="fw-bold mb-0"><?php echo $pedidosPendientes; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Usuarios -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-info text-white h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Clientes</h6>
                        <h3 class="fw-bold mb-0"><?php echo $totalUsuarios; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Alerta Stock -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Stock Crítico</h6>
                        <h3 class="fw-bold mb-0"><?php echo $stockCriticoCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tabla de Últimas Ventas -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Últimas Transacciones</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Cliente</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ultimasVentas as $v): ?>
                                    <tr>
                                        <td class="ps-4">#<?php echo $v['id_venta']; ?></td>
                                        <td><?php echo htmlspecialchars($v['nombre']); ?></td>
                                        <td class="fw-bold"><?php echo number_format($v['total'], 2); ?> Bs.</td>
                                        <td>
                                            <span class="badge rounded-pill bg-<?php echo ($v['estado_venta'] == 'Pendiente') ? 'warning text-dark' : 'success'; ?>">
                                                <?php echo $v['estado_venta']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="gestion_ventas.php" class="small fw-bold text-decoration-none">Ver todos los pedidos →</a>
                </div>
            </div>
        </div>

        <!-- Acceso Rápido -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">Acciones Rápidas</h5>
                <div class="d-grid gap-3">
                    <a href="crear_producto.php" class="btn btn-outline-primary text-start p-3 rounded-3">
                        <i class="bi bi-plus-square me-2"></i> Nuevo Producto
                    </a>
                    <a href="usuarios.php" class="btn btn-outline-dark text-start p-3 rounded-3">
                        <i class="bi bi-person-gear me-2"></i> Gestionar Usuarios
                    </a>
                    <a href="inventario.php" class="btn btn-outline-info text-start p-3 rounded-3">
                        <i class="bi bi-box-seam me-2"></i> Ver Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
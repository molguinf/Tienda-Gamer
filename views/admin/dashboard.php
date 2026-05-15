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

<div class="container py-4 dashboard-container">
    <h2 class="fw-bold mb-4">Panel de Control <span class="text-primary">Admin</span></h2>

    <!-- Fila de Tarjetas de Resumen -->
    <!-- STATS -->
    <div class="row g-4 mb-4">

        <!-- VENTAS -->
        <div class="col-md-6 col-xl-3">

            <div class="dashboard-stat-card stat-primary">

                <div class="stat-icon">

                    <i class="bi bi-cash-coin"></i>

                </div>

                <div>

                    <div class="stat-label">

                        Ventas Totales

                    </div>

                    <div class="stat-value">

                        <?php echo number_format($totalVentas, 2); ?> Bs.

                    </div>

                </div>

            </div>

        </div>


        <!-- PENDIENTES -->
        <div class="col-md-6 col-xl-3">

            <div class="dashboard-stat-card stat-warning">

                <div class="stat-icon">

                    <i class="bi bi-clock-history"></i>

                </div>

                <div>

                    <div class="stat-label">

                        Pendientes

                    </div>

                    <div class="stat-value">

                        <?php echo $pedidosPendientes; ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- CLIENTES -->
        <div class="col-md-6 col-xl-3">

            <div class="dashboard-stat-card stat-info">

                <div class="stat-icon">

                    <i class="bi bi-people"></i>

                </div>

                <div>

                    <div class="stat-label">

                        Clientes

                    </div>

                    <div class="stat-value">

                        <?php echo $totalUsuarios; ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- STOCK -->
        <div class="col-md-6 col-xl-3">

            <div class="dashboard-stat-card stat-danger">

                <div class="stat-icon">

                    <i class="bi bi-exclamation-triangle"></i>

                </div>

                <div>

                    <div class="stat-label">

                        Stock Crítico

                    </div>

                    <div class="stat-value">

                        <?php echo $stockCriticoCount; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">
        <!-- Tabla de Últimas Ventas -->
        <div class="col-lg-8">
            <div class="card dashboard-card border-0 rounded-4">
                <div class="card-header border-0 py-3 bg-transparent">
                    <h5 class="fw-bold mb-0">Últimas Transacciones</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 dashboard-table">
                            <thead>
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
                <div class="card-footer border-0 text-center py-3 bg-transparent">
                    <a href="gestion_ventas.php" class="small fw-bold text-decoration-none">Ver todos los pedidos →</a>
                </div>
            </div>
        </div>

        <!-- Acceso Rápido -->
        <div class="col-lg-4">
            <div class="card dashboard-card border-0 rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">Acciones Rápidas</h5>
                <div class="d-grid gap-3">
                    <a href="crear_producto.php" class="quick-action-btn">
                        <i class="bi bi-plus-square me-2"></i> Nuevo Producto
                    </a>
                    <a href="usuarios.php" class="quick-action-btn">
                        <i class="bi bi-person-gear me-2"></i> Gestionar Usuarios
                    </a>
                    <a href="inventario.php" class="quick-action-btn">
                        <i class="bi bi-box-seam me-2"></i> Ver Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 

include '../includes/header.php'; 

// 1. Consultar datos enfocados en logística
try {
    // CORRECCIÓN: Tablas en Mayúsculas y nombre_categoria
    $sql = "SELECT p.*, c.nombre_categoria as categoria, (p.precio * p.stock) as valor_total
            FROM Producto p 
            INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
            ORDER BY p.stock ASC"; 
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll();

    // Resumen rápido
    $valorInventario = 0;
    foreach($productos as $prod) { 
        $valorInventario += $prod['valor_total']; 
    }
    
} catch (PDOException $e) {
    die("Error al cargar inventario: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Reporte de Inventario</h2>
            <p class="text-muted mb-0">Análisis de stock y valorización de mercadería en **Tienda Gamer**.</p>
        </div>
        <div class="text-end">
            <h5 class="text-muted mb-0 small">Valor Total en Almacén:</h5>
            <h3 class="fw-bold text-success"><?php echo number_format($valorInventario, 2); ?> Bs.</h3>
        </div>
    </div>

    <div class="alert alert-light border-0 shadow-sm rounded-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                Mostrando productos ordenados por **menor disponibilidad**.
            </div>
            <div class="col-md-4 text-end">
                <button onclick="window.print()" class="btn btn-dark btn-sm rounded-pill">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Descargar Reporte
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio Unit.</th>
                        <th class="pe-4 text-end">Valor en Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr class="<?php echo ($p['stock'] <= 5) ? 'table-danger-subtle' : ''; ?>">
                            <td class="ps-4 text-muted">PROD-<?php echo str_pad($p['id_producto'], 4, "0", STR_PAD_LEFT); ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($p['marca']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                            <td>
                                <?php if($p['stock'] <= 5): ?>
                                    <span class="badge bg-danger rounded-pill"><i class="bi bi-arrow-down-circle me-1"></i>Crítico: <?php echo $p['stock']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3"><?php echo $p['stock']; ?> unidades</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($p['precio'], 2); ?> Bs.</td>
                            <td class="pe-4 text-end fw-bold">
                                <?php echo number_format($p['valor_total'], 2); ?> Bs.
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .alert, header, footer, nav { display: none !important; }
        body { background: white !important; padding: 0 !important; }
        .container { max-width: 100% !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
    .table-danger-subtle { background-color: #fceaea !important; }
</style>

<?php include '../includes/footer.php'; ?>
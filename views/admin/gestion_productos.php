<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';

// Seguridad: Solo administradores
requerirAdmin(); 

include '../includes/header.php'; 

// 1. Consultar todos los productos con el nombre de su categoría
try {
    $sql = "SELECT p.*, c.nombre as categoria 
            FROM Producto p 
            INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
            ORDER BY p.id_producto DESC";
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="bi bi-box-seam me-2"></i>Inventario de Productos</h2>
            <p class="text-muted">Gestiona el stock, precios y visualización de la tienda.</p>
        </div>
        <a href="crear_producto.php" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
        </a>
    </div>

    <!-- Alertas de éxito post-acción -->
    <?php if(isset($_GET['msj']) && $_GET['msj'] == 'producto_creado'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> El producto ha sido registrado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Imagen</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($productos) > 0): ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td class="ps-4">
                                        <img src="../../assets/img/productos/<?php echo $p['imagen']; ?>" 
                                             alt="Producto" class="rounded border" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($p['nombre']); ?></div>
                                        <div class="text-muted small text-truncate" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($p['descripcion']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark opacity-75"><?php echo $p['categoria']; ?></span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        <?php echo number_format($p['precio'], 2); ?> Bs.
                                    </td>
                                    <td>
                                        <?php if($p['stock'] <= 5): ?>
                                            <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo $p['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="text-dark"><?php echo $p['stock']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <a href="editar_producto.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarEliminar(<?php echo $p['id_producto']; ?>)" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                    No hay productos registrados aún.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script para confirmación de eliminación -->
<script>
function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
        window.location.href = '../../controllers/ProductoController.php?action=delete&id=' + id;
    }
}
</script>

<?php include '../includes/footer.php'; ?>
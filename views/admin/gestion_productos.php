<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';

requerirAdmin(); 
include '../includes/header.php'; 

try {
    // Corregido: nombre_categoria (según tu SQL)
    $sql = "SELECT p.*, c.nombre_categoria as categoria 
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
            <h2 class="fw-bold text-dark"><i class="bi bi-tags me-2"></i>Inventario de Productos</h2>
            <p class="text-muted">Gestiona el stock y las múltiples imágenes de tus productos.</p>
        </div>
        <a href="crear_producto.php" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Imagen Principal</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $p): 
                            // Lógica de múltiples imágenes: tomamos la primera de la lista
                            $lista_imagenes = explode(',', $p['imagen']);
                            $foto_mostrar = !empty($lista_imagenes[0]) ? $lista_imagenes[0] : 'default.png';
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="../../assets/img/productos/<?php echo $foto_mostrar; ?>" 
                                         class="rounded border shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></div>
                                    <small class="text-muted"><?php echo count($lista_imagenes); ?> foto(s) disponible(s)</small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?php echo $p['categoria']; ?></span></td>
                                <td class="fw-bold text-primary"><?php echo number_format($p['precio'], 2); ?> Bs.</td>
                                <td><?php echo $p['stock']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="editar_producto.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                        <button onclick="confirmarEliminar(<?php echo $p['id_producto']; ?>)" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id) {
    if (confirm('¿Eliminar producto? Esto también borrará TODAS sus imágenes físicas del servidor.')) {
        window.location.href = '../../controllers/ProductoController.php?action=delete&id=' + id;
    }
}
</script>
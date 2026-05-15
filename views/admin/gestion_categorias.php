<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 
include '../includes/header.php'; 

$categorias = $pdo->query("SELECT * FROM Categoria ORDER BY id_categoria DESC")->fetchAll();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="bi bi-tags me-2"></i>Gestión de Categorías</h2>
            <p class="text-muted">Organiza los productos de la tienda por grupos.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="bi bi-plus-lg me-2"></i>Nueva Categoría
        </button>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre de Categoría</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td class="ps-4">#<?php echo $c['id_categoria']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($c['nombre_categoria']); ?></td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($c['descripcion']); ?></small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminarCat(<?php echo $c['id_categoria']; ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <form action="../../controllers/CategoriaController.php" method="POST" class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Añadir Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre_categoria" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_guardar" class="btn btn-primary w-100 fw-bold">Guardar Categoría</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmarEliminarCat(id) {
    if (confirm('¿Eliminar categoría? Los productos asociados podrían quedar sin categoría.')) {
        window.location.href = '../../controllers/CategoriaController.php?action=delete&id=' + id;
    }
}
</script>

<?php include '../includes/footer.php'; ?>
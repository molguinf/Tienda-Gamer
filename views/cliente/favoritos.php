<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirLogin();

include '../includes/header.php'; 

$fav_ids = $_SESSION['favoritos'] ?? [];
$productos = [];

if (!empty($fav_ids)) {
    $in  = str_repeat('?,', count($fav_ids) - 1) . '?';
    // Corregido: Tabla 'Producto' con P mayúscula
    $sql = "SELECT * FROM Producto WHERE id_producto IN ($in)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($fav_ids);
    $productos = $stmt->fetchAll();
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger me-2"></i>Mi Lista de Deseos</h2>

    <?php if (!empty($productos)): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($productos as $p): 
                // --- LÓGICA DE IMAGEN ---
                $fotos = explode(',', $p['imagen']);
                $foto_principal = !empty($fotos[0]) ? $fotos[0] : 'default_product.png';
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img src="../../assets/img/productos/<?php echo $foto_principal; ?>" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;"
                             alt="<?php echo htmlspecialchars($p['nombre']); ?>">
                             
                        <div class="card-body">
                            <h5 class="fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></h5>
                            <p class="text-primary fw-bold fs-5"><?php echo number_format($p['precio'], 2); ?> Bs.</p>
                            <div class="d-grid gap-2">
                                <a href="../../controllers/CarritoController.php?add=<?php echo $p['id_producto']; ?>" class="btn btn-primary rounded-pill">
                                    <i class="bi bi-cart-plus me-2"></i>Mover al Carrito
                                </a>
                                <a href="../../controllers/FavoritosController.php?remove=<?php echo $p['id_producto']; ?>" class="btn btn-outline-danger btn-sm border-0">
                                    <i class="bi bi-trash me-1"></i>Quitar de favoritos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted"></i>
            <p class="mt-3 fs-5 text-muted">Aún no tienes productos favoritos. ¡Explora la tienda!</p>
            <a href="../../index.php" class="btn btn-primary px-5 rounded-pill">Volver a la Tienda</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
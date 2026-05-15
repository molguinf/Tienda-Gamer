<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php'; 

// 1. Obtener y validar el ID de la categoría
$id_cat = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // 2. Obtener el nombre de la categoría para el título
    $stmtCat = $pdo->prepare("SELECT nombre_categoria FROM Categoria WHERE id_categoria = ?");
    $stmtCat->execute([$id_cat]);
    $categoria = $stmtCat->fetch();

    if (!$categoria) {
        header("Location: ../../index.php");
        exit;
    }

    // 3. Obtener los productos de ESTA categoría únicamente
    $stmtProd = $pdo->prepare("SELECT * FROM Producto WHERE id_categoria = ? ORDER BY id_producto DESC");
    $stmtProd->execute([$id_cat]);
    $productos = $stmtProd->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

include '../includes/header.php'; 
?>

<div class="container my-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                    <li class="breadcrumb-item active text-primary font-weight-bold">Categoría</li>
                </ol>
            </nav>
            <h1 class="section-title">
                <i class="bi bi-tag-fill text-primary"></i> 
                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
            </h1>
        </div>
        <a href="../../index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row g-4">
        <?php if (count($productos) > 0): ?>
            <?php foreach ($productos as $p): 
                // Lógica para la primera imagen (como en el index)
                $fotos = explode(',', $p['imagen']);
                $foto_portada = !empty($fotos[0]) ? $fotos[0] : 'default_product.png';
            ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="product-card position-relative h-100 shadow-sm">
                        <a href="detalle_producto.php?id=<?php echo $p['id_producto']; ?>" class="stretched-link"></a>
                        
                        <div class="product-image">
                            <img src="../../assets/img/productos/<?php echo $foto_portada; ?>" 
                                 class="img-fluid" 
                                 style="height: 220px; object-fit: cover; width: 100%;">
                        </div>

                        <div class="product-content p-3">
                            <h5 class="product-title mt-1 fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></h5>
                            <div class="product-price fw-bold text-primary fs-5 mb-3">
                                <?php echo number_format($p['precio'], 2); ?> Bs.
                            </div>
                            
                            <div class="product-buttons d-flex gap-2" style="position: relative; z-index: 3;">
                                <?php if($p['stock'] > 0): ?>
                                    <a href="carrito.php?add=<?php echo $p['id_producto']; ?>" class="btn btn-primary flex-grow-1">🛒 Agregar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3 display-1 text-muted"><i class="bi bi-search"></i></div>
                <h3>No hay productos en esta categoría</h3>
                <p class="text-muted">Pronto tendremos novedades para ti.</p>
                <a href="../../index.php" class="btn btn-primary mt-3">Ver otros productos</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
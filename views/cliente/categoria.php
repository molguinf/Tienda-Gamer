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
                        
                        <div class="product-image-wrapper">

                            <img
                                src="../../assets/img/productos/<?php echo $foto_portada; ?>"
                                alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                                class="product-image"
                            >

                        </div>

                        <div class="product-content">

                            <!-- TITULO -->
                            <h3 class="product-title">

                                <?php echo htmlspecialchars($p['nombre']); ?>

                            </h3>


                            <!-- PRECIO -->
                            <div class="product-price">

                                <?php echo number_format($p['precio'], 2); ?>

                                <span>Bs.</span>

                            </div>


                            <!-- BOTONES -->
                            <div class="product-actions">

                                <!-- ADMIN -->
                                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>

                                    <a
                                        href="../admin/editar_producto.php?id=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-edit"
                                    >

                                        ✏️ Editar

                                    </a>


                                    <a
                                        href="../../controllers/ProductoController.php?action=delete&id=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('¿Eliminar producto?')"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>

                                <!-- CLIENTE -->
                                <?php else: ?>

                                    <?php if($p['stock'] > 0): ?>

                                        <a
                                            href="../../controllers/CarritoController.php?add=<?php echo $p['id_producto']; ?>"
                                            class="btn btn-hero-primary w-100"
                                        >

                                            <i class="bi bi-cart-plus me-2"></i>

                                            Agregar

                                        </a>

                                    <?php else: ?>

                                        <button
                                            class="btn btn-secondary w-100 rounded-4"
                                            disabled
                                        >

                                            Sin Stock

                                        </button>

                                    <?php endif; ?>

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

<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';

try {

    $query = "SELECT * FROM Producto ORDER BY id_producto DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $productos = $stmt->fetchAll();

} catch (PDOException $e) {

    die("Error: " . $e->getMessage());

}

include '../includes/header.php';
?>

<!-- ========================================= -->
<!-- CATÁLOGO COMPLETO -->
<!-- ========================================= -->
<section class="container py-5 mt-5">

    <!-- TITULO -->
    <div class="text-center mb-5">

        <h1 class="section-title">
            Todos los Productos
        </h1>

        <p class="section-subtitle">
            Explora todo nuestro catálogo gamer premium.
        </p>

    </div>

    <!-- GRID PRODUCTOS -->
    <div class="row g-4">

        <?php if(count($productos) > 0): ?>

            <?php foreach($productos as $p):

                $fotos = explode(',', $p['imagen']);
                $foto_portada = !empty($fotos[0])
                    ? $fotos[0]
                    : 'default_product.png';

            ?>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                    <div class="product-card position-relative h-100 shadow-sm">

                        <?php if($p['stock'] <= 0): ?>
                            <div class="product-badge bg-secondary">
                                Agotado
                            </div>
                        <?php elseif($p['stock'] <= 5): ?>
                            <div class="product-badge">
                                Últimos <?php echo $p['stock']; ?>!
                            </div>
                        <?php endif; ?>

                        <!-- IMAGEN -->
                        <div class="product-image">

                            <img
                                src="../../assets/img/productos/<?php echo $foto_portada; ?>"
                                alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                                class="img-fluid"
                            >

                        </div>

                        <!-- CONTENIDO -->
                        <div class="product-content p-3">

                            <span class="product-brand small text-primary">
                                Gaming Gear
                            </span>

                            <h5 class="product-title mt-2 fw-bold text-white">
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </h5>

                            <p class="product-description small">
                                <?php echo htmlspecialchars($p['descripcion']); ?>
                            </p>

                            <div class="product-price fw-bold fs-5 mb-3 text-white">
                                <?php echo number_format($p['precio'], 2); ?> Bs.
                            </div>

                            <!-- BOTONES -->
                            <div class="d-flex gap-2">

                                <?php if($p['stock'] > 0): ?>

                                    <a
                                        href="../../controllers/CarritoController.php?add=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-primary flex-grow-1"
                                    >
                                        🛒 Agregar
                                    </a>

                                <?php else: ?>

                                    <button class="btn btn-secondary flex-grow-1" disabled>
                                        Sin Stock
                                    </button>

                                <?php endif; ?>

                                <a
                                    href="../../controllers/FavoritosController.php?add=<?php echo $p['id_producto']; ?>"
                                    class="btn btn-outline-danger"
                                >
                                    <i class="bi bi-heart<?php echo (isset($_SESSION['favoritos']) && in_array($p['id_producto'], $_SESSION['favoritos'])) ? '-fill' : ''; ?>"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12 text-center py-5">

                <p class="text-white">
                    No hay productos disponibles.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include '../includes/footer.php'; ?>


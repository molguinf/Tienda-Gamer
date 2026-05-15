
<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';

try {

    $por_pagina = 8;

    $pagina_actual = isset($_GET['pagina'])
        ? (int)$_GET['pagina']
        : 1;

    if($pagina_actual < 1){
        $pagina_actual = 1;
    }

    $inicio = ($pagina_actual - 1) * $por_pagina;


    /* TOTAL PRODUCTOS */
    $total_query = $pdo->query(
        "SELECT COUNT(*) FROM Producto"
    );

    $total_productos = $total_query->fetchColumn();

    $total_paginas = ceil(
        $total_productos / $por_pagina
    );


    /* QUERY PAGINADA */
    $query = "
        SELECT * 
        FROM Producto
        ORDER BY id_producto DESC
        LIMIT $inicio, $por_pagina
    ";

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

                        <a
                            href="detalle_producto.php?id=<?php echo $p['id_producto']; ?>"
                            class="stretched-link"
                        ></a>
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
                            <div
                                class="product-actions position-relative"
                                style="z-index: 5;"
                            >
                                <!-- ADMIN -->
                                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>

                                    <!-- EDITAR -->
                                    <a
                                        href="../admin/editar_producto.php?id=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-edit"
                                    >

                                        ✏️ Editar

                                    </a>


                                    <!-- ELIMINAR -->
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
                                            class="btn btn-hero-primary flex-grow-1"
                                        >

                                            <i class="bi bi-cart-plus me-2"></i>

                                            Agregar

                                        </a>

                                    <?php else: ?>

                                        <button
                                            class="btn btn-secondary flex-grow-1 rounded-4"
                                            disabled
                                        >

                                            Sin Stock

                                        </button>

                                    <?php endif; ?>


                                    <!-- FAVORITOS -->
                                    <a
                                        href="../../controllers/FavoritosController.php?add=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-favorite"
                                    >

                                        <i class="bi bi-heart<?php echo (isset($_SESSION['favoritos']) && in_array($p['id_producto'], $_SESSION['favoritos'])) ? '-fill' : ''; ?>"></i>

                                    </a>

                                <?php endif; ?>

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
            <!-- PAGINACION -->
<?php if($total_paginas > 1): ?>

    <div class="d-flex justify-content-center mt-5">

        <nav>

            <ul class="pagination custom-pagination">

                <!-- ANTERIOR -->
                <?php if($pagina_actual > 1): ?>

                    <li class="page-item">

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $pagina_actual - 1; ?>"
                        >

                            «

                        </a>

                    </li>

                <?php endif; ?>


                <!-- NUMEROS -->
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>

                    <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $i; ?>"
                        >

                            <?php echo $i; ?>

                        </a>

                    </li>

                <?php endfor; ?>


                <!-- SIGUIENTE -->
                <?php if($pagina_actual < $total_paginas): ?>

                    <li class="page-item">

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $pagina_actual + 1; ?>"
                        >

                            »

                        </a>

                    </li>

                <?php endif; ?>

            </ul>

        </nav>

    </div>

<?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>


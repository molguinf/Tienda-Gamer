
<?php 
require_once 'config/db.php';
require_once 'config/auth.php'; 

// ===============================
// FILTRO Y BÚSQUEDA
// ===============================
$categoria_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$busqueda = isset($_GET['s']) ? trim($_GET['s']) : '';

try {

    $query = "SELECT * FROM producto WHERE 1=1";
    $params = [];

    if ($categoria_id) {
        $query .= " AND id_categoria = ?";
        $params[] = $categoria_id;
    }

    if ($busqueda) {
        $query .= " AND nombre LIKE ?";
        $params[] = "%$busqueda%";
    }

    $query .= " ORDER BY id_producto DESC";

    $stmtProd = $pdo->prepare($query);
    $stmtProd->execute($params);

    $productos = $stmtProd->fetchAll();

    // Categorías
    $categorias = $pdo->query("SELECT * FROM categoria")->fetchAll();

} catch (PDOException $e) {

    die("Error: " . $e->getMessage());

}

include 'views/includes/header.php'; 
?>

<!-- ========================================= -->
<!-- HERO SECTION -->
<!-- ========================================= -->
<header class="hero-section">

    <div class="hero-overlay"></div>

    <div class="container hero-content">

        <div class="row align-items-center min-vh-100">

            <!-- TEXTO -->
            <div class="col-lg-6 text-center text-lg-start">

                <span class="hero-badge">
                    🔥 Tecnología Gamer Premium
                </span>

                <h1 class="hero-title mt-4">
                    LEVEL UP <br>
                    YOUR <span>GAMING</span> EXPERIENCE
                </h1>

                <p class="hero-description mt-4">
                    Descubre los mejores periféricos, laptops y accesorios gamer
                    con tecnología de última generación para dominar cada partida.
                </p>

                <div class="hero-buttons mt-5">

                    <a href="#productos" class="btn btn-hero-primary">
                        Explorar Catálogo
                    </a>

                    <a href="#categorias" class="btn btn-hero-secondary">
                        Ver Categorías
                    </a>

                </div>

                <!-- STATS -->
                <div class="hero-stats mt-5">

                    <div class="stat-box">
                        <h3>+500</h3>
                        <p>Productos</p>
                    </div>

                    <div class="stat-box">
                        <h3>+2K</h3>
                        <p>Clientes</p>
                    </div>

                    <div class="stat-box">
                        <h3>24/7</h3>
                        <p>Soporte</p>
                    </div>

                </div>

            </div>

            <!-- IMAGEN -->
            <div class="col-lg-6 text-center">

                <img 
                    src="assets/img/setup-gamer.png" 
                    alt="Setup Gamer"
                    class="hero-image img-fluid"
                >

            </div>

        </div>

    </div>

</header>

<!-- ========================================= -->
<!-- CATEGORÍAS -->
<!-- ========================================= -->
<section class="categories-section py-5" id="categorias">

    <div class="container">

        <!-- TITULO -->
        <div class="text-center mb-5">

            <h2 class="section-title">
                Nuestras Categorías
            </h2>

            <p class="section-subtitle">
                Explora nuestros productos gamer premium.
            </p>

        </div>

        <!-- GRID -->
        <div class="row g-4 justify-content-center">

            <?php foreach ($categorias as $cat): ?>

                <div class="col-6 col-md-4 col-lg-3">

                    <a 
                        href="views/cliente/categoria.php?id=<?php echo $cat['id_categoria']; ?>" 
                        class="text-decoration-none"
                    >

                        <div class="category-card">

                            <div class="category-icon">

                                <?php
                                $iconos = [
                                    'Laptops' => 'bi-laptop',
                                    'Monitores' => 'bi-display',
                                    'Periféricos' => 'bi-mouse',
                                    'Mouse' => 'bi-mouse',
                                    'Teclados' => 'bi-keyboard',
                                    'Consolas' => 'bi-controller',
                                    'Componentes' => 'bi-gpu-card'
                                ];

                                $icono = $iconos[$cat['nombre_categoria']] ?? 'bi-controller';
                                ?>

                                <i class="bi <?php echo $icono; ?>"></i>

                            </div>

                            <h5>
                                <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                            </h5>

                        </div>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>





<!-- SECCIÓN 3: PRODUCTOS DINÁMICOS -->
<!-- ========================================= -->
<!-- PRODUCTOS DESTACADOS -->
<!-- ========================================= -->
<section class="container my-5" id="productos">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-5">

        <div>

            <h2 class="section-title mb-2">
                🔥 Productos Destacados
            </h2>

            <p class="section-subtitle mb-0">
                Equipamiento premium para gamers profesionales.
            </p>

        </div>

        <a 
            href="views/cliente/catalogo_completo.php"
            class="btn btn-hero-secondary"
        >

            Ver Todos

        </a>

    </div>

    <!-- GRID -->
    <div class="row g-4">

        <?php if(count($productos) > 0): ?>

            <?php foreach($productos as $p):

                // MULTIPLES IMAGENES
                $fotos = explode(',', $p['imagen']);

                $foto_portada = !empty($fotos[0])

                    ? $fotos[0]

                    : 'default_product.png';

            ?>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                    <div class="product-card">

                        <!-- BADGES -->
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
                        <div class="product-image-wrapper">

                            <img
                                src="assets/img/productos/<?php echo $foto_portada; ?>"
                                alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                                class="product-image"
                            >

                        </div>


                        <!-- CONTENIDO -->
                        <div class="product-content">

                            <!-- CATEGORIA -->
                            <div class="product-category">

                                Gaming Gear

                            </div>


                            <!-- TITULO -->
                            <h3 class="product-title">

                                <?php
                                echo htmlspecialchars(
                                    $p['nombre']
                                );
                                ?>

                            </h3>


                            <!-- DESCRIPCION -->
                            <p class="product-description">

                                <?php
                                echo htmlspecialchars(
                                    $p['descripcion']
                                );
                                ?>

                            </p>


                            <!-- PRECIO -->
                            <div class="product-price">

                                <?php
                                echo number_format(
                                    $p['precio'],
                                    2
                                );
                                ?>

                                <span>Bs.</span>

                            </div>


                            <!-- BOTONES -->
                            <div class="product-actions">

                                <!-- ADMIN -->
                                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>

                                    <!-- EDITAR -->
                                    <a
                                        href="views/admin/editar_producto.php?id=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-edit"
                                    >

                                        ✏️ Editar

                                    </a>

                                    <!-- ELIMINAR -->
                                    <a
                                        href="controllers/ProductoController.php?action=delete&id=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-danger btn-delete"
                                        onclick="return confirm('¿Eliminar este producto?')"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>

                                <!-- CLIENTE -->
                                <?php else: ?>

                                    <?php if($p['stock'] > 0): ?>

                                        <a
                                            href="controllers/CarritoController.php?add=<?php echo $p['id_producto']; ?>"
                                            class="btn btn-edit"
                                        >

                                            🛒 Agregar

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
                                        href="controllers/FavoritosController.php?add=<?php echo $p['id_producto']; ?>"
                                        class="btn btn-outline-danger btn-delete"
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

                <p class="text-light">

                    No hay productos disponibles.

                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- ========================================= -->
<!-- BENEFICIOS -->
<!-- ========================================= -->
<section class="benefits-section py-5">

    <div class="container">

        <!-- TITULO -->
        <div class="text-center mb-5">

            <h2 class="section-title">
                ¿Por qué elegirnos?
            </h2>

            <p class="section-subtitle">
                Tecnología premium, soporte especializado y experiencia gamer real.
            </p>

        </div>

        <!-- GRID -->
        <div class="row g-4">

            <!-- BENEFICIO 1 -->
            <div class="col-md-4">

                <div class="benefit-card h-100">

                    <div class="benefit-icon">
                        🚚
                    </div>

                    <h4>
                        Envíos Nacionales
                    </h4>

                    <p>
                        Realizamos envíos rápidos y seguros a toda Bolivia.
                    </p>

                </div>

            </div>

            <!-- BENEFICIO 2 -->
            <div class="col-md-4">

                <div class="benefit-card h-100">

                    <div class="benefit-icon">
                        🛡️
                    </div>

                    <h4>
                        Garantía Oficial
                    </h4>

                    <p>
                        Todos nuestros productos cuentan con garantía real.
                    </p>

                </div>

            </div>

            <!-- BENEFICIO 3 -->
            <div class="col-md-4">

                <div class="benefit-card h-100">

                    <div class="benefit-icon">
                        💳
                    </div>

                    <h4>
                        Pagos Seguros
                    </h4>

                    <p>
                        Aceptamos QR, transferencias bancarias y efectivo.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<?php 
include 'views/includes/footer.php'; 
?>


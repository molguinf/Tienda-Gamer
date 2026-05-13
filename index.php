<?php 
require_once 'config/db.php';
require_once 'config/auth.php'; 

// 1. Lógica de Filtrado y Búsqueda
$categoria_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$busqueda = isset($_GET['s']) ? trim($_GET['s']) : '';

try {
    $query = "SELECT * FROM Producto WHERE 1=1";
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

    // Categorías para el menú
    $categorias = $pdo->query("SELECT * FROM Categoria")->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

include 'views/includes/header.php'; 
?>

<!-- SECCIÓN 1: HERO / BANNER PRINCIPAL (Tu diseño original) -->
<header class="bg-dark text-white py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/img/banner-bg.jpg') center/cover;">
    <div class="container py-5 text-center">
        <h1 class="display-3 fw-bold text-primary">TIENDA <span class="text-white">GAMER</span></h1>
        <p class="lead mb-4">Equipamiento de alto rendimiento para pro-players.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="#productos" class="btn btn-primary btn-lg px-4 gap-3 shadow">Explorar Catálogo</a>
            <a href="#categorias" class="btn btn-outline-light btn-lg px-4">Ver Categorías</a>
        </div>
    </div>
</header>

<!-- SECCIÓN 2: CATEGORÍAS DINÁMICAS -->
<section class="container my-5" id="categorias">
    <h2 class="text-center fw-bold mb-4 text-uppercase">Nuestras Categorías</h2>
    <div class="row text-center justify-content-center">
        <?php foreach ($categorias as $cat): ?>
            <div class="col-6 col-md-2 mb-3">
                <a href="views/cliente/categoria.php?id=<?php echo $cat['id_categoria']; ?>" class="text-decoration-none text-dark">
                    <div class="p-4 border rounded-4 shadow-sm bg-light hover-category">
                        <i class="bi bi-tag-fill fs-2 text-primary mb-2 d-block"></i>
                        <p class="mb-0 fw-bold"><?php echo htmlspecialchars($cat['nombre']); ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- SECCIÓN 3: PRODUCTOS DINÁMICOS -->
<section class="container my-5" id="productos">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="fw-bold m-0"><i class="bi bi-fire text-danger"></i> Productos Destacados</h2>
        <a href="views/cliente/catalogo_completo.php" class="text-primary text-decoration-none fw-bold">Ver todos →</a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
        <?php if (count($productos) > 0): ?>
            <?php foreach ($productos as $p): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 card-gamer">
                        <!-- Etiquetas de Stock -->
                        <?php if($p['stock'] <= 0): ?>
                            <div class="badge bg-dark position-absolute" style="top: 0.5rem; right: 0.5rem">Agotado</div>
                        <?php elseif($p['stock'] <= 5): ?>
                            <div class="badge bg-danger position-absolute animate-pulse" style="top: 0.5rem; right: 0.5rem">¡Últimos <?php echo $p['stock']; ?>!</div>
                        <?php endif; ?>

                        <!-- Imagen -->
                        <div class="overflow-hidden rounded-top-4">
                            <img src="assets/img/productos/<?php echo $p['imagen']; ?>" 
                                 class="card-img-top img-product" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($p['nombre']); ?></h5>
                            <p class="card-text text-muted small text-truncate"><?php echo htmlspecialchars($p['descripcion']); ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="h4 fw-bold text-primary mb-0"><?php echo number_format($p['precio'], 2); ?> <small class="fs-6">Bs.</small></span>
                                
                                <?php if($p['stock'] > 0): ?>
                                    <a href="views/cliente/carrito.php?add=<?php echo $p['id_producto']; ?>" class="btn btn-primary rounded-circle shadow-sm">
                                        <i class="bi bi-cart-plus"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary rounded-circle" disabled><i class="bi bi-slash-circle"></i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pb-3 px-3">
                            <!-- Cambia el botón de Favorito por este link -->
<a href="controllers/FavoritosController.php?add=<?php echo $p['id_producto']; ?>" 
   class="btn btn-outline-danger btn-sm w-100 rounded-pill">
   <i class="bi bi-heart<?php echo (isset($_SESSION['favoritos']) && in_array($p['id_producto'], $_SESSION['favoritos'])) ? '-fill' : ''; ?> me-1"></i> Favorito
</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aún no hay productos disponibles. Vuelve pronto.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- SECCIÓN 4: BENEFICIOS (Tu diseño original) -->
<div class="bg-light py-5 border-top">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-4">
                <i class="bi bi-truck fs-1 text-primary mb-3"></i>
                <h4 class="fw-bold">Envío a todo el país</h4>
                <p class="text-muted">Cochabamba, La Paz, Santa Cruz y más ciudades.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-shield-check fs-1 text-primary mb-3"></i>
                <h4 class="fw-bold">Garantía Real</h4>
                <p class="text-muted">12 meses de garantía oficial en todos los equipos.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-wallet2 fs-1 text-primary mb-3"></i>
                <h4 class="fw-bold">Pagos Seguros</h4>
                <p class="text-muted">Aceptamos QR, Transferencias bancarias y Efectivo.</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos adicionales para que se vea Pro */
    .card-gamer { transition: all 0.3s ease; }
    .card-gamer:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important; }
    .img-product { height: 200px; object-fit: cover; transition: transform 0.5s ease; }
    .card-gamer:hover .img-product { transform: scale(1.1); }
    .hover-category { transition: all 0.3s ease; border: 2px solid transparent !important; }
    .hover-category:hover { border-color: #0d6efd !important; background-color: #fff !important; transform: scale(1.05); }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
</style>

<?php 
include 'views/includes/footer.php'; 
?>
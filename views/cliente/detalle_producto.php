<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
include '../includes/header.php'; 

// 1. Validar ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Consultar Producto
$stmt = $pdo->prepare("SELECT p.*, c.nombre_categoria FROM Producto p 
                       INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                       WHERE p.id_producto = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    echo "<div class='container my-5 alert alert-danger'>Producto no encontrado.</div>";
    include '../includes/footer.php';
    exit;
}

// 3. Procesar todas las imágenes
$fotos = explode(',', $p['imagen']);
?>

<div class="container my-5 pt-5">
    <a href="../../index.php" class="btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left"></i> Volver a la Tienda
    </a>

    <div class="row g-5">
        <div class="col-md-6">
            <div class="main-image mb-3">
                <img id="viewedImage" src="../../assets/img/productos/<?php echo $fotos[0]; ?>" 
                     class="img-fluid rounded-4 shadow" style="width: 100%; height: 500px; object-fit: contain; background: #f8f9fa;">
            </div>
            
            <?php if(count($fotos) > 1): ?>
                <div class="row g-2">
                    <?php foreach($fotos as $f): ?>
                        <div class="col-3">
                            <img src="../../assets/img/productos/<?php echo $f; ?>" 
                                 class="img-thumbnail thumbnail-btn" style="cursor:pointer; height: 80px; width: 100%; object-fit: cover;"
                                 onclick="document.getElementById('viewedImage').src=this.src">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <span class="badge bg-primary-subtle text-primary mb-2"><?php echo $p['nombre_categoria']; ?></span>
            <h1 class="fw-bold display-5 mb-3"><?php echo htmlspecialchars($p['nombre']); ?></h1>
            <p class="text-muted small">Marca: <span class="text-dark fw-bold"><?php echo htmlspecialchars($p['marca']); ?></span></p>
            
            <h2 class="text-primary fw-bold mb-4"><?php echo number_format($p['precio'], 2); ?> Bs.</h2>
            
            <div class="mb-4">
                <h5 class="fw-bold">Descripción:</h5>
                <p class="text-secondary" style="white-space: pre-line;">
                    <?php echo htmlspecialchars($p['descripcion']); ?>
                </p>
            </div>

            <div class="stock-info mb-4">
                <?php if($p['stock'] > 0): ?>
                    <p class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Stock Disponible (<?php echo $p['stock']; ?> unidades)</p>
                <?php else: ?>
                    <p class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Agotado temporalmente</p>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-3 mt-5 flex-wrap">

    <!-- ADMIN -->
    <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>

        <!-- EDITAR -->
        <a 
            href="../admin/editar_producto.php?id=<?php echo $p['id_producto']; ?>" 
            class="btn btn-primary btn-lg px-5 shadow flex-grow-1"
        >

            <i class="bi bi-pencil-square me-2"></i>
            Editar Producto

        </a>

        <!-- ELIMINAR -->
        <a 
            href="../../controllers/ProductoController.php?action=delete&id=<?php echo $p['id_producto']; ?>"
            class="btn btn-danger btn-lg shadow"
            onclick="return confirm('¿Eliminar producto?')"
        >

            <i class="bi bi-trash"></i>

        </a>

    <!-- CLIENTE -->
    <?php else: ?>

        <?php if($p['stock'] > 0): ?>

            <a 
                href="../../controllers/CarritoController.php?add=<?php echo $p['id_producto']; ?>" 
                class="btn btn-primary btn-lg px-5 flex-grow-1 shadow"
            >

                <i class="bi bi-cart-plus me-2"></i>
                Añadir al Carrito

            </a>

        <?php endif; ?>

        <!-- FAVORITOS -->
        <a 
            href="../../controllers/FavoritosController.php?add=<?php echo $p['id_producto']; ?>" 
            class="btn btn-outline-danger btn-lg"
        >

            <i class="bi bi-heart<?php echo (isset($_SESSION['favoritos']) && in_array($p['id_producto'], $_SESSION['favoritos'])) ? '-fill' : ''; ?>"></i>

        </a>

    <?php endif; ?>

</div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
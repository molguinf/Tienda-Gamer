<?php 
// Incluimos la configuración de sesión y seguridad
require_once 'config/auth.php'; 

// "Pegamos" la parte de arriba
include 'views/includes/header.php'; 
?>

<!-- SECCIÓN 1: HERO / BANNER PRINCIPAL -->
<header class="bg-dark text-white py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/img/banner-bg.jpg') center/cover;">
    <div class="container py-5 text-center">
        <h1 class="display-3 fw-bold text-primary">TIENDA <span class="text-white">GAMER</span></h1>
        <p class="lead mb-4">Equipamiento de alto rendimiento para pro-players.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="#productos" class="btn btn-primary btn-lg px-4 gap-3">Explorar Catálogo</a>
            <a href="#categorias" class="btn btn-outline-light btn-lg px-4">Ver Categorías</a>
        </div>
    </div>
</header>

<!-- SECCIÓN 2: CATEGORÍAS (Para el Estudiante 2) -->
<section class="container my-5" id="categorias">
    <h2 class="text-center fw-bold mb-4">Nuestras Categorías</h2>
    <div class="row text-center">
        <div class="col-6 col-md-2 mb-3">
            <div class="p-3 border rounded shadow-sm bg-light">
                <i class="bi bi-laptop fs-1"></i>
                <p class="mb-0">Laptops</p>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-3">
            <div class="p-3 border rounded shadow-sm bg-light">
                <p class="mb-0">Monitores</p>
            </div>
        </div>
        <!-- Agregar más categorías según tu tabla de la DB -->
    </div>
</section>

<!-- SECCIÓN 3: PRODUCTOS DESTACADOS (Para el Estudiante 3) -->
<section class="container my-5" id="productos">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Productos Destacados</h2>
        <a href="#" class="text-primary text-decoration-none">Ver todos →</a>
    </div>

    <div class="row">
        <!-- Ejemplo de un Producto (Esto luego será un bucle PHP) -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="badge bg-danger position-absolute" style="top: 0.5rem; right: 0.5rem">Oferta</div>
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Laptop Gamer">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Razer DeathAdder V3</h5>
                    <p class="card-text text-muted small">Mouse ergonómico ultra ligero para eSports.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 fw-bold text-primary mb-0">$69.99</span>
                        <a href="#" class="btn btn-sm btn-dark text-white"><i class="bi bi-cart-plus"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3">
                    <button class="btn btn-outline-danger btn-sm w-100">❤️ Favorito</button>
                </div>
            </div>
        </div>
        <!-- Fin de ejemplo -->
    </div>
</section>

<!-- SECCIÓN 4: BENEFICIOS -->
<div class="bg-light py-5 border-top">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-4">
                <h4 class="fw-bold">Envío a todo el país</h4>
                <p>Cochabamba, La Paz, Santa Cruz y más.</p>
            </div>
            <div class="col-md-4">
                <h4 class="fw-bold">Garantía Real</h4>
                <p>12 meses de garantía en todos los equipos.</p>
            </div>
            <div class="col-md-4">
                <h4 class="fw-bold">Pagos Seguros</h4>
                <p>Aceptamos QR, Transferencias y Efectivo.</p>
            </div>
        </div>
    </div>
</div>

<?php 
// "Pegamos" la parte de abajo
include 'views/includes/footer.php'; 
?>
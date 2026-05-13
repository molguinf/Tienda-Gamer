<?php 
// Incluimos la configuración de sesión y seguridad
require_once 'config/auth.php'; 

// "Pegamos" la parte de arriba
include 'views/includes/header.php'; 
?>

<!-- SECCIÓN 1: HERO / BANNER PRINCIPAL -->

<!-- HERO SECTION -->
<header class="hero-section">

    <div class="hero-overlay"></div>

    <div class="container hero-content">

        <div class="row align-items-center min-vh-100">

            <!-- TE
XTO -->
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




<!-- SECCIÓN 2: CATEGORÍAS -->
<section class="categories-section py-5" id="categorias">

    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">Explora Categorías</h2>
            <p class="section-subtitle">
                Encuentra el setup perfecto para tu experiencia gamer.
            </p>
        </div>

        <div class="row g-4">

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-laptop category-icon"></i>

                    <h5>Laptops</h5>

                </div>

            </div>

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-display category-icon"></i>

                    <h5>Monitores</h5>

                </div>

            </div>

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-mouse category-icon"></i>

                    <h5>Mouse</h5>

                </div>

            </div>

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-keyboard category-icon"></i>

                    <h5>Teclados</h5>

                </div>

            </div>

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-controller category-icon"></i>

                    <h5>Consolas</h5>

                </div>

            </div>

            <!-- CATEGORIA -->
            <div class="col-6 col-md-4 col-lg-2">

                <div class="category-card">

                    <i class="bi bi-gpu-card category-icon"></i>

                    <h5>Componentes</h5>

                </div>

            </div>

        </div>

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
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="product-card">
                <div class="product-badge">
                    Oferta
                </div>
                <div class="product-image">
                    <img 
                        src="https://via.placeholder.com/300x250"
                        alt="Producto Gamer"
                        class="img-fluid"
                    >
                </div>
                <div class="product-content">
                    <span class="product-brand">
                        Razer
                    </span>
                    <h5 class="product-title">
                        DeathAdder V3 Pro
                    </h5>
                    <p class="product-description">
                        Mouse gamer ultraligero diseñado para eSports profesionales.
                    </p>
                    <div class="product-price">
                        $69.99
                    </div>
                    <div class="product-buttons">
                        <button class="btn-cart">
                            🛒 Agregar
                        </button>
                        <button class="btn-favorite">
                            ❤️
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin de ejemplo -->
    </div>
</section>

<!-- SECCIÓN 4: BENEFICIOS -->
<!-- BENEFICIOS -->
<section class="benefits-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">
                ¿Por qué elegirnos?
            </h2>
            <p class="section-subtitle">
                Tecnología premium, atención especializada y experiencia gamer real.
            </p>
        </div>
        <div class="row g-4">

            <!-- BENEFICIO -->
            <div class="col-md-4">
                <div class="benefit-card">
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
            <!-- BENEFICIO -->
            <div class="col-md-4">
                <div class="benefit-card">
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
            <!-- BENEFICIO -->
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        ⚡
                    </div>
                    <h4>
                        Rendimiento Gamer
                    </h4>
                    <p>
                        Equipamiento de alto rendimiento para gamers exigentes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>



<?php 
// "Pegamos" la parte de abajo
include 'views/includes/footer.php'; 
?>
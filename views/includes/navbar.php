```php
<nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand logo-text" href="../../index.php">
            🎮 TiendaGamer
        </a>

        <!-- BOTON RESPONSIVE -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- LINKS -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Productos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Categorías</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Ofertas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>

            </ul>

            <!-- BOTONES DERECHA -->
            <div class="d-flex gap-3">

                <a href="../auth/login.php" class="btn btn-login">
                    Iniciar Sesión
                </a>

                <a href="../cliente/carrito.php" class="btn cart-btn">
                    🛒
                </a>

            </div>

        </div>
    </div>
</nav>
```

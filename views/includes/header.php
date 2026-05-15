
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// VERIFICAR SI ES ADMIN
$esAdmin = (
    isset($_SESSION['rol']) &&
    $_SESSION['rol'] === 'admin'
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tienda Gamer | Expertos en Hardware</title>

    <!-- BOOTSTRAP -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <!-- ICONOS -->
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
    >

    <!-- FUENTE -->
    <link 
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" 
        rel="stylesheet"
    >

    <!-- CSS -->
    <link 
        rel="stylesheet" 
        href="/Tienda-Gamer/assets/css/style.css"
    >

    <style>

        body{
            font-family: 'Poppins', sans-serif;
            padding-top: 120px;
        }

        .navbar-brand{
            letter-spacing: 1px;
        }

        .nav-link:hover{
            color: #0d6efd !important;
        }

    </style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">

    <div class="container">

        <!-- LOGO -->
        <a 
            class="navbar-brand fw-bold"
            href="/Tienda-Gamer/index.php"
        >
            <span class="text-primary">
                🎮 TIENDA
            </span>
            GAMER
        </a>

        <!-- BOTON MOBILE -->
        <button 
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMain"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navMain">

            <!-- LINKS -->
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a 
                        class="nav-link"
                        href="/Tienda-Gamer/index.php#productos"
                    >
                        Productos
                    </a>
                </li>

                <li class="nav-item">
                    <a 
                        class="nav-link"
                        href="/Tienda-Gamer/index.php#categorias"
                    >
                        Categorías
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Inicio
                    </a>
                </li>

            </ul>

            <!-- DERECHA -->
            <div class="d-flex align-items-center">

                <!-- SOLO CLIENTE -->
                <?php if(!$esAdmin): ?>

                    <!-- FAVORITOS -->
                    <a 
                        href="/Tienda-Gamer/views/cliente/favoritos.php"
                        class="text-white me-3 position-relative text-decoration-none"
                    >

                        <i class="bi bi-heart fs-5"></i>

                        <?php if(!empty($_SESSION['favoritos'])): ?>

                            <span 
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.6rem;"
                            >

                                <?php echo count($_SESSION['favoritos']); ?>

                            </span>

                        <?php endif; ?>

                    </a>

                    <!-- CARRITO -->
                    <a 
                        href="/Tienda-Gamer/views/cliente/carrito.php"
                        class="text-white me-4 position-relative text-decoration-none"
                    >

                        <i class="bi bi-cart3 fs-5"></i>

                        <?php if(!empty($_SESSION['carrito'])): ?>

                            <span 
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
                                style="font-size: 0.6rem;"
                            >

                                <?php
                                echo array_sum(
                                    array_column(
                                        $_SESSION['carrito'],
                                        'cantidad'
                                    )
                                );
                                ?>

                            </span>

                        <?php endif; ?>

                    </a>

                <?php endif; ?>

                <!-- SI NO LOGUEADO -->
                <?php if(!isset($_SESSION['id_usuario'])): ?>

                    <a 
                        href="/Tienda-Gamer/views/auth/login.php"
                        class="btn btn-outline-primary btn-sm me-2"
                    >
                        Login
                    </a>

                    <a 
                        href="/Tienda-Gamer/views/auth/registro.php"
                        class="btn btn-primary btn-sm"
                    >
                        Registro
                    </a>

                <?php else: ?>

                    <!-- DROPDOWN -->
                    <div class="dropdown">

                        <button 
                            class="btn btn-primary btn-sm dropdown-toggle d-flex align-items-center"
                            type="button"
                            data-bs-toggle="dropdown"
                        >

                            <i class="bi bi-person-circle me-2"></i>

                            Hola,
                            <?php echo htmlspecialchars($_SESSION['nombre']); ?>

                        </button>

                        <!-- MENU -->
                        <ul class="dropdown-menu dropdown-menu-end shadow">

                            <li>

                                <h6 class="dropdown-header">

                                    Cuenta
                                    (
                                    <?php echo strtoupper($_SESSION['rol']); ?>
                                    )

                                </h6>

                            </li>

                            <!-- PERFIL -->
                            <li>

                                <a 
                                    class="dropdown-item"
                                    href="/Tienda-Gamer/views/cliente/perfil.php"
                                >

                                    <i class="bi bi-person me-2"></i>
                                    Ver Perfil

                                </a>

                            </li>

                            <!-- CLIENTE -->
                            <?php if(!$esAdmin): ?>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/cliente/mis_pedidos.php"
                                    >

                                        <i class="bi bi-bag-check me-2"></i>
                                        Mis Compras

                                    </a>

                                </li>

                            <?php endif; ?>

                            <!-- ADMIN -->
                            <?php if($esAdmin): ?>

                                <li><hr class="dropdown-divider"></li>

                                <li>

                                    <h6 class="dropdown-header text-primary">

                                        Administración

                                    </h6>

                                </li>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/admin/dashboard.php"
                                    >

                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Dashboard

                                    </a>

                                </li>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/admin/usuarios.php"
                                    >

                                        <i class="bi bi-people me-2"></i>
                                        Usuarios

                                    </a>

                                </li>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/admin/gestion_productos.php"
                                    >

                                        <i class="bi bi-box-seam me-2"></i>
                                        Productos

                                    </a>

                                </li>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/admin/gestion_ventas.php"
                                    >

                                        <i class="bi bi-receipt me-2"></i>
                                        Ventas

                                    </a>

                                <li>

                                    <a 
                                        class="dropdown-item"
                                        href="/Tienda-Gamer/views/admin/gestion_categorias.php"
                                    >

                                        <i class="bi bi-tags me-2"></i>

                                        Gestión Categorías

                                    </a>

                                </li>

                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>

                            <!-- LOGOUT -->
                            <li>

                                <a 
                                    class="dropdown-item text-danger"
                                    href="/Tienda-Gamer/controllers/LogoutController.php"
                                >

                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Cerrar Sesión

                                </a>

                            </li>

                        </ul>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</nav>


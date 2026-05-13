<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Gamer | Expertos en Hardware</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/Tienda-Gamer/assets/css/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .navbar-brand { letter-spacing: 1px; }
        .nav-link:hover { color: #0d6efd !important; }
    </style>
</head>
<body>



<nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/TiendaGamer/index.php">
            <span class="text-primary">🎮 TIENDA</span>GAMER
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/TiendaGamer/index.php#productos">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="/TiendaGamer/index.php#categorias">Categorías</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Ofertas</a></li>
            </ul>
            
            <div class="d-flex align-items-center">
    <!-- Icono de Favoritos con Contador -->
    <a href="/TiendaGamer/views/cliente/favoritos.php" class="text-white me-3 position-relative text-decoration-none">
        <i class="bi bi-heart fs-5"></i>
        <?php if (!empty($_SESSION['favoritos'])): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                <?php echo count($_SESSION['favoritos']); ?>
            </span>
        <?php endif; ?>
    </a>

    <!-- Icono de Carrito con Contador (puedes añadir la lógica del carrito aquí también) -->
    <a href="/TiendaGamer/views/cliente/carrito.php" class="text-white me-4 position-relative text-decoration-none">
        <i class="bi bi-cart3 fs-5"></i>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.6rem;">
                <?php echo count($_SESSION['carrito']); ?>
            </span>
        <?php endif; ?>
    </a>

    <!-- Lógica de Usuario: ¿Está logueado? -->
    <?php if(!isset($_SESSION['id_usuario'])): ?>
        <a href="/TiendaGamer/views/auth/login.php" class="btn btn-outline-primary btn-sm me-2">Login</a>
        <a href="/TiendaGamer/views/auth/registro.php" class="btn btn-primary btn-sm">Registro</a>
    <?php else: ?>
        <div class="dropdown">
            <button class="btn btn-primary btn-sm dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>
                <span>Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><h6 class="dropdown-header">Mi Cuenta (<?php echo strtoupper($_SESSION['rol']); ?>)</h6></li>
    
             <!-- OPCIONES PARA TODOS LOS LOGUEADOS -->
                <li><a class="dropdown-item" href="/TiendaGamer/views/cliente/perfil.php">
                    <i class="bi bi-person me-2"></i>Ver Perfil</a>
                </li>
                <li><a class="dropdown-item" href="/TiendaGamer/views/cliente/mis_pedidos.php">
                    <i class="bi bi-bag-check me-2"></i>Mis Compras</a>
                </li>

                <?php if($_SESSION['rol'] === 'admin'): ?>
                    <!-- SECCIÓN EXCLUSIVA PARA ADMINISTRADORES -->
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header text-primary">Gestión del Sistema</h6></li>
        
                    <li><a class="dropdown-item" href="/TiendaGamer/views/admin/dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard General</a>
                    </li>
                    <li><a class="dropdown-item" href="/TiendaGamer/views/admin/usuarios.php">
                        <i class="bi bi-people me-2"></i>Control de Usuarios</a>
                    </li>
                    <li><a class="dropdown-item" href="/TiendaGamer/views/admin/inventario.php">
                        <i class="bi bi-box-seam me-2"></i>Reporte de Inventario</a>
                    </li>
                    <li><a class="dropdown-item" href="/TiendaGamer/views/admin/gestion_productos.php">
            <i class="bi bi-tags me-2"></i>Editar Productos</a>
        </li>
    <?php endif; ?>

    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="/TiendaGamer/controllers/LogoutController.php">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a>
    </li>
</ul>
        </div>
    <?php endif; ?>
</div>
        </div>
    </div>
</nav>
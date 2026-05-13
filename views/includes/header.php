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
                <!-- Iconos de Carrito y Favoritos -->
                <a href="/TiendaGamer/views/cliente/favoritos.php" class="text-white me-3 position-relative">
                    <i class="bi bi-heart fs-5"></i>
                </a>
                <a href="/TiendaGamer/views/cliente/carrito.php" class="text-white me-3 position-relative">
                    <i class="bi bi-cart3 fs-5"></i>
                </a>
                
                <!-- Botón Login / Registro -->
                <?php if(!isset($_SESSION['id_usuario'])): ?>
                    <a href="/TiendaGamer/views/auth/login.php" class="btn btn-outline-primary btn-sm me-2">Login</a>
                    <a href="/TiendaGamer/views/auth/registro.php" class="btn btn-primary btn-sm">Registro</a>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> Mi Cuenta
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if($_SESSION['rol'] == 'admin'): ?>
                                <li><a class="dropdown-item" href="/TiendaGamer/views/admin/dashboard.php">Panel Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/TiendaGamer/views/cliente/perfil.php">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/TiendaGamer/controllers/LogoutController.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
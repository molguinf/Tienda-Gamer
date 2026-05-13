<?php
require_once '../config/db.php';
require_once '../config/auth.php';
// El carrito debe funcionar para cualquier usuario logueado
requerirLogin();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// 1. ACCIÓN: AGREGAR AL CARRITO
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    // Buscar el producto en la DB para validar stock y precio
    $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, imagen, stock FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if ($producto && $producto['stock'] > 0) {
        // Si el producto ya está en el carrito, aumentamos la cantidad
        if (isset($_SESSION['carrito'][$id])) {
            // Validar que no exceda el stock disponible
            if ($_SESSION['carrito'][$id]['cantidad'] < $producto['stock']) {
                $_SESSION['carrito'][$id]['cantidad']++;
            }
        } else {
            // Si es nuevo, lo añadimos al array
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'cantidad' => 1
            ];
        }
        header("Location: ../views/cliente/carrito.php?msj=agregado");
    } else {
        header("Location: ../index.php?error=sin_stock");
    }
    exit();
}

// 2. ACCIÓN: ELIMINAR UN PRODUCTO
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: ../views/cliente/carrito.php?msj=eliminado");
    exit();
}

// 3. ACCIÓN: VACIAR TODO EL CARRITO
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: ../views/cliente/carrito.php");
    exit();
}
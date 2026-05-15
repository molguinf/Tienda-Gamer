<?php
require_once '../config/db.php';
require_once '../config/auth.php';
requerirLogin();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// 1. ACCIÓN: AGREGAR AL CARRITO
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    // CORRECCIÓN: Tabla 'Producto' con P mayúscula según tu SQL
    $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, imagen, stock FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if ($producto && $producto['stock'] > 0) {
        // Lógica Multimagen: Tomamos solo la primera para mostrarla en el carrito
        $fotos = explode(',', $producto['imagen']);
        $foto_principal = !empty($fotos[0]) ? $fotos[0] : 'default.png';

        if (isset($_SESSION['carrito'][$id])) {
            if ($_SESSION['carrito'][$id]['cantidad'] < $producto['stock']) {
                $_SESSION['carrito'][$id]['cantidad']++;
            }
        } else {
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $foto_principal, // Guardamos solo una imagen
                'cantidad' => 1
            ];
        }
        header("Location: ../views/cliente/carrito.php?msj=agregado");
    } else {
        header("Location: ../index.php?error=sin_stock");
    }
    exit();
}

// 2. ELIMINAR Y VACIAR (Sin cambios, pero asegúrate de incluirlos)
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: ../views/cliente/carrito.php?msj=eliminado");
    exit();
}


// =========================================
// SUMAR CANTIDAD
// =========================================
if(isset($_GET['sumar'])){

    $id = (int)$_GET['sumar'];

    if(isset($_SESSION['carrito'][$id])){

        $_SESSION['carrito'][$id]['cantidad']++;

    }

    header('Location: ../views/cliente/carrito.php');

    exit();
}

// =========================================
// RESTAR CANTIDAD
// =========================================
if(isset($_GET['restar'])){

    $id = (int)$_GET['restar'];

    if(isset($_SESSION['carrito'][$id])){

        $_SESSION['carrito'][$id]['cantidad']--;

        // SI LLEGA A 0 SE ELIMINA
        if($_SESSION['carrito'][$id]['cantidad'] <= 0){

            unset($_SESSION['carrito'][$id]);

        }

    }

    header('Location: ../views/cliente/carrito.php');

    exit();
}



if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: ../views/cliente/carrito.php");
    exit();
}
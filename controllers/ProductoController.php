<?php
/**
 * Controlador de Productos - Tienda Gamer
 * Maneja Creación, Edición y Eliminación.
 */

require_once '../config/db.php';
require_once '../config/auth.php';

// Seguridad: Solo el admin puede operar aquí
requerirAdmin();

// --- 1. ACCIÓN DE ELIMINAR (Vía GET) ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Primero: Obtener el nombre de la imagen para borrarla del disco
        $stmtImg = $pdo->prepare("SELECT imagen FROM Producto WHERE id_producto = ?");
        $stmtImg->execute([$id]);
        $producto = $stmtImg->fetch();

        if ($producto) {
            // Borrar el archivo físico si no es la imagen por defecto
            if ($producto['imagen'] !== 'default_product.png') {
                $ruta_img = "../assets/img/productos/" . $producto['imagen'];
                if (file_exists($ruta_img)) {
                    unlink($ruta_img);
                }
            }

            // Segundo: Borrar el registro de la base de datos
            $delete = $pdo->prepare("DELETE FROM Producto WHERE id_producto = ?");
            $delete->execute([$id]);

            header("Location: ../views/admin/gestion_productos.php?msj=eliminado");
            exit();
        }
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
}

// --- 2. ACCIÓN DE GUARDAR NUEVO (Vía POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_guardar'])) {
    
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_categoria = (int)$_POST['id_categoria'];
    
    $nombre_imagen = "default_product.png";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $file_ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $extensiones = ["jpg", "jpeg", "png", "webp"];

        if (in_array($file_ext, $extensiones)) {
            $nuevo_nombre_img = "prod_" . bin2hex(random_bytes(4)) . "." . $file_ext;
            $ruta_destino = "../assets/img/productos/" . $nuevo_nombre_img;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $nombre_imagen = $nuevo_nombre_img;
            }
        }
    }

    try {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, stock, imagen, id_categoria) 
                VALUES (:nombre, :descripcion, :precio, :stock, :imagen, :id_categoria)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':precio'       => $precio,
            ':stock'        => $stock,
            ':imagen'       => $nombre_imagen,
            ':id_categoria' => $id_categoria
        ]);

        header("Location: ../views/admin/gestion_productos.php?msj=producto_creado");
        exit();

    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}
// --- 3. ACCIÓN DE ACTUALIZAR (Añadir esto al final de ProductoController.php) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_actualizar'])) {
    $id = (int)$_POST['id_producto'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_categoria = (int)$_POST['id_categoria'];

    try {
        // ¿El usuario subió una nueva imagen?
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $file_ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $nuevo_nombre_img = "prod_" . bin2hex(random_bytes(4)) . "." . $file_ext;
            $ruta_destino = "../assets/img/productos/" . $nuevo_nombre_img;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                // Borrar la imagen anterior para no llenar el disco
                $stmtImg = $pdo->prepare("SELECT imagen FROM Producto WHERE id_producto = ?");
                $stmtImg->execute([$id]);
                $vieja = $stmtImg->fetch();
                if ($vieja['imagen'] !== 'default_product.png') {
                    @unlink("../assets/img/productos/" . $vieja['imagen']);
                }
                
                // Actualizar con nueva imagen
                $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, stock=?, id_categoria=?, imagen=? WHERE id_producto=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $nuevo_nombre_img, $id]);
            }
        } else {
            // Actualizar SIN cambiar la imagen
            $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, stock=?, id_categoria=? WHERE id_producto=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $id]);
        }

        header("Location: ../views/admin/gestion_productos.php?msj=actualizado");
        exit();

    } catch (PDOException $e) {
        die("Error al actualizar: " . $e->getMessage());
    }
}
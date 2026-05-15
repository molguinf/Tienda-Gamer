<?php
/**
 * Controlador de Productos - Tienda Gamer (Soporte Multi-Imagen)
 */

require_once '../config/db.php';
require_once '../config/auth.php';

requerirAdmin();

$ruta_base = __DIR__ . "/../assets/img/productos/";

if (!is_dir($ruta_base)) {

    mkdir($ruta_base, 0777, true);

}



// --- 2. ACCIÓN DE GUARDAR NUEVO (Soporta múltiples archivos) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_guardar'])) {
    
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_categoria = (int)$_POST['id_categoria'];
    
    $nombres_imagenes = []; // Array para acumular nombres

    // Verificamos si se subieron archivos
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $extensiones_permitidas = ["jpg", "jpeg", "png", "webp"];
        
        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['imagenes']['name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $extensiones_permitidas)) {
                $nuevo_nombre = "prod_" . bin2hex(random_bytes(4)) . "_" . time() . "." . $file_ext;
                if (
                    move_uploaded_file(
                        $tmp_name,
                        $ruta_base . $nuevo_nombre
                    )
                ) {

                    $nombres_imagenes[] = $nuevo_nombre;

                } else {

                    die(
                        "Error al mover imagen: " .
                        $file_name
                    );

                }
            }
        }
    }

    // Si no se subió nada, usamos la por defecto
    $string_imagenes = !empty($nombres_imagenes) ? implode(',', $nombres_imagenes) : "default_product.png";

    try {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, stock, imagen, id_categoria) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $string_imagenes, $id_categoria]);

        header("Location: ../views/admin/gestion_productos.php?msj=producto_creado");
        exit();
    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}

// --- 3. ACCIÓN DE ACTUALIZAR (Mantiene anteriores + añade nuevas - elimina seleccionadas) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_actualizar'])) {
    $id = (int)$_POST['id_producto'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_categoria = (int)$_POST['id_categoria'];

    // 1. Imágenes que el usuario decidió CONSERVAR (vienen de checkboxes)
    $imagenes_a_mantener = isset($_POST['imagenes_actuales']) ? $_POST['imagenes_actuales'] : [];

    // 2. Lógica para borrar físicamente las que el usuario DESMARCÓ
    $stmt = $pdo->prepare("SELECT imagen FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    $prod_actual = $stmt->fetch();
    $fotos_viejas = explode(',', $prod_actual['imagen']);

    foreach ($fotos_viejas as $fv) {
        // Si la foto vieja NO está en el array de "mantener", la borramos del disco
        if (!in_array($fv, $imagenes_a_mantener) && $fv !== 'default_product.png') {
            @unlink($ruta_base . $fv);
        }
    }

    // 3. Procesar NUEVAS imágenes subidas
    if (isset($_FILES['imagenes_nuevas']) && !empty($_FILES['imagenes_nuevas']['name'][0])) {
        foreach ($_FILES['imagenes_nuevas']['tmp_name'] as $key => $tmp_name) {
            $file_ext = strtolower(pathinfo($_FILES['imagenes_nuevas']['name'][$key], PATHINFO_EXTENSION));
            $nuevo_nombre = "prod_" . bin2hex(random_bytes(4)) . "_" . time() . "." . $file_ext;
            
            if (
                move_uploaded_file(
                    $tmp_name,
                    $ruta_base . $nuevo_nombre
                )
            ) {

                $imagenes_a_mantener[] = $nuevo_nombre;

            } else {

                die(
                    "Error al mover nueva imagen."
                );

            }
        }
    }

    // 4. Consolidar el nuevo string para la BD
    $string_final = !empty($imagenes_a_mantener) ? implode(',', $imagenes_a_mantener) : "default_product.png";

    try {
        $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, stock=?, id_categoria=?, imagen=? WHERE id_producto=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $string_final, $id]);

        header("Location: ../views/admin/gestion_productos.php?msj=actualizado");
        exit();
    } catch (PDOException $e) {
        die("Error al actualizar: " . $e->getMessage());
    }
}
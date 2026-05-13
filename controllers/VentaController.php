<?php
require_once '../config/db.php';
require_once '../config/auth.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $metodo_pago = $_POST['metodo_pago'];
    $carrito = $_SESSION['carrito'];
    
    $total = 0;
    foreach ($carrito as $item) { $total += $item['precio'] * $item['cantidad']; }

    try {
        // INICIO DE TRANSACCIÓN (Punto crítico de seguridad de datos)
        $pdo->beginTransaction();

        // 1. Insertar en la tabla Venta
        $sqlVenta = "INSERT INTO Venta (id_usuario, total, metodo_pago, direccion, telefono, estado) 
                     VALUES (?, ?, ?, ?, ?, 'Pendiente')";
        $stmtVenta = $pdo->prepare($sqlVenta);
        $stmtVenta->execute([$id_usuario, $total, $metodo_pago, $direccion, $telefono]);
        
        // Recuperar el ID de la venta recién creada
        $id_venta = $pdo->lastInsertId();

        // 2. Procesar cada producto del carrito
        foreach ($carrito as $id_producto => $item) {
            // A. Insertar en Detalle_Venta
            $sqlDetalle = "INSERT INTO Detalle_Venta (id_venta, id_producto, cantidad, precio_unitario) 
                           VALUES (?, ?, ?, ?)";
            $stmtDetalle = $pdo->prepare($sqlDetalle);
            $stmtDetalle->execute([$id_venta, $id_producto, $item['cantidad'], $item['precio']]);

            // B. RESTAR STOCK (Lógica de Inventario)
            // Usamos una validación extra: solo resta si el stock es suficiente
            $sqlStock = "UPDATE Producto SET stock = stock - ? WHERE id_producto = ? AND stock >= ?";
            $stmtStock = $pdo->prepare($sqlStock);
            $stmtStock->execute([$item['cantidad'], $id_producto, $item['cantidad']]);

            // Si el stock no se actualizó (porque ya no había suficiente), lanzamos error
            if ($stmtStock->rowCount() === 0) {
                throw new Exception("Stock insuficiente para el producto: " . $item['nombre']);
            }
        }

        // Si todo salió bien, guardamos los cambios definitivamente
        $pdo->commit();

        // Limpiar el carrito
        $_SESSION['carrito'] = [];

        // Redirigir a una página de éxito
        header("Location: ../views/cliente/mis_pedidos.php?msj=compra_exitosa");
        exit();

    } catch (Exception $e) {
        // Si algo falla, deshacemos TODO lo que se hizo en la DB
        $pdo->rollBack();
        die("Error en la transacción: " . $e->getMessage());
    }
}
// --- ACCIÓN PARA EL ADMINISTRADOR: ACTUALIZAR ESTADO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_estado') {
    // Seguridad extra: Solo admin puede procesar esto
    requerirAdmin();

    $id_venta = (int)$_POST['id_venta'];
    $nuevo_estado = $_POST['nuevo_estado'];

    try {
        $stmt = $pdo->prepare("UPDATE Venta SET estado = ? WHERE id_venta = ?");
        $stmt->execute([$nuevo_estado, $id_venta]);

        header("Location: ../views/admin/gestion_ventas.php?msj=estado_actualizado");
        exit();
    } catch (PDOException $e) {
        die("Error al actualizar estado: " . $e->getMessage());
    }
}
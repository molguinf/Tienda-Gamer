
<?php

require_once '../config/db.php';
require_once '../config/auth.php';

// =========================================
// LOGIN OBLIGATORIO
// =========================================
requerirLogin();

// =========================================
// PROCESAR COMPRA
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && !isset($_POST['accion'])) {

    // VALIDAR CARRITO
    if (
        !isset($_SESSION['carrito']) ||
        empty($_SESSION['carrito'])
    ) {

        die('El carrito está vacío.');

    }

    $id_usuario = $_SESSION['id_usuario'];

    $metodo_pago = trim($_POST['metodo_pago']);

    $carrito = $_SESSION['carrito'];

    // =========================================
    // CALCULAR TOTAL
    // =========================================

    $total = 0;

    foreach ($carrito as $item) {

        $total += (
            $item['precio'] * $item['cantidad']
        );

    }

    // VALIDAR TOTAL
    if ($total <= 0) {

        die('Total inválido.');

    }

    try {

        // =========================================
        // INICIAR TRANSACCIÓN
        // =========================================

        $pdo->beginTransaction();

        // =========================================
        // INSERTAR VENTA
        // =========================================

        $sqlVenta = "
            INSERT INTO venta
            (
                id_usuario,
                total,
                metodo_pago,
                estado_venta
            )
            VALUES
            (
                ?, ?, ?, 'Pendiente'
            )
        ";

        $stmtVenta = $pdo->prepare($sqlVenta);

        $stmtVenta->execute([
            $id_usuario,
            $total,
            $metodo_pago
        ]);

        // ID VENTA
        $id_venta = $pdo->lastInsertId();

        // =========================================
        // RECORRER CARRITO
        // =========================================

        foreach ($carrito as $id_producto => $item) {

            $subtotal = (
                $item['precio'] * $item['cantidad']
            );

            // =========================================
            // INSERT DETALLE
            // =========================================

            $sqlDetalle = "
                INSERT INTO detalle_venta
                (
                    id_venta,
                    id_producto,
                    cantidad,
                    subtotal
                )
                VALUES
                (
                    ?, ?, ?, ?
                )
            ";

            $stmtDetalle = $pdo->prepare($sqlDetalle);

            $stmtDetalle->execute([
                $id_venta,
                $id_producto,
                $item['cantidad'],
                $subtotal
            ]);

            // =========================================
            // ACTUALIZAR STOCK
            // =========================================

            $sqlStock = "
                UPDATE producto
                SET stock = stock - ?
                WHERE id_producto = ?
                AND stock >= ?
            ";

            $stmtStock = $pdo->prepare($sqlStock);

            $stmtStock->execute([
                $item['cantidad'],
                $id_producto,
                $item['cantidad']
            ]);

            // VALIDAR STOCK
            if ($stmtStock->rowCount() === 0) {

                throw new Exception(
                    'Stock insuficiente para: '
                    . $item['nombre']
                );

            }

        }

        // =========================================
        // CONFIRMAR TRANSACCIÓN
        // =========================================

        $pdo->commit();

        // =========================================
        // LIMPIAR CARRITO
        // =========================================

        $_SESSION['carrito'] = [];

        // =========================================
        // REDIRIGIR
        // =========================================

        header(
            'Location: ../views/cliente/mis_pedidos.php?msj=compra_exitosa'
        );

        exit();

    } catch (Exception $e) {

        // DESHACER TODO
        $pdo->rollBack();

        die(
            'Error en la transacción: '
            . $e->getMessage()
        );

    }

}

// =========================================
// ACTUALIZAR ESTADO (ADMIN)
// =========================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['accion'])
    && $_POST['accion'] === 'actualizar_estado'
) {

    // SOLO ADMIN
    requerirAdmin();

    $id_venta = (int)$_POST['id_venta'];

    $nuevo_estado = trim($_POST['nuevo_estado']);

    try {

        $sql = "
            UPDATE venta
            SET estado_venta = ?
            WHERE id_venta = ?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $nuevo_estado,
            $id_venta
        ]);

        header(
            'Location: ../views/admin/gestion_ventas.php?ok=1'
        );

        exit();

    } catch (PDOException $e) {

        die(
            'Error al actualizar estado: '
            . $e->getMessage()
        );

    }

}


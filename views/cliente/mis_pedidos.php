
<?php 

require_once '../../config/db.php';
require_once '../../config/auth.php';

requerirLogin();

include '../includes/header.php'; 

// =========================================
// OBTENER PEDIDOS DEL USUARIO
// =========================================

try {

    $stmt = $pdo->prepare("
        SELECT * 
        FROM venta 
        WHERE id_usuario = ?
        ORDER BY fecha DESC
    ");

    $stmt->execute([
        $_SESSION['id_usuario']
    ]);

    $pedidos = $stmt->fetchAll();

} catch (PDOException $e) {

    die(
        "Error al cargar pedidos: " . $e->getMessage()
    );

}

?>

<div class="container my-5 pt-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold text-white">
            <i class="bi bi-bag-check me-2 text-primary"></i>
            Mis Pedidos
        </h2>

        <a 
            href="../../index.php" 
            class="btn btn-outline-light btn-sm rounded-pill"
        >
            Seguir Comprando
        </a>

    </div>

    <!-- ALERTA -->
    <?php if(isset($_GET['msj']) && $_GET['msj'] == 'compra_exitosa'): ?>

        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            <strong>¡Pedido realizado!</strong> 
            Tu compra se ha registrado con éxito.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php endif; ?>

    <!-- CARD -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-dark">

        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle mb-0">

                <!-- HEAD -->
                <thead>

                    <tr>

                        <th class="ps-4">
                            ID Pedido
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Método de Pago
                        </th>

                        <th>
                            Estado
                        </th>

                        <th class="text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <!-- BODY -->
                <tbody>

                    <?php if(count($pedidos) > 0): ?>

                        <?php foreach($pedidos as $p): ?>

                            <tr>

                                <!-- ID -->
                                <td class="ps-4 fw-bold">

                                    #<?php echo $p['id_venta']; ?>

                                </td>

                                <!-- FECHA -->
                                <td>

                                    <?php 
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime($p['fecha'])
                                    ); 
                                    ?>

                                </td>

                                <!-- TOTAL -->
                                <td class="fw-bold text-primary">

                                    <?php 
                                    echo number_format(
                                        $p['total'],
                                        2
                                    ); 
                                    ?> Bs.

                                </td>

                                <!-- MÉTODO -->
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        <?php 
                                        echo htmlspecialchars(
                                            $p['metodo_pago']
                                        ); 
                                        ?>

                                    </span>

                                </td>

                                <!-- ESTADO -->
                                <td>

                                    <?php 

                                    $estado = $p['estado_venta'];

                                    $color = 'secondary';

                                    if($estado == 'Pendiente'){
                                        $color = 'warning';
                                    }

                                    if($estado == 'Completado'){
                                        $color = 'success';
                                    }

                                    if($estado == 'Cancelado'){
                                        $color = 'danger';
                                    }

                                    ?>

                                    <span class="badge bg-<?php echo $color; ?>">

                                        <?php echo $estado; ?>

                                    </span>

                                </td>

                                <!-- BOTÓN -->
                                <td class="text-center">

                                    <a 
                                        href="detalle_pedido.php?id=<?php echo $p['id_venta']; ?>" 
                                        class="btn btn-sm btn-primary rounded-pill px-3"
                                    >
                                        Ver Detalle
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="text-center py-5 text-light">

                                <i class="bi bi-cart-x display-4 d-block mb-3"></i>

                                Aún no has realizado ninguna compra.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>


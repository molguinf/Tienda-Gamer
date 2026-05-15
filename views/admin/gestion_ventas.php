
<?php

require_once '../../config/db.php';
require_once '../../config/auth.php';

requerirAdmin();

include '../includes/header.php';

try {

    $where = [];
    $params = [];

    $sql = "
        SELECT 
            v.*, 
            u.nombre AS cliente,
            u.correo
        FROM venta v
        JOIN usuario u 
            ON v.id_usuario = u.id_usuario
    ";

    // FILTRO BUSCAR CLIENTE
    if (isset($_GET['buscar']) && $_GET['buscar'] != '') {

        $where[] = "u.nombre LIKE ?";

        $params[] = "%" . $_GET['buscar'] . "%";
    }

    // FILTRO ESTADO
    if (isset($_GET['estado']) && $_GET['estado'] != '') {

        $where[] = "v.estado_venta = ?";

        $params[] = $_GET['estado'];
    }

    // FILTRO FECHA
    if (isset($_GET['fecha']) && $_GET['fecha'] != '') {

        $where[] = "DATE(v.fecha) = ?";

        $params[] = $_GET['fecha'];
    }

    // AGREGAR WHERE
    if (!empty($where)) {

        $sql .= " WHERE " . implode(" AND ", $where);
    }

    // ORDEN
    $sql .= " ORDER BY v.fecha DESC";

    // PREPARAR Y EJECUTAR
    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    // RESULTADOS
    $ventas = $stmt->fetchAll();

} catch (PDOException $e) {

    die("Error: " . $e->getMessage());

}

?>

<div class="container my-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold text-white">

                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>

                Gestión de Ventas

            </h2>

            <p class="text-light opacity-75 mb-0">

                Administra pedidos y actualiza estados.

            </p>

        </div>

        <button 
            class="btn btn-outline-primary rounded-pill px-4"
            onclick="window.print()"
        >

            <i class="bi bi-printer me-2"></i>
            Exportar Reporte

        </button>

    </div>


    <!-- FILTROS -->
    <div class="card dashboard-card border-0 rounded-4 p-4 mb-4">

        <form method="GET" class="row g-3">

            <!-- BUSCAR CLIENTE -->
            <div class="col-md-4">

                <label class="form-label fw-bold text-light">

                    Buscar Cliente

                </label>

                <input
                    type="text"
                    name="buscar"
                    class="form-control sales-input"
                    placeholder="Nombre del cliente"
                    value="<?php echo $_GET['buscar'] ?? ''; ?>"
                >

            </div>


            <!-- FILTRAR ESTADO -->
            <div class="col-md-3">

                <label class="form-label fw-bold text-light">

                    Estado

                </label>

                <select
                    name="estado"
                    class="form-select sales-input"
                >

                    <option value="">

                        Todos

                    </option>

                    <option 
                        value="Pendiente"
                        <?php echo (($_GET['estado'] ?? '') == 'Pendiente') ? 'selected' : ''; ?>
                    >

                        Pendiente

                    </option>

                    <option 
                        value="Enviado"
                        <?php echo (($_GET['estado'] ?? '') == 'Enviado') ? 'selected' : ''; ?>
                    >

                        Enviado

                    </option>

                    <option 
                        value="Entregado"
                        <?php echo (($_GET['estado'] ?? '') == 'Entregado') ? 'selected' : ''; ?>
                    >

                        Entregado

                    </option>

                    <option 
                        value="Cancelado"
                        <?php echo (($_GET['estado'] ?? '') == 'Cancelado') ? 'selected' : ''; ?>
                    >

                        Cancelado

                    </option>

                </select>

            </div>


            <!-- FILTRAR FECHA -->
            <div class="col-md-3">

                <label class="form-label fw-bold text-light">

                    Fecha

                </label>

                <input
                    type="date"
                    name="fecha"
                    class="form-control sales-input"
                    value="<?php echo $_GET['fecha'] ?? ''; ?>"
                >

            </div>


            <!-- BOTON -->
            <div class="col-md-2 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn auth-btn w-100"
                >

                    <i class="bi bi-funnel me-2"></i>

                    Filtrar

                </button>

            </div>

        </form>

    </div>
    <!-- TABLA -->
    <div class="card dashboard-card border-0 rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0 sales-table">

                <thead>

                    <tr>

                        <th class="ps-4">ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(count($ventas) > 0): ?>

                        <?php foreach($ventas as $v): ?>

                            <tr>

                                <!-- ID -->
                                <td class="ps-4 fw-bold">

                                    #<?php echo $v['id_venta']; ?>

                                </td>

                                <!-- CLIENTE -->
                                <td>

                                    <div class="fw-bold">

                                        <?php echo htmlspecialchars($v['cliente']); ?>

                                    </div>

                                    <div class="small text-muted">

                                        <?php echo htmlspecialchars($v['correo']); ?>

                                    </div>

                                </td>

                                <!-- FECHA -->
                                <td>

                                    <?php
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime($v['fecha'])
                                    );
                                    ?>

                                </td>

                                <!-- TOTAL -->
                                <td class="fw-bold text-success">

                                    <?php
                                    echo number_format(
                                        $v['total'],
                                        2
                                    );
                                    ?> Bs.

                                </td>

                                <!-- METODO -->
                                <td>

                                    <span class="payment-badge">

                                        <?php echo htmlspecialchars($v['metodo_pago']); ?>

                                    </span>

                                </td>

                                <!-- ESTADO -->
                                <td>

                                    <form 
                                        action="../../controllers/VentaController.php"
                                        method="POST"
                                    >

                                        <input 
                                            type="hidden"
                                            name="accion"
                                            value="actualizar_estado"
                                        >

                                        <input 
                                            type="hidden"
                                            name="id_venta"
                                            value="<?php echo $v['id_venta']; ?>"
                                        >

                                        <select
                                            name="nuevo_estado"
                                            class="form-select form-select-sm"
                                            onchange="this.form.submit()"
                                        >

                                            <option
                                                value="Pendiente"
                                                <?php echo ($v['estado_venta'] == 'Pendiente') ? 'selected' : ''; ?>
                                            >
                                                🟡 Pendiente
                                            </option>

                                            <option
                                                value="Enviado"
                                                <?php echo ($v['estado_venta'] == 'Enviado') ? 'selected' : ''; ?>
                                            >
                                                🔵 Enviado
                                            </option>

                                            <option
                                                value="Entregado"
                                                <?php echo ($v['estado_venta'] == 'Entregado') ? 'selected' : ''; ?>
                                            >
                                                🟢 Entregado
                                            </option>

                                            <option
                                                value="Cancelado"
                                                <?php echo ($v['estado_venta'] == 'Cancelado') ? 'selected' : ''; ?>
                                            >
                                                🔴 Cancelado
                                            </option>

                                        </select>

                                    </form>

                                </td>

                                <!-- BOTON -->
                                <td class="text-center">

                                    <button
                                        class="btn btn-sm btn-dark rounded-pill px-3"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#productos_<?php echo $v['id_venta']; ?>"
                                    >

                                        <i class="bi bi-eye me-1"></i>

                                        Ver Productos

                                    </button>

                                </td>

                            </tr>

                            <!-- DETALLE -->
                            <tr 
                                class="collapse"
                                id="productos_<?php echo $v['id_venta']; ?>"
                            >

                                <td colspan="7" class="sales-detail p-4">

                                    <td colspan="7" class="sales-detail p-4">

                                        <h6 class="fw-bold text-primary mb-3">

                                            Productos del Pedido

                                        </h6>

                                        <ul class="list-group list-group-flush">

                                            <?php

                                            $stmtDet = $pdo->prepare("
                                                SELECT 
                                                    dv.*, 
                                                    p.nombre
                                                FROM detalle_venta dv
                                                JOIN producto p
                                                    ON dv.id_producto = p.id_producto
                                                WHERE dv.id_venta = ?
                                            ");

                                            $stmtDet->execute([
                                                $v['id_venta']
                                            ]);

                                            $detalles = $stmtDet->fetchAll();

                                            foreach($detalles as $det):

                                            ?>

                                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                                    <div>

                                                        <?php echo htmlspecialchars($det['nombre']); ?>

                                                        <strong class="text-primary">

                                                            x<?php echo $det['cantidad']; ?>

                                                        </strong>

                                                    </div>

                                                    <div class="text-success fw-bold">

                                                        <?php
                                                        echo number_format(
                                                            $det['subtotal'],
                                                            2
                                                        );
                                                        ?> Bs.

                                                    </div>

                                                </li>

                                            <?php endforeach; ?>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="text-center py-5 text-muted">

                                <i class="bi bi-bag-x display-5 d-block mb-3"></i>

                                No hay ventas registradas.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>


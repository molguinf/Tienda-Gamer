<?php 

require_once '../../config/db.php';
require_once '../../config/auth.php';

requerirAdmin();

include '../includes/header.php';


// OBTENER PRODUCTOS
try {

    $sql = "
        SELECT 
            p.*, 
            c.nombre_categoria AS categoria
        FROM Producto p
        INNER JOIN Categoria c 
            ON p.id_categoria = c.id_categoria
        ORDER BY p.id_producto DESC
    ";

    $stmt = $pdo->query($sql);

    $productos = $stmt->fetchAll();

} catch (PDOException $e) {

    die(
        "Error al obtener productos: " .
        $e->getMessage()
    );

}

?>

<div class="container py-4 dashboard-container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>

            <h2 class="fw-bold text-white mb-2">

                <i class="bi bi-box-seam me-2 text-primary"></i>

                Inventario de Productos

            </h2>

            <p class="text-light opacity-75 mb-0">

                Gestiona el stock y las imágenes de tus productos.

            </p>

        </div>

        <!-- BOTON -->
        <a 
            href="crear_producto.php"
            class="btn auth-btn"
        >

            <i class="bi bi-plus-lg me-2"></i>

            Nuevo Producto

        </a>

    </div>


    <!-- CARD -->
    <div class="card dashboard-card border-0 rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0 inventory-table">

                <!-- HEAD -->
                <thead>

                    <tr>

                        <th class="ps-4">

                            Imagen

                        </th>

                        <th>

                            Producto

                        </th>

                        <th>

                            Categoría

                        </th>

                        <th>

                            Precio

                        </th>

                        <th>

                            Stock

                        </th>

                        <th class="text-center">

                            Acciones

                        </th>

                    </tr>

                </thead>


                <!-- BODY -->
                <tbody>

                    <?php foreach ($productos as $p):

                        // MULTIPLES IMAGENES
                        $lista_imagenes = explode(
                            ',',
                            $p['imagen']
                        );

                        $foto_mostrar = !empty($lista_imagenes[0])

                            ? $lista_imagenes[0]

                            : 'default.png';

                    ?>

                        <tr>

                            <!-- IMAGEN -->
                            <td class="ps-4">

                                <img
                                    src="../../assets/img/productos/<?php echo $foto_mostrar; ?>"
                                    class="inventory-image"
                                    alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                                >

                            </td>


                            <!-- PRODUCTO -->
                            <td>

                                <div class="fw-bold text-white mb-1">

                                    <?php
                                    echo htmlspecialchars(
                                        $p['nombre']
                                    );
                                    ?>

                                </div>

                                <div class="small text-secondary">

                                    <?php
                                    echo count(
                                        $lista_imagenes
                                    );
                                    ?>

                                    foto(s)

                                </div>

                            </td>


                            <!-- CATEGORIA -->
                            <td>

                                <span class="inventory-category">

                                    <?php
                                    echo htmlspecialchars(
                                        $p['categoria']
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- PRECIO -->
                            <td>

                                <div class="inventory-price">

                                    <?php
                                    echo number_format(
                                        $p['precio'],
                                        2
                                    );
                                    ?>

                                    Bs.

                                </div>

                            </td>


                            <!-- STOCK -->
                            <td>

                                <?php if($p['stock'] <= 5): ?>

                                    <span class="stock-badge stock-low">

                                        <?php echo $p['stock']; ?>

                                    </span>

                                <?php else: ?>

                                    <span class="stock-badge stock-ok">

                                        <?php echo $p['stock']; ?>

                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- ACCIONES -->
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

    <!-- VER -->
    <a
        href="../cliente/detalle_producto.php?id=<?php echo $p['id_producto']; ?>"
        class="inventory-btn"
        title="Ver Producto"
    >

        <i class="bi bi-eye"></i>

    </a>

    <!-- EDITAR -->
    <a
        href="editar_producto.php?id=<?php echo $p['id_producto']; ?>"
        class="inventory-btn edit-btn"
        title="Editar"
    >

        <i class="bi bi-pencil"></i>

    </a>

    <!-- ELIMINAR -->
    <button
        onclick="confirmarEliminar(<?php echo $p['id_producto']; ?>)"
        class="inventory-btn delete-btn"
        title="Eliminar"
    >

        <i class="bi bi-trash"></i>

    </button>

</div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- SCRIPT -->
<script>

function confirmarEliminar(id){

    if(
        confirm(
            '¿Eliminar producto? Esto también borrará TODAS sus imágenes físicas del servidor.'
        )
    ){

        window.location.href =
            '../../controllers/ProductoController.php?action=delete&id='
            + id;

    }

}

</script>

<?php include '../includes/footer.php'; ?>
<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 

include '../includes/header.php'; 

// 1. Validar que exista un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_productos.php");
    exit();
}

$id = (int)$_GET['id'];

// 2. Obtener los datos actuales del producto
try {
    $stmt = $pdo->prepare("SELECT * FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if (!$producto) {
        header("Location: gestion_productos.php?error=no_encontrado");
        exit();
    }

    // Obtener categorías para el select
    $stmtCat = $pdo->query("SELECT * FROM Categoria");
    $categorias = $stmtCat->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white p-4">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Producto</h3>
                </div>
                <div class="card-body p-4">
                    
                    <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
                        <!-- Campo oculto para enviar el ID al controlador -->
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Precio (Bs.)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $producto['precio']; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Actual</label>
                                <input type="number" name="stock" class="form-control" value="<?php echo $producto['stock']; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Gestión de Imagen -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Nueva Imagen (Opcional)</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*" id="inputImagen">
                                <div class="form-text text-info">Deja este campo vacío si no quieres cambiar la imagen actual.</div>
                            </div>
                        </div>

                        <!-- Comparativa de Imágenes -->
                        <div class="row mb-4 text-center">
                            <div class="col-6">
                                <p class="small fw-bold">Imagen Actual:</p>
                                <img src="../../assets/img/productos/<?php echo $producto['imagen']; ?>" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            <div class="col-6 d-none" id="previewContainer">
                                <p class="small fw-bold text-success">Nueva Imagen:</p>
                                <img id="imgPreview" src="#" class="img-thumbnail border-success" style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="gestion_productos.php" class="btn btn-outline-secondary">Volver Atrás</a>
                            <button type="submit" name="btn_actualizar" class="btn btn-success px-5 fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Actualizar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputImagen = document.getElementById('inputImagen');
    const previewContainer = document.getElementById('previewContainer');
    const imgPreview = document.getElementById('imgPreview');

    inputImagen.onchange = evt => {
        const [file] = inputImagen.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            previewContainer.classList.remove('d-none');
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
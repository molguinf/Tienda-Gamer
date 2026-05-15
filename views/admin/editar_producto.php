<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 

include '../includes/header.php'; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_productos.php");
    exit();
}

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if (!$producto) {
        header("Location: gestion_productos.php?error=no_encontrado");
        exit();
    }

    // Consulta corregida: nombre_categoria según tu BD
    $stmtCat = $pdo->query("SELECT id_categoria, nombre_categoria FROM Categoria ORDER BY nombre_categoria ASC");
    $categorias = $stmtCat->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white p-4">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Producto Gamer</h3>
                </div>
                <div class="card-body p-4">
                    
                    <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Precio (Bs.)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $producto['precio']; ?>" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Stock Actual</label>
                                <input type="number" name="stock" class="form-control" value="<?php echo $producto['stock']; ?>" required>
                            </div>
                        </div>

                        <hr>

                        <div class="bg-light p-4 rounded-4 mb-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-images me-2 text-primary"></i>Galería de Imágenes</h5>
                            
                            <label class="form-label fw-bold text-muted small">IMÁGENES ACTUALES (Desmarca para eliminar)</label>
                            <div class="row g-3 mb-4">
                                <?php 
                                $mis_fotos = explode(',', $producto['imagen']);
                                foreach ($mis_fotos as $img): if(!empty($img)):
                                ?>
                                    <div class="col-6 col-md-3 text-center">
                                        <div class="position-relative border rounded p-1 bg-white shadow-sm">
                                            <img src="../../assets/img/productos/<?php echo $img; ?>" class="img-fluid rounded" style="height: 120px; object-fit: cover; width: 100%;">
                                            <div class="form-check mt-2 d-flex justify-content-center">
                                                <input class="form-check-input me-2" type="checkbox" name="imagenes_actuales[]" value="<?php echo $img; ?>" id="chk_<?php echo $img; ?>" checked>
                                                <label class="form-check-label small" for="chk_<?php echo $img; ?>">Mantener</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; endforeach; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Añadir Nuevas Fotos</label>
                                <input type="file" name="imagenes_nuevas[]" class="form-control" accept="image/*" id="inputNuevas" multiple>
                                <div class="form-text">Puedes seleccionar varios archivos a la vez.</div>
                            </div>

                            <div class="d-none" id="previewContainer">
                                <p class="small fw-bold text-success mt-3">Previsualización de nuevas imágenes:</p>
                                <div id="divPreview" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="gestion_productos.php" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Cancelar cambios</a>
                            <button type="submit" name="btn_actualizar" class="btn btn-success btn-lg px-5 fw-bold shadow">
                                <i class="bi bi-cloud-arrow-up me-2"></i>Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputNuevas = document.getElementById('inputNuevas');
    const previewContainer = document.getElementById('previewContainer');
    const divPreview = document.getElementById('divPreview');

    inputNuevas.onchange = evt => {
        divPreview.innerHTML = ''; // Limpiar previas
        const files = inputNuevas.files;
        
        if (files.length > 0) {
            previewContainer.classList.remove('d-none');
            
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.height = '100px';
                    img.style.width = '100px';
                    img.style.objectFit = 'cover';
                    divPreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.classList.add('d-none');
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
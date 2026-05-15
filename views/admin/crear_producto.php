<?php 
require_once '../../config/db.php';   // <--- ¡ESTA LÍNEA FALTA O ESTÁ MAL LA RUTA!
require_once '../../config/auth.php';
// Solo los administradores pueden entrar aquí
requerirAdmin(); 
$stmtCat = $pdo->query("SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria ASC");
$categorias = $stmtCat->fetchAll();

include '../includes/header.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white p-4">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Producto Gamer</h3>
                </div>
                <div class="card-body p-4">
                    
                    <!-- El atributo enctype="multipart/form-data" es OBLIGATORIO para subir fotos -->
                    <form action="../../controllers/ProductoController.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <!-- Nombre del Producto -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej. Teclado Mecánico Razer BlackWidow" required>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Descripción Detallada</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Especificaciones técnicas, switches, RGB, etc." required></textarea>
                            </div>

                            <!-- Precio -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Precio (Bs.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Inicial</label>
                                <input type="number" name="stock" class="form-control" placeholder="Cantidad disponible" required>
                            </div>

                            <!-- Categoría (Esto podría ser dinámico después) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <option value="" selected disabled>Selecciona una...</option>
                                    <?php foreach($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>">
                                            <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Imagen del Producto -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Imagen del Producto</label>
                                <input type="file" name="imagenes[]" class="form-control" accept="image/*" id="inputImagen" multiple required>
                                <div class="form-text">Formatos aceptados: JPG, PNG, WEBP.</div>
                            </div>
                        </div>

                        <!-- Vista Previa de la Imagen (Opcional pero muy pro) -->
                        <div class="mb-4 text-center d-none" id="previewContainer">
                            <p class="small fw-bold">Vista previa:</p>
                            <img id="imgPreview" src="#" alt="Vista previa" class="img-thumbnail" style="max-height: 200px;">
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="gestion_productos.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" name="btn_guardar" class="btn btn-primary px-5 fw-bold">
                                <i class="bi bi-save me-2"></i>Guardar Producto
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

    inputImagen.onchange = evt => {
        // Limpiamos el contenedor antes de mostrar las nuevas
        previewContainer.innerHTML = '<p class="small fw-bold">Vista previa de imágenes:</p>';
        const files = inputImagen.files;
        
        if (files.length > 0) {
            previewContainer.classList.remove('d-none');
            
            // Creamos una miniatura por cada archivo seleccionado
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail', 'me-2', 'mb-2');
                    img.style.height = '100px';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
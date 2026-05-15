<?php 
require_once '../../config/db.php';
require_once '../../config/auth.php';
requerirAdmin(); 
include '../includes/header.php'; 

$categorias = $pdo->query("SELECT * FROM Categoria ORDER BY id_categoria DESC")->fetchAll();
?>

<style>

body{
    background: #0f172a;
    color: #e2e8f0;
}

/* CONTENEDOR PRINCIPAL */
.card-categorias{
    background: #111827;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 12px 35px rgba(0,0,0,0.35);
}

/* TITULOS */
.titulo-admin{
    font-size: 2rem;
    color: #f1f5f9;
}

.subtitulo-admin{
    color: #94a3b8;
    margin-top: 6px;
    font-size: .96rem;
}

/* BOTON NUEVO */
.btn-add{
    background: linear-gradient(135deg,#1d4ed8,#2563eb);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 999px;
    font-weight: 600;
    transition: .3s ease;
    box-shadow: 0 8px 20px rgba(37,99,235,.25);
}

.btn-add:hover{
    transform: translateY(-2px);
    background: linear-gradient(135deg,#2563eb,#3b82f6);
    box-shadow: 0 12px 25px rgba(37,99,235,.35);
}

/* TABLA */
.table{
    color: #e2e8f0;
    margin-bottom: 0;
}

.table thead{
    background: #1e293b;
}

.table thead th{
    color: #cbd5e1;
    font-weight: 700;
    border: none;
    padding-top: 18px;
    padding-bottom: 18px;
}

.table tbody tr{
    border-color: rgba(255,255,255,0.04);
    transition: .25s ease;
}

.table tbody tr:hover{
    background: rgba(37,99,235,0.08);
}

/* BADGE CATEGORIA */
.categoria-badge{
    background: rgba(37,99,235,.15);
    color: #93c5fd;
    padding: 7px 14px;
    border-radius: 999px;
    font-size: .85rem;
    font-weight: 600;
    border: 1px solid rgba(59,130,246,.2);
}

/* TEXTO */
.desc-text{
    color: #94a3b8;
    font-size: .92rem;
}

/* BOTONES */
.btn-edit{
    border: none;
    background: rgba(37,99,235,.12);
    color: #60a5fa;
    width: 42px;
    height: 42px;
    border-radius: 14px;
    transition: .25s ease;
}

.btn-edit:hover{
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(37,99,235,.35);
}

.btn-delete{
    border: none;
    background: rgba(239,68,68,.12);
    color: #f87171;
    width: 42px;
    height: 42px;
    border-radius: 14px;
    transition: .25s ease;
}

.btn-delete:hover{
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(220,38,38,.35);
}

/* MODALS */
.modal-content{
    background: #111827;
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 28px;
    overflow: hidden;
    color: #e2e8f0;
}

.modal-header-custom{
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
    color: white;
    padding: 22px 28px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

/* INPUTS */
.form-control{
    background: #0f172a;
    border: 1px solid #334155;
    color: #f1f5f9;
    border-radius: 16px;
    padding: 14px;
    transition: .25s ease;
}

.form-control::placeholder{
    color: #64748b;
}

.form-control:focus{
    background: #0f172a;
    color: white;
    border-color: #2563eb;
    box-shadow: 0 0 0 .15rem rgba(37,99,235,.2);
}

/* BOTON GUARDAR */
.btn-save{
    background: linear-gradient(135deg,#1d4ed8,#2563eb);
    border: none;
    border-radius: 16px;
    padding: 14px;
    font-weight: 700;
    transition: .3s ease;
}

.btn-save:hover{
    transform: translateY(-2px);
    background: linear-gradient(135deg,#2563eb,#3b82f6);
    box-shadow: 0 12px 25px rgba(37,99,235,.35);
}

/* SCROLL TABLA */
.table-responsive::-webkit-scrollbar{
    height: 8px;
}

.table-responsive::-webkit-scrollbar-thumb{
    background: #334155;
    border-radius: 999px;
}

.table-responsive::-webkit-scrollbar-track{
    background: transparent;
}

</style>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h2 class="fw-bold titulo-admin">
                <i class="bi bi-tags me-2"></i>Gestión de Categorías
            </h2>

            <p class="subtitulo-admin">
                Organiza los productos de la tienda por grupos.
            </p>
        </div>

        <button 
            class="btn-add"
            data-bs-toggle="modal"
            data-bs-target="#modalCategoria"
        >
            <i class="bi bi-plus-lg me-2"></i>Nueva Categoría
        </button>

    </div>

    <div class="card-categorias">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre de Categoría</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($categorias as $c): ?>

                        <tr>

                            <td class="ps-4 fw-semibold text-secondary">
                                #<?php echo $c['id_categoria']; ?>
                            </td>

                            <td>
                                <span class="categoria-badge">
                                    <?php echo htmlspecialchars($c['nombre_categoria']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="desc-text">
                                    <?php echo htmlspecialchars($c['descripcion']); ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <button 
                                    class="btn-edit me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editarCategoria<?php echo $c['id_categoria']; ?>"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button 
                                    class="btn-delete"
                                    onclick="confirmarEliminarCat(<?php echo $c['id_categoria']; ?>)"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="modal fade" id="modalCategoria" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <form 
            action="../../controllers/CategoriaController.php"
            method="POST"
            class="modal-content shadow-lg"
        >

            <div class="modal-header-custom d-flex justify-content-between align-items-center">

                <h5 class="modal-title fw-bold mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Añadir Categoría
                </h5>

                <button 
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body p-4">

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Nombre
                    </label>

                    <input 
                        type="text"
                        name="nombre_categoria"
                        class="form-control"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Descripción
                    </label>

                    <textarea 
                        name="descripcion"
                        class="form-control"
                        rows="4"
                    ></textarea>

                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4">

                <button 
                    type="submit"
                    name="btn_guardar"
                    class="btn-save w-100 text-white"
                >
                    <i class="bi bi-save2 me-2"></i>Guardar Categoría
                </button>

            </div>

        </form>

    </div>

</div>

<?php foreach ($categorias as $c): ?>

<div class="modal fade" id="editarCategoria<?php echo $c['id_categoria']; ?>" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <form 
            action="../../controllers/CategoriaController.php"
            method="POST"
            class="modal-content shadow-lg"
        >

            <div class="modal-header-custom d-flex justify-content-between align-items-center">

                <h5 class="modal-title fw-bold mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Editar Categoría
                </h5>

                <button 
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body p-4">

                <input 
                    type="hidden"
                    name="id_categoria"
                    value="<?php echo $c['id_categoria']; ?>"
                >

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Nombre de Categoría
                    </label>

                    <input 
                        type="text"
                        name="nombre_categoria"
                        class="form-control"
                        value="<?php echo htmlspecialchars($c['nombre_categoria']); ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Descripción
                    </label>

                    <textarea 
                        name="descripcion"
                        class="form-control"
                        rows="4"
                    ><?php echo htmlspecialchars($c['descripcion']); ?></textarea>

                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4">

                <button 
                    type="submit"
                    name="btn_editar"
                    class="btn-save w-100 text-white"
                >
                    <i class="bi bi-save2 me-2"></i>Guardar Cambios
                </button>

            </div>

        </form>

    </div>

</div>

<?php endforeach; ?>

<script>

function confirmarEliminarCat(id) {

    if (confirm('¿Eliminar categoría? Los productos asociados podrían quedar sin categoría.')) {

        window.location.href = '../../controllers/CategoriaController.php?action=delete&id=' + id;

    }

}

</script>

<?php include '../includes/footer.php'; ?>
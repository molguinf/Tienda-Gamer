<?php 

require_once '../../config/db.php';
require_once '../../config/auth.php';

requerirAdmin();

include '../includes/header.php';


// OBTENER USUARIOS
try {

    $sql = "
        SELECT 
            id_usuario,
            nombre,
            correo,
            rol,
            fecha_registro
        FROM usuario
        ORDER BY rol ASC, nombre ASC
    ";

    $stmt = $pdo->query($sql);

    $usuarios = $stmt->fetchAll();

} catch (PDOException $e) {

    die(
        "Error al obtener usuarios: " .
        $e->getMessage()
    );

}

?>

<div class="container my-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>

            <h2 class="fw-bold text-white mb-2">

                <i class="bi bi-people-fill me-2 text-primary"></i>

                Usuarios Registrados

            </h2>

            <p class="text-light opacity-75 mb-0">

                Lista completa de clientes y administradores del sistema.

            </p>

        </div>

        <!-- TOTAL -->
        <div class="badge bg-dark border border-primary px-4 py-3 rounded-pill fs-6 shadow">

            <i class="bi bi-person-check me-2"></i>

            Total:
            <?php echo count($usuarios); ?>

            usuarios

        </div>

    </div>


    <!-- CARD -->
    <div class="card profile-card shadow-lg border-0 rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0 user-table">

                <!-- HEAD -->
                <thead>

                    <tr>

                        <th class="ps-4">

                            ID

                        </th>

                        <th>

                            Usuario

                        </th>

                        <th>

                            Correo Electrónico

                        </th>

                        <th>

                            Rol

                        </th>

                        <th>

                            Fecha Registro

                        </th>

                        <th class="text-center">

                            Estado

                        </th>

                    </tr>

                </thead>


                <!-- BODY -->
                <tbody>

                    <?php foreach ($usuarios as $u): ?>

                        <tr>

                            <!-- ID -->
                            <td class="ps-4 text-secondary fw-semibold">

                                #<?php echo $u['id_usuario']; ?>

                            </td>


                            <!-- USUARIO -->
                            <td>

                                <div class="d-flex align-items-center">

                                    <!-- AVATAR -->
                                    <div class="user-avatar me-3">

                                        <i class="bi bi-person-fill"></i>

                                    </div>

                                    <div>

                                        <div class="fw-bold text-white">

                                            <?php
                                            echo htmlspecialchars(
                                                $u['nombre']
                                            );
                                            ?>

                                        </div>

                                        <div class="small text-secondary">

                                            Usuario activo

                                        </div>

                                    </div>

                                </div>

                            </td>


                            <!-- CORREO -->
                            <td class="text-light">

                                <?php
                                echo htmlspecialchars(
                                    $u['correo']
                                );
                                ?>

                            </td>


                            <!-- ROL -->
                            <td>

                                <?php if ($u['rol'] === 'admin'): ?>

                                    <span class="badge role-admin">

                                        <i class="bi bi-shield-lock me-1"></i>

                                        Admin

                                    </span>

                                <?php else: ?>

                                    <span class="badge role-client">

                                        <i class="bi bi-person me-1"></i>

                                        Cliente

                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- FECHA -->
                            <td class="text-secondary">

                                <?php
                                echo date(
                                    'd/m/Y',
                                    strtotime(
                                        $u['fecha_registro']
                                    )
                                );
                                ?>

                            </td>


                            <!-- ESTADO -->
                            <td class="text-center">

                                <span class="status-active">

                                    <i class="bi bi-circle-fill me-1"></i>

                                    Activo

                                </span>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
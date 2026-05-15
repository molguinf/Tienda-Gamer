<?php
require_once '../config/db.php';
require_once '../config/auth.php';
requerirAdmin();

// --- ELIMINAR ---
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id_categoria = ?");
    $stmt->execute([$id]);
    header("Location: ../views/admin/gestion_categorias.php?msj=eliminado");
    exit();
}

// --- GUARDAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_guardar'])) {
    $nombre = trim($_POST['nombre_categoria']);
    $desc = trim($_POST['descripcion']);

    try {
        $stmt = $pdo->prepare("INSERT INTO Categoria (nombre_categoria, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $desc]);
        header("Location: ../views/admin/gestion_categorias.php?msj=creado");
        exit();
    } catch (PDOException $e) {
        die("Error al crear categoría: " . $e->getMessage());
    }
}
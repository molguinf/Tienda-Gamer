<?php
require_once '../config/auth.php';
requerirLogin();

if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [];
}

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    // Solo lo agregamos si no existe ya
    if (!in_array($id, $_SESSION['favoritos'])) {
        $_SESSION['favoritos'][] = $id;
    }
    header("Location: " . $_SERVER['HTTP_REFERER']); // Regresa a donde estaba
    exit();
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if (($key = array_search($id, $_SESSION['favoritos'])) !== false) {
        unset($_SESSION['favoritos'][$key]);
    }
    header("Location: ../views/cliente/favoritos.php");
    exit();
}
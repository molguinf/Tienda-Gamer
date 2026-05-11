<?php
// Configuración de la base de datos
$host = 'localhost';
$db   = 'TiendaGamer';
$user = 'root'; // Usuario por defecto en XAMPP/Wamp
$pass = '';     // Contraseña por defecto (vacía en XAMPP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve datos como array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva emulación para mayor seguridad
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En producción, no deberías mostrar el mensaje de error real ($e->getMessage())
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
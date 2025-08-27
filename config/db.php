<?php
// Conexión MySQL para localhost (root, sin contraseña)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_inventario";

// Habilitar errores de mysqli para depurar correctamente
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Compatibilidad con código que use $conexion
$conexion = $conn;

// Zona horaria
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('America/Argentina/Cordoba');
}
?>

<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['dpi'])) {
    die("❌ Error: DPI no especificado.");
}

$dpi = $_GET['dpi'];

// Este es el DPI del empleado "sin asignar"
$dpi_sin_asignar = '0000000000000';

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Reasignar buses a "sin asignar"
    $stmt = $pdo->prepare("UPDATE bus SET DPI_Empleado = ? WHERE DPI_Empleado = ?");
    $stmt->execute([$dpi_sin_asignar, $dpi]);

    // Ahora eliminar el empleado
    $stmt = $pdo->prepare("DELETE FROM empleado WHERE DPI_Empleado = ?");
    $stmt->execute([$dpi]);

    header("Location: empleados.php?eliminado=1");
    exit();

} catch (PDOException $e) {
    die("❌ Error al eliminar: " . $e->getMessage());
}
?>

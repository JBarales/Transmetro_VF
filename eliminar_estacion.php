<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("❌ Error: ID de estación no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Primero elimina las asociaciones de la estación en la tabla intermedia
    $stmt = $pdo->prepare("DELETE FROM estacion_linea WHERE ID_Estacion = ?");
    $stmt->execute([$id]);

    // Luego elimina la estación
    $stmt = $pdo->prepare("DELETE FROM estacion WHERE ID_Estacion = ?");
    $stmt->execute([$id]);

    header("Location: estaciones.php?eliminado=1");
    exit();

} catch (PDOException $e) {
    die("❌ Error al eliminar la estación: " . $e->getMessage());
}
?>

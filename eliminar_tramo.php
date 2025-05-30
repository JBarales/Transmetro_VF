<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("❌ Error: ID de tramo no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Eliminar el tramo
    $stmt = $pdo->prepare("DELETE FROM tramo WHERE ID_Tramo = ?");
    $stmt->execute([$id]);

    header("Location: tramos.php?eliminado=1");
    exit();

} catch (PDOException $e) {
    die("❌ Error al eliminar el tramo: " . $e->getMessage());
}
?>


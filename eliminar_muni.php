<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("DELETE FROM catalogomunicipalidad WHERE ID_Municipalidad = ?");
    $stmt->execute([$id]);

    header("Location: municipalidades.php?eliminado=1");
    exit();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

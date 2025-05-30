<?php
session_start();
header("Content-Type: text/html; charset=UTF-8"); // ✅ Solución para que el navegador muestre tildes
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'lineas';

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sql = "SELECT l.*, c.Nombre AS Nombre_Municipalidad FROM linea l
        LEFT JOIN catalogomunicipalidad c ON l.ID_Municipalidad = c.ID_Municipalidad";
$stmt = $pdo->query($sql);
$lineas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ver Líneas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    body { overflow-x: hidden; }
    .sidebar { min-height: 100vh; background-color: #343a40; }
    .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px 15px; }
    .sidebar a:hover { background-color: #495057; }
</style>
</head>
<body>
<div class="row g-0">
    <?php include 'sidebar.php'; ?>

    <div class="col-md-9 col-lg-10 p-4">
        <h2>Líneas Registradas</h2>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Municipalidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineas as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['ID_Linea']) ?></td>
                        <td><?= htmlspecialchars($l['Nombre_Linea']) ?></td>
                        <td><?= htmlspecialchars($l['Nombre_Municipalidad']) ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

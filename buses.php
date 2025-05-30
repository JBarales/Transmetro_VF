<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'buses';

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Consulta de los buses
$sql = "SELECT b.*, l.Nombre_Linea, p.Nombre_Parqueo
        FROM bus b
        LEFT JOIN linea l ON b.ID_Linea = l.ID_Linea
        LEFT JOIN parqueo p ON b.ID_Parqueo = p.ID_Parqueo";
$stmt = $pdo->query($sql);
$buses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ver Buses</title>
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
        <h2>Buses Registrados</h2>
        <a href="registrar_bus.php" class="btn btn-success mb-3">Registrar Nuevo bus</a>
        <?php if (empty($buses)): ?>
            <div class="alert alert-warning">No hay buses registrados.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Línea</th>
                        <th>parqueo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($buses as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['ID_Bus']) ?></td>
                            <td><?= htmlspecialchars($b['PLACA_BUS']) ?></td>
                            <td><?= htmlspecialchars($b['Nombre_Linea'] ?? 'Sin línea') ?></td>
                            <td><?= htmlspecialchars($b['Nombre_Parqueo'] ?? 'Sin parqueo') ?></td>
                            <td><?= htmlspecialchars($b['Estado']) ?></td>
                            <td>
                                <a href="editar_bus.php?id=<?= $b['ID_Bus'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_bus.php?id=<?= $b['ID_Bus'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

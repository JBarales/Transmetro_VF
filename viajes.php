<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'viajes';

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Cambiar a LEFT JOIN para ver aunque falte algún dato
$sql = "SELECT r.*, b.PLACA_BUS, u.Nombre AS Nombre_Operador, e.Nombre AS Nombre_Estacion
        FROM registro_viaje r
        LEFT JOIN bus b ON r.ID_Bus = b.ID_Bus
        LEFT JOIN usuario u ON r.ID_Usuario = u.ID_Usuario
        LEFT JOIN estacion e ON r.ID_Estacion = e.ID_Estacion
        ORDER BY r.Fecha_Registro DESC";
$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registros de usuarios en Buses (con Estación)</title>
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
        <h2>Registros de usuarios en Buses (con Estación)</h2>
        <?php if (empty($registros)): ?>
            <div class="alert alert-warning">No hay registros disponibles.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>bus</th>
                        <th>Estación</th>
                        <th>Operador</th>
                        <th>Cantidad de usuarios</th>
                        <th>Fecha/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $r): ?>
                        <tr>
                            <td><?= $r['ID_Registro'] ?></td>
                            <td><?= htmlspecialchars($r['PLACA_BUS'] ?? 'Desconocido') ?></td>
                            <td><?= htmlspecialchars($r['Nombre_Estacion'] ?? 'Desconocida') ?></td>
                            <td><?= htmlspecialchars($r['Nombre_Operador'] ?? 'Desconocido') ?></td>
                            <td><?= htmlspecialchars($r['Cantidad_Usuarios']) ?></td>
                            <td><?= htmlspecialchars($r['Fecha_Registro']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

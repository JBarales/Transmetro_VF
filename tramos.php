<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'tramos';

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Búsqueda
$busqueda = $_GET['buscar'] ?? '';

$sql = "SELECT t.*, l.Nombre_Linea, e1.Nombre AS Origen, e2.Nombre AS Destino
        FROM tramo t
        JOIN linea l ON t.ID_Linea = l.ID_Linea
        JOIN estacion e1 ON t.ID_Estacion_Origen = e1.ID_Estacion
        JOIN estacion e2 ON t.ID_Estacion_Destino = e2.ID_Estacion";

$params = [];

if ($busqueda) {
    $sql .= " WHERE l.Nombre_Linea LIKE ?";
    $params = ["%$busqueda%"];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tramos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lista de Tramos</title>
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
        <h2>Lista de Tramos</h2>

        <!-- Buscador -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por línea" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <a href="registrar_tramo.php" class="btn btn-success mb-3">Registrar Nuevo Tramo</a>

        <?php if (count($tramos) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Línea</th>
                        <th>Estación Origen</th>
                        <th>Estación Destino</th>
                        <th>Kilómetros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tramos as $t): ?>
                        <tr>
                            <td><?= $t['ID_Tramo'] ?></td>
                            <td><?= htmlspecialchars($t['Nombre_Linea']) ?></td>
                            <td><?= htmlspecialchars($t['Origen']) ?></td>
                            <td><?= htmlspecialchars($t['Destino']) ?></td>
                            <td><?= htmlspecialchars($t['Kilometros']) ?></td>
                            <td>
                                <a href="editar_tramo.php?id=<?= $t['ID_Tramo'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_tramo.php?id=<?= $t['ID_Tramo'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron tramos.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

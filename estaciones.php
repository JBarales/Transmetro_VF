<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'estaciones';
$rol = $_SESSION['rol'] ?? '';

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Búsqueda
$busqueda = $_GET['buscar'] ?? '';

$sql = "SELECT e.*, GROUP_CONCAT(l.Nombre_Linea SEPARATOR ', ') AS Lineas
        FROM estacion e
        LEFT JOIN estacion_linea el ON e.ID_Estacion = el.ID_Estacion
        LEFT JOIN linea l ON el.ID_Linea = l.ID_Linea";
$params = [];

if ($busqueda) {
    $sql .= " WHERE e.Nombre LIKE ? OR e.Direccion LIKE ?";
    $params = ["%$busqueda%", "%$busqueda%"];
}

$sql .= " GROUP BY e.ID_Estacion";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ver Estaciones</title>
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
        <h2>Estaciones Registradas</h2>

        <!-- Buscador -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o dirección" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if ($rol === 'admin'): ?>
            <a href="registrar_estacion.php" class="btn btn-success mb-3">Registrar Nueva Estación</a>
        <?php endif; ?>

        <?php if (count($estaciones) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Municipalidad</th>
                        <th>Capacidad</th>
                        <th>Usuarios</th>
                        <th>Estado</th>
                        <th>Líneas Asociadas</th>
                        <?php if ($rol === 'admin'): ?>
                            <th class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estaciones as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['ID_Estacion']) ?></td>
                            <td><?= htmlspecialchars($e['Nombre']) ?></td>
                            <td><?= htmlspecialchars($e['Direccion']) ?></td>
                            <td><?= htmlspecialchars($e['ID_Municipalidad']) ?></td>
                            <td><?= htmlspecialchars($e['Capacidad']) ?></td>
                            <td><?= htmlspecialchars($e['Cantidad_Usuarios']) ?></td>
                            <td><?= htmlspecialchars($e['Estado']) ?></td>
                            <td><?= htmlspecialchars($e['Lineas']) ?></td>
                            <?php if ($rol === 'admin'): ?>
                                <td class="text-center">
                                    <a href="editar_estacion.php?id=<?= urlencode($e['ID_Estacion']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminar_estacion.php?id=<?= urlencode($e['ID_Estacion']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron estaciones.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

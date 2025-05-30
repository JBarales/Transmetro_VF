<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Busqueda de viajes
$busqueda_viaje = $_GET['buscar_viaje'] ?? '';

// Paginación
$limite = 10; // registros por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

$sql_viajes = "SELECT r.ID_Registro, b.PLACA_BUS, u.Nombre AS Usuario, e.Nombre AS Estacion, r.Cantidad_Usuarios, r.Fecha_Registro
                FROM registro_viaje r
                JOIN bus b ON r.ID_Bus = b.ID_Bus
                LEFT JOIN usuario u ON r.ID_Usuario = u.ID_Usuario
                JOIN estacion e ON r.ID_Estacion = e.ID_Estacion";
if ($busqueda_viaje) {
    $sql_viajes .= " WHERE b.PLACA_BUS LIKE ? OR u.Nombre LIKE ? OR e.Nombre LIKE ?";
}
$sql_viajes .= " ORDER BY r.Fecha_Registro DESC LIMIT $limite OFFSET $offset";
$stmt_viajes = $pdo->prepare($sql_viajes);

$params = [];
if ($busqueda_viaje) {
    $params = ["%$busqueda_viaje%", "%$busqueda_viaje%", "%$busqueda_viaje%"];
}
$stmt_viajes->execute($params);
$viajes_registrados = $stmt_viajes->fetchAll();

// Calcular el total de registros
$sql_total = "SELECT COUNT(*) FROM registro_viaje r
                JOIN bus b ON r.ID_Bus = b.ID_Bus
                LEFT JOIN usuario u ON r.ID_Usuario = u.ID_Usuario
                JOIN estacion e ON r.ID_Estacion = e.ID_Estacion";
if ($busqueda_viaje) {
    $sql_total .= " WHERE b.PLACA_BUS LIKE ? OR u.Nombre LIKE ? OR e.Nombre LIKE ?";
}
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Control</title>
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
        <h2>Bienvenido al Panel</h2>
        <p>Hola, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>. Usa el menú lateral para navegar por el sistema.</p>
        <div class="alert alert-info mt-4">Tienes acceso completo como administrador.</div>

        <!-- Sección de Viajes Registrados -->
        <div class="mt-4">
            <h4>Viajes Registrados</h4>
            <form method="GET" class="mb-3 d-flex">
                <input type="text" name="buscar_viaje" class="form-control me-2" placeholder="Buscar por bus, usuario o estación" value="<?= htmlspecialchars($busqueda_viaje) ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID Registro</th>
                        <th>Placa Bus</th>
                        <th>Usuario</th>
                        <th>Estación</th>
                        <th>Cantidad Usuarios</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($viajes_registrados): ?>
                        <?php foreach ($viajes_registrados as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['ID_Registro']) ?></td>
                                <td><?= htmlspecialchars($v['PLACA_BUS']) ?></td>
                                <td><?= htmlspecialchars($v['Usuario'] ?? 'No asignado') ?></td>
                                <td><?= htmlspecialchars($v['Estacion']) ?></td>
                                <td><?= htmlspecialchars($v['Cantidad_Usuarios']) ?></td>
                                <td><?= htmlspecialchars($v['Fecha_Registro']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No se encontraron registros de viajes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Paginación -->
            <nav>
              <ul class="pagination">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                  <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&buscar_viaje=<?= htmlspecialchars($busqueda_viaje) ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Consulta para la cantidad de buses por línea
$sql = "SELECT l.Nombre_Linea, COUNT(b.ID_Bus) AS cantidad_buses
        FROM linea l
        LEFT JOIN bus b ON l.ID_Linea = b.ID_Linea
        GROUP BY l.ID_Linea, l.Nombre_Linea";
$stmt = $pdo->query($sql);
$buses_por_linea = $stmt->fetchAll();
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

    <!-- Sidebar integrado directamente -->
    <div class="col-md-3 col-lg-2 sidebar bg-dark text-white">
        <div class="p-3">
            <h5 class="text-white">Panel</h5>
            <hr class="text-white">
            <p class="text-white">Usuario: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
            <p class="text-white">Rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></p>
            <hr class="text-white">

            <a href="panel.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'panel.php' ? 'fw-bold' : '' ?>">Inicio</a>

            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <?php
                $menu_items = [
                    'empleados' => 'Empleados',
                    'municipalidades' => 'Municipalidades',
                    'parqueos' => 'Parqueos',
                    'lineas' => 'Líneas',
                    'estaciones' => 'Estaciones',
                    'buses' => 'Buses',
                    'tramos' => 'Tramos',
                    'viajes' => 'Viajes',
                    'usuarios' => 'Usuarios'
                ];

                foreach ($menu_items as $id => $nombre):
                    $is_open = (isset($submenu_abierto) && $submenu_abierto === $id);
                ?>
                    <a class="text-white text-decoration-none d-flex justify-content-between align-items-center mb-2" data-bs-toggle="collapse" href="#submenu<?= ucfirst($id) ?>" role="button" aria-expanded="<?= $is_open ? 'true' : 'false' ?>" aria-controls="submenu<?= ucfirst($id) ?>">
                        <?= $nombre ?>
                        <span class="bi <?= $is_open ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></span>
                    </a>
                    <div class="collapse ps-3 <?= $is_open ? 'show' : '' ?>" id="submenu<?= ucfirst($id) ?>">
                        <a href="<?= $id ?>.php" class="text-white text-decoration-none d-block mb-1 <?= basename($_SERVER['PHP_SELF']) === "$id.php" ? 'fw-bold' : '' ?>">Ver <?= $nombre ?></a>
                        <a href="registrar_<?= rtrim($id, 's') ?>.php" class="text-white text-decoration-none d-block mb-1 <?= basename($_SERVER['PHP_SELF']) === "registrar_".rtrim($id, 's').".php" ? 'fw-bold' : '' ?>">Registrar <?= rtrim($nombre, 's') ?></a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="lineas.php" class="text-white text-decoration-none d-block mb-2">Ver Líneas</a>
                <a href="estaciones.php" class="text-white text-decoration-none d-block mb-2">Ver Estaciones</a>
                <a href="registrar_viaje.php" class="text-white text-decoration-none d-block mb-2">Registrar Viaje</a>
                <a href="viajes.php" class="text-white text-decoration-none d-block mb-2">Ver Registros de Viajes</a>
            <?php endif; ?>

            <a href="logout.php" class="text-white text-decoration-none d-block mt-3">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="col-md-9 col-lg-10 p-4">
        <h2>Bienvenido al Panel</h2>
        <p>Hola, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>. Usa el menú lateral para navegar por el sistema.</p>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <div class="alert alert-info mt-4">Tienes acceso completo como administrador.</div>
            <a href="reporte_empleados.php" class="btn btn-primary mt-3">Ver Reporte de Empleados Activos</a>
        <?php else: ?>
            <div class="alert alert-info mt-4">Tienes acceso como usuario normal. Puedes ver líneas, estaciones y registrar viajes.</div>
        <?php endif; ?>

        <div class="mt-4">
            <h4>Cantidad de Buses por Línea</h4>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Línea</th>
                        <th>Cantidad de Buses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($buses_por_linea as $bpl): ?>
                        <tr>
                            <td><?= htmlspecialchars($bpl['Nombre_Linea']) ?></td>
                            <td><?= htmlspecialchars($bpl['cantidad_buses']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

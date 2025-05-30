
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = ''; // Puedes ajustarlo si quieres que un submenu aparezca abierto por defecto

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Consulta para la cantidad de buses operando por línea
$sql_buses = "SELECT l.Nombre_Linea AS linea, COUNT(b.ID_Bus) AS cantidad_buses
               FROM linea l
               LEFT JOIN bus b ON l.ID_Linea = b.ID_Linea AND b.Estado = 'en línea'
               GROUP BY l.ID_Linea, l.Nombre_Linea";
$stmt_buses = $pdo->query($sql_buses);
$operacion_lineas = $stmt_buses->fetchAll();

// Consulta para la cantidad de viajes por bus
$sql_viajes = "SELECT b.ID_Bus, b.PLACA_BUS, COUNT(r.ID_Registro) AS cantidad_viajes, MAX(r.Fecha_Registro) AS ultimo_viaje
                FROM bus b
                LEFT JOIN registro_viaje r ON b.ID_Bus = r.ID_Bus
                GROUP BY b.ID_Bus, b.PLACA_BUS";
$stmt_viajes = $pdo->query($sql_viajes);
$viajes_por_bus = $stmt_viajes->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Control</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <div class="alert alert-info mt-4">Tienes acceso completo como administrador.</div>
        <?php else: ?>
            <div class="alert alert-info mt-4">Tienes acceso como usuario normal. Puedes ver líneas, estaciones y registrar viajes.</div>
        <?php endif; ?>

        <div class="mt-4">
            <h4>Cantidad de Buses Operando por Línea</h4>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Línea</th>
                        <th>Cantidad de Buses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operacion_lineas as $ol): ?>
                        <tr>
                            <td><?= htmlspecialchars($ol['linea']) ?></td>
                            <td><?= htmlspecialchars($ol['cantidad_buses']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h4>Viajes Registrados por Bus</h4>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID Bus</th>
                        <th>Placa</th>
                        <th>Cantidad de Viajes</th>
                        <th>Último Viaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viajes_por_bus as $v): ?>
                        <tr>
                            <td><?= htmlspecialchars($v['ID_Bus']) ?></td>
                            <td><?= htmlspecialchars($v['PLACA_BUS']) ?></td>
                            <td><?= htmlspecialchars($v['cantidad_viajes']) ?></td>
                            <td><?= $v['ultimo_viaje'] ? htmlspecialchars($v['ultimo_viaje']) : 'Sin registros' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

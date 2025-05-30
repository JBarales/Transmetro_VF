<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'buses';

$mensaje = "";
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener datos para selects
$lineas = $pdo->query("SELECT * FROM linea")->fetchAll();
$empleados = $pdo->query("SELECT DPI_Empleado, P_Nombre, P_Apellido FROM empleado")->fetchAll();
$parqueos = $pdo->query("SELECT * FROM parqueo")->fetchAll();
$estaciones = $pdo->query("SELECT * FROM estacion")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'] ?? '';
    $id_linea = $_POST['id_linea'] ?? '';
    $dpi_empleado = $_POST['dpi_empleado'] ?? '';
    $id_parqueo = $_POST['id_parqueo'] ?? '';
    $capacidad_total = $_POST['capacidad_total'] ?? '';
    $capacidad_ruta = $_POST['capacidad_ruta'] ?? '';
    $id_estacion = $_POST['id_estacion'] ?? '';

    if ($placa && $id_linea && $dpi_empleado && $id_parqueo && $capacidad_total && $capacidad_ruta && $id_estacion) {
        $stmt = $pdo->prepare("INSERT INTO bus (PLACA_BUS, ID_Linea, DPI_Empleado, ID_Parqueo, Capacidad_Total, Capacidad_Ruta, ID_Estacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$placa, $id_linea, $dpi_empleado, $id_parqueo, $capacidad_total, $capacidad_ruta, $id_estacion]);
        $mensaje = "✅ bus registrado correctamente.";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar bus</title>
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
        <h2>Registrar bus</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Línea Asociada</label>
                <select name="id_linea" class="form-select" required>
                    <option value="">Seleccione una línea</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>"><?= htmlspecialchars($l['Nombre_Linea']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>empleado Asignado</label>
                <select name="dpi_empleado" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    <?php foreach ($empleados as $e): ?>
                        <option value="<?= $e['DPI_Empleado'] ?>"><?= htmlspecialchars($e['P_Nombre'] . ' ' . $e['P_Apellido']) ?> (DPI: <?= $e['DPI_Empleado'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>parqueo</label>
                <select name="id_parqueo" class="form-select" required>
                    <option value="">Seleccione un parqueo</option>
                    <?php foreach ($parqueos as $p): ?>
                        <option value="<?= $p['ID_Parqueo'] ?>"><?= htmlspecialchars($p['Nombre_Parqueo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Capacidad Total</label>
                <input type="number" name="capacidad_total" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Capacidad en Ruta</label>
                <input type="number" name="capacidad_ruta" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Estación Inicial</label>
                <select name="id_estacion" class="form-select" required>
                    <option value="">Seleccione una estación</option>
                    <?php foreach ($estaciones as $es): ?>
                        <option value="<?= $es['ID_Estacion'] ?>"><?= htmlspecialchars($es['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

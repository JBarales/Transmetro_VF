<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'tramos';
$mensaje = "";
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener líneas, estaciones
$lineas = $pdo->query("SELECT * FROM linea")->fetchAll();
$estaciones = $pdo->query("SELECT * FROM estacion")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_linea = $_POST['id_linea'] ?? '';
    $id_origen = $_POST['id_origen'] ?? '';
    $id_destino = $_POST['id_destino'] ?? '';
    $kilometros = $_POST['kilometros'] ?? '';

    if ($id_linea && $id_origen && $id_destino && $kilometros !== '') {
        $stmt = $pdo->prepare("INSERT INTO tramo (ID_Linea, ID_Estacion_Origen, ID_Estacion_Destino, Kilometros) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_linea, $id_origen, $id_destino, $kilometros]);
        $mensaje = "✅ tramo registrado correctamente.";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar tramo</title>
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
        <h2>Registrar tramo</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Línea</label>
                <select name="id_linea" class="form-select" required>
                    <option value="">Seleccione una línea</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>"><?= htmlspecialchars($l['Nombre_Linea']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estación Origen</label>
                <select name="id_origen" class="form-select" required>
                    <option value="">Seleccione estación origen</option>
                    <?php foreach ($estaciones as $e): ?>
                        <option value="<?= $e['ID_Estacion'] ?>"><?= htmlspecialchars($e['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estación Destino</label>
                <select name="id_destino" class="form-select" required>
                    <option value="">Seleccione estación destino</option>
                    <?php foreach ($estaciones as $e): ?>
                        <option value="<?= $e['ID_Estacion'] ?>"><?= htmlspecialchars($e['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Kilómetros</label>
                <input type="number" name="kilometros" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

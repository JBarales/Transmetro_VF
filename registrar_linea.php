<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'lineas'; // ✅ Indicamos que el submenú de líneas debe quedar abierto

$mensaje = "";
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->query("SELECT * FROM catalogomunicipalidad");
$municipalidades = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $id_muni = $_POST['id_muni'] ?? '';

    if ($nombre && $id_muni) {
        $stmt = $pdo->prepare("INSERT INTO linea (Nombre_Linea, ID_Municipalidad) VALUES (?, ?)");
        $stmt->execute([$nombre, $id_muni]);
        $mensaje = "✅ Línea registrada correctamente.";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Línea</title>
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
        <h2>Registrar Línea</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nombre de la Línea</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Municipalidad Asociada</label>
                <select name="id_muni" class="form-select" required>
                    <option value="">Seleccione una municipalidad</option>
                    <?php foreach ($municipalidades as $m): ?>
                        <option value="<?= $m['ID_Municipalidad'] ?>"><?= htmlspecialchars($m['Nombre']) ?></option>
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

<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'estaciones';

$mensaje = "";
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener líneas y municipalidades para los selects
$lineas = $pdo->query("SELECT * FROM linea")->fetchAll();
$municipalidades = $pdo->query("SELECT * FROM catalogomunicipalidad")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $id_muni = $_POST['id_muni'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $usuarios = $_POST['usuarios'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $lineas_asociadas = $_POST['lineas'] ?? [];

    if ($nombre && $direccion && $id_muni && $capacidad && $usuarios && $estado && count($lineas_asociadas) > 0) {
        // Insertar la estación
        $stmt = $pdo->prepare("INSERT INTO estacion (Nombre, Direccion, ID_Municipalidad, Capacidad, Cantidad_Usuarios, Estado) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $direccion, $id_muni, $capacidad, $usuarios, $estado]);

        $id_estacion = $pdo->lastInsertId();

        // Insertar relaciones en Estacion_Linea
        $stmt = $pdo->prepare("INSERT INTO estacion_linea (ID_Estacion, ID_Linea) VALUES (?, ?)");
        foreach ($lineas_asociadas as $id_linea) {
            $stmt->execute([$id_estacion, $id_linea]);
        }

        $mensaje = "✅ Estación registrada correctamente.";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios y debes seleccionar al menos una línea.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Estación</title>
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
        <h2>Registrar Estación</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" required>
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
            <div class="mb-3">
                <label>Capacidad</label>
                <input type="number" name="capacidad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Cantidad de usuarios</label>
                <input type="number" name="usuarios" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="">Seleccione el estado</option>
                    <option value="abierta">Abierta</option>
                    <option value="cerrada">Cerrada</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Líneas Asociadas</label>
                <select name="lineas[]" class="form-select" multiple required>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>"><?= htmlspecialchars($l['Nombre_Linea']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Mantenga presionada la tecla Ctrl (Windows) o Cmd (Mac) para seleccionar varias líneas.</small>
            </div>
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'tramos';

$id = $_GET['id'] ?? null;
if (!$id) { die("ID de tramo no especificado."); }

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=login_db", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener tramo actual
$stmt = $pdo->prepare("SELECT * FROM tramo WHERE ID_Tramo = ?");
$stmt->execute([$id]);
$tramo = $stmt->fetch();
if (!$tramo) { die("tramo no encontrado."); }

// Obtener líneas y estaciones para el formulario
$lineas = $pdo->query("SELECT * FROM linea")->fetchAll();
$estaciones = $pdo->query("SELECT * FROM estacion")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_linea = $_POST['id_linea'] ?? '';
    $id_origen = $_POST['id_origen'] ?? '';
    $id_destino = $_POST['id_destino'] ?? '';
    $kilometros = $_POST['kilometros'] ?? '';

    if ($id_linea && $id_origen && $id_destino && $kilometros !== '') {
        $stmt = $pdo->prepare("UPDATE tramo SET ID_Linea=?, ID_Estacion_Origen=?, ID_Estacion_Destino=?, Kilometros=? WHERE ID_Tramo=?");
        $stmt->execute([$id_linea, $id_origen, $id_destino, $kilometros, $id]);
        $mensaje = "✅ tramo actualizado correctamente.";

        // Recargar datos actualizados
        $stmt = $pdo->prepare("SELECT * FROM tramo WHERE ID_Tramo = ?");
        $stmt->execute([$id]);
        $tramo = $stmt->fetch();
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar tramo</title>
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
        <h2>Editar tramo (ID: <?= htmlspecialchars($id) ?>)</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Línea</label>
                <select name="id_linea" class="form-select" required>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>" <?= $l['ID_Linea'] == $tramo['ID_Linea'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['Nombre_Linea']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estación Origen</label>
                <select name="id_origen" class="form-select" required>
                    <?php foreach ($estaciones as $e): ?>
                        <option value="<?= $e['ID_Estacion'] ?>" <?= $e['ID_Estacion'] == $tramo['ID_Estacion_Origen'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estación Destino</label>
                <select name="id_destino" class="form-select" required>
                    <?php foreach ($estaciones as $e): ?>
                        <option value="<?= $e['ID_Estacion'] ?>" <?= $e['ID_Estacion'] == $tramo['ID_Estacion_Destino'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Kilómetros</label>
                <input type="number" name="kilometros" class="form-control" value="<?= htmlspecialchars($tramo['Kilometros']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="tramos.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) { die("ID no especificado."); }

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->prepare("SELECT * FROM linea WHERE ID_Linea = ?");
$stmt->execute([$id]);
$linea = $stmt->fetch();
if (!$linea) { die("Línea no encontrada."); }

$municipalidades = $pdo->query("SELECT * FROM catalogomunicipalidad")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $id_muni = $_POST['id_muni'] ?? '';
    if ($nombre && $id_muni) {
        $stmt = $pdo->prepare("UPDATE linea SET Nombre_Linea = ?, ID_Municipalidad = ? WHERE ID_Linea = ?");
        $stmt->execute([$nombre, $id_muni, $id]);
        $mensaje = "✅ Línea actualizada correctamente.";
        $stmt = $pdo->prepare("SELECT * FROM linea WHERE ID_Linea = ?");
        $stmt->execute([$id]);
        $linea = $stmt->fetch();
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Línea</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h2>Editar Línea (ID: <?= htmlspecialchars($id) ?>)</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nombre de la Línea</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($linea['Nombre_Linea']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Municipalidad Asociada</label>
                <select name="id_muni" class="form-select" required>
                    <?php foreach ($municipalidades as $m): ?>
                        <option value="<?= $m['ID_Municipalidad'] ?>" <?= $m['ID_Municipalidad'] == $linea['ID_Municipalidad'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="lineas.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

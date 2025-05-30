<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) { die("ID de bus no especificado."); }

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener datos actuales del bus
$stmt = $pdo->prepare("SELECT * FROM bus WHERE ID_Bus = ?");
$stmt->execute([$id]);
$bus = $stmt->fetch();
if (!$bus) { die("bus no encontrado."); }

// Obtener líneas y parqueos para los select
$lineas = $pdo->query("SELECT * FROM linea")->fetchAll();
$parqueos = $pdo->query("SELECT * FROM parqueo")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'] ?? '';
    $id_linea = $_POST['id_linea'] ?? '';
    $id_parqueo = $_POST['id_parqueo'] ?? '';
    $estado = $_POST['estado'] ?? 'en línea'; // valor por defecto

    $stmt = $pdo->prepare("UPDATE bus SET PLACA_BUS=?, ID_Linea=?, ID_Parqueo=?, Estado=? WHERE ID_Bus=?");
    $stmt->execute([$placa, $id_linea, $id_parqueo, $estado, $id]);

    $mensaje = "✅ bus actualizado correctamente.";

    // Recargar datos actualizados
    $stmt = $pdo->prepare("SELECT * FROM bus WHERE ID_Bus = ?");
    $stmt->execute([$id]);
    $bus = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar bus</title>
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
        <h2>Editar bus (ID: <?= htmlspecialchars($id) ?>)</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($bus['PLACA_BUS']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Línea</label>
                <select name="id_linea" class="form-select" required>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>" <?= $l['ID_Linea'] == $bus['ID_Linea'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['Nombre_Linea']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>parqueo</label>
                <select name="id_parqueo" class="form-select" required>
                    <?php foreach ($parqueos as $p): ?>
                        <option value="<?= $p['ID_Parqueo'] ?>" <?= $p['ID_Parqueo'] == $bus['ID_Parqueo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['Nombre_Parqueo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="en línea" <?= $bus['Estado'] == 'en línea' ? 'selected' : '' ?>>En línea</option>
                    <option value="en parqueo" <?= $bus['Estado'] == 'en parqueo' ? 'selected' : '' ?>>En parqueo</option>
                    <option value="en taller" <?= $bus['Estado'] == 'en taller' ? 'selected' : '' ?>>En taller</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="buses.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

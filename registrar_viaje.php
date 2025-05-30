<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'viajes';
$mensaje = "";

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Obtener buses y estaciones
$buses = $pdo->query("SELECT * FROM bus")->fetchAll();
$estaciones = $pdo->query("SELECT * FROM estacion")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bus = $_POST['id_bus'] ?? '';
    $id_estacion = $_POST['id_estacion'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';

    if ($id_bus && $id_estacion && $cantidad !== '') {
        $id_usuario = $_SESSION['id_usuario']; // Asegúrate de tenerlo en la sesión

        $stmt = $pdo->prepare("INSERT INTO registro_viaje (ID_Bus, ID_Usuario, ID_Estacion, Cantidad_Usuarios) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_bus, $id_usuario, $id_estacion, $cantidad]);
        $mensaje = "✅ Registro guardado correctamente.";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar usuarios en bus (Estación)</title>
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
        <h2>Registrar Cantidad de usuarios en bus (Estación)</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>bus</label>
                <select name="id_bus" class="form-select" required>
                    <option value="">Seleccione un bus</option>
                    <?php foreach ($buses as $b): ?>
                        <option value="<?= $b['ID_Bus'] ?>"><?= htmlspecialchars($b['PLACA_BUS']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Estación</label>
                <select name="id_estacion" class="form-select" required>
                    <option value="">Seleccione estación</option>
                    <?php foreach ($estaciones as $e): ?>
                        <option value="<?= $e['ID_Estacion'] ?>"><?= htmlspecialchars($e['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Cantidad de usuarios</label>
                <input type="number" name="cantidad" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

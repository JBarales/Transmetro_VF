<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("SELECT * FROM parqueo WHERE ID_Parqueo = ?");
    $stmt->execute([$id]);
    $parqueo = $stmt->fetch();

    if (!$parqueo) {
        die("parqueo no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $capacidad = $_POST['capacidad'] ?? '';

        if ($nombre && $ubicacion && $telefono && $capacidad) {
            $stmt = $pdo->prepare("UPDATE parqueo SET Nombre_Parqueo = ?, Ubicacion = ?, Telefono = ?, Capacidad = ? WHERE ID_Parqueo = ?");
            $stmt->execute([$nombre, $ubicacion, $telefono, $capacidad, $id]);
            $mensaje = "✅ Datos actualizados correctamente.";

            $stmt = $pdo->prepare("SELECT * FROM parqueo WHERE ID_Parqueo = ?");
            $stmt->execute([$id]);
            $parqueo = $stmt->fetch();
        } else {
            $mensaje = "❌ Todos los campos son obligatorios.";
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar parqueo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { overflow-x: hidden; }
    .sidebar { min-height: 100vh; background-color: #343a40; }
    .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px 15px; }
    .sidebar a:hover { background-color: #495057; }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="row g-0">
    <?php include 'sidebar.php'; ?>

    <div class="col-md-9 col-lg-10 p-4">
        <h2>Editar parqueo (ID: <?= htmlspecialchars($id) ?>)</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre del parqueo</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($parqueo['Nombre_Parqueo']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($parqueo['Ubicacion']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="number" name="telefono" class="form-control" value="<?= htmlspecialchars($parqueo['Telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Capacidad</label>
                <input type="number" name="capacidad" class="form-control" value="<?= htmlspecialchars($parqueo['Capacidad']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="parqueos.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

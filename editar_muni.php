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

    // Obtener datos actuales
    $stmt = $pdo->prepare("SELECT * FROM catalogomunicipalidad WHERE ID_Municipalidad = ?");
    $stmt->execute([$id]);
    $municipio = $stmt->fetch();

    if (!$municipio) {
        die("Municipalidad no encontrada.");
    }

    // Procesar actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';

        if ($nombre && $direccion && $telefono) {
            $stmt = $pdo->prepare("UPDATE catalogomunicipalidad SET Nombre = ?, Direccion = ?, Telefono = ? WHERE ID_Municipalidad = ?");
            $stmt->execute([$nombre, $direccion, $telefono, $id]);
            $mensaje = "✅ Datos actualizados correctamente.";

            // Refrescar datos
            $stmt = $pdo->prepare("SELECT * FROM catalogomunicipalidad WHERE ID_Municipalidad = ?");
            $stmt->execute([$id]);
            $municipio = $stmt->fetch();
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
    <title>Editar Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <div class="card shadow p-4">
        <h4>Editar Municipalidad (ID: <?= htmlspecialchars($id) ?>)</h4>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($municipio['Nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($municipio['Direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="number" name="telefono" class="form-control" value="<?= htmlspecialchars($municipio['Telefono']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="municipalidades.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

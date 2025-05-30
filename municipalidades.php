<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $busqueda = $_GET['buscar'] ?? '';
    $sql = "SELECT * FROM catalogomunicipalidad";
    $params = [];
    if ($busqueda) {
        $sql .= " WHERE Nombre LIKE ? OR Direccion LIKE ?";
        $params = ["%$busqueda%", "%$busqueda%"];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $municipios = $stmt->fetchAll();

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Municipalidades</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        <h2>Municipalidades</h2>

        <?php if (isset($_GET['eliminado'])): ?>
            <div class="alert alert-success">Municipalidad eliminada correctamente.</div>
        <?php endif; ?>

        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o dirección" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if (count($municipios) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID Municipalidad</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($municipios as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['ID_Municipalidad']) ?></td>
                            <td><?= htmlspecialchars($m['Nombre']) ?></td>
                            <td><?= htmlspecialchars($m['Direccion']) ?></td>
                            <td><?= htmlspecialchars($m['Telefono']) ?></td>
                            <td>
                                <a href="editar_muni.php?id=<?= urlencode($m['ID_Municipalidad']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar_muni.php?id=<?= urlencode($m['ID_Municipalidad']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron municipalidades.</div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

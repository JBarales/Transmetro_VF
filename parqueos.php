<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$busqueda = $_GET['buscar'] ?? '';
$sql = "SELECT * FROM parqueo";
$params = [];
if ($busqueda) {
    $sql .= " WHERE Nombre_Parqueo LIKE ? OR Ubicacion LIKE ?";
    $params = ["%$busqueda%", "%$busqueda%"];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$parqueos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Parqueos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        <h2>Parqueos Registrados</h2>
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o ubicación" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if (count($parqueos) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parqueos as $p): ?>
                        <tr>
                            <td><?= $p['ID_Parqueo'] ?></td>
                            <td><?= htmlspecialchars($p['Nombre_Parqueo']) ?></td>
                            <td><?= htmlspecialchars($p['Ubicacion']) ?></td>
                            <td><?= htmlspecialchars($p['Telefono']) ?></td>
                            <td><?= htmlspecialchars($p['Capacidad']) ?></td>
                            <td>
                                <a href="editar_parqueo.php?id=<?= $p['ID_Parqueo'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar_parqueo.php?id=<?= $p['ID_Parqueo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron parqueos.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>


<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$busqueda = $_GET['buscar'] ?? '';

$sql = "SELECT * FROM empleado";
$params = [];
if ($busqueda) {
    $sql .= " WHERE DPI_Empleado LIKE ? OR P_Nombre LIKE ? OR P_Apellido LIKE ?";
    $params = ["%$busqueda%", "%$busqueda%", "%$busqueda%"];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$empleados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Empleados</title>
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
        <h2>Empleados</h2>
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por DPI o nombre" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if (count($empleados) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>DPI</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Licencia</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $e): ?>
                        <tr>
                            <td><?= $e['DPI_Empleado'] ?></td>
                            <td><?= $e['P_Nombre'] . ' ' . $e['S_Nombre'] . ' ' . $e['T_Nombre'] ?></td>
                            <td><?= $e['P_Apellido'] . ' ' . $e['C_Apellido'] ?></td>
                            <td><?= $e['Tipo_Licencia'] ?></td>
                            <td><?= $e['Num_Telefono'] ?></td>
                            <td>
                                <span class="badge <?= $e['Estado'] === 'Activo' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $e['Estado'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="ficha_empleado.php?dpi=<?= $e['DPI_Empleado'] ?>" class="btn btn-sm btn-info">Ver</a>
                                <a href="editar_empleado.php?dpi=<?= $e['DPI_Empleado'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar_empleado.php?dpi=<?= $e['DPI_Empleado'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron empleados.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Aseguramos que el JS de Bootstrap esté al final para que funcione collapse -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

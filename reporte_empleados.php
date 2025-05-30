
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$submenu_abierto = 'reportes';
$estado = $_GET['estado'] ?? 'Activo';

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if ($estado === 'Todos') {
        $sql = "SELECT DPI_Empleado, P_Nombre, S_Nombre, P_Apellido, C_Apellido, Num_Telefono, Estado FROM empleado";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT DPI_Empleado, P_Nombre, S_Nombre, P_Apellido, C_Apellido, Num_Telefono, Estado
                FROM empleado
                WHERE Estado = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$estado]);
    }

    $empleados = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Empleados</title>
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
        <h2>Reporte de Empleados</h2>

        <form method="GET" class="mb-3 d-flex align-items-center">
            <label class="me-2">Filtrar por Estado:</label>
            <select name="estado" class="form-select me-2" style="width: auto;">
                <option value="Activo" <?= $estado === 'Activo' ? 'selected' : '' ?>>Activos</option>
                <option value="Inactivo" <?= $estado === 'Inactivo' ? 'selected' : '' ?>>Inactivos</option>
                <option value="Todos" <?= $estado === 'Todos' ? 'selected' : '' ?>>Todos</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <a href="exportar_empleados.php?estado=<?= urlencode($estado) ?>" class="btn btn-success mb-3">Exportar a CSV</a>

        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>DPI</th>
                    <th>Nombre Completo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['DPI_Empleado']) ?></td>
                        <td><?= htmlspecialchars($e['P_Nombre'] . " " . $e['S_Nombre'] . " " . $e['P_Apellido'] . " " . $e['C_Apellido']) ?></td>
                        <td><?= htmlspecialchars($e['Num_Telefono']) ?></td>
                        <td>
                            <span class="badge <?= $e['Estado'] === 'Activo' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($e['Estado']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="reportes.php" class="btn btn-secondary mt-3">Volver a Reportes</a>
    </div>
</div>
</body>
</html>

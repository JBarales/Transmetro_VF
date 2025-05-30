<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Búsqueda opcional
$busqueda = $_GET['buscar'] ?? '';
$sql = "SELECT u.*, e.P_Nombre, e.P_Apellido FROM usuario u 
        LEFT JOIN empleado e ON u.DPI_Empleado = e.DPI_Empleado";
$params = [];

if ($busqueda) {
    $sql .= " WHERE u.Nombre LIKE ? OR u.Correo LIKE ?";
    $params = ["%$busqueda%", "%$busqueda%"];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>usuarios</title>
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
        <h2>usuarios Registrados</h2>

        <?php if (isset($_GET['eliminado'])): ?>
            <div class="alert alert-success">usuario eliminado correctamente.</div>
        <?php endif; ?>

        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if (count($usuarios) > 0): ?>
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>empleado Asociado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['ID_Usuario'] ?></td>
                            <td><?= htmlspecialchars($u['Nombre']) ?></td>
                            <td><?= htmlspecialchars($u['Correo']) ?></td>
                            <td><?= htmlspecialchars($u['Rol']) ?></td>
                            <td>
                                <?= htmlspecialchars($u['P_Nombre'] . ' ' . $u['P_Apellido']) ?><br>
                                <small>DPI: <?= htmlspecialchars($u['DPI_Empleado']) ?></small>
                            </td>
                            <td>
                                <a href="editar_usuario.php?id=<?= $u['ID_Usuario'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar_usuario.php?id=<?= $u['ID_Usuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No se encontraron usuarios.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

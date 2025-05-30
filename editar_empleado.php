<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$dpi = $_GET['dpi'] ?? '';
if (empty($dpi)) { die("❌ Error: DPI de empleado no especificado."); }

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Obtener datos actuales del empleado
    $stmt = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
    $stmt->execute([$dpi]);
    $empleado = $stmt->fetch();

    if (!$empleado) { die("❌ Error: Empleado no encontrado para DPI: " . htmlspecialchars($dpi)); }

    // Si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $p_nombre = $_POST['p_nombre'] ?? '';
        $s_nombre = $_POST['s_nombre'] ?? '';
        $t_nombre = $_POST['t_nombre'] ?? '';
        $p_apellido = $_POST['p_apellido'] ?? '';
        $c_apellido = $_POST['c_apellido'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $tipo_licencia = $_POST['tipo_licencia'] ?? '';
        $estado = $_POST['estado'] ?? 'Activo';

        $stmt = $pdo->prepare("UPDATE empleado SET P_Nombre=?, S_Nombre=?, T_Nombre=?, P_Apellido=?, C_Apellido=?, Num_Telefono=?, Tipo_Licencia=?, Estado=? WHERE DPI_Empleado=?");
        $stmt->execute([$p_nombre, $s_nombre, $t_nombre, $p_apellido, $c_apellido, $telefono, $tipo_licencia, $estado, $dpi]);

        $mensaje = "✅ Empleado actualizado correctamente.";

        // Recargar datos actualizados
        $stmt = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
        $stmt->execute([$dpi]);
        $empleado = $stmt->fetch();
    }
} catch (PDOException $e) {
    die("❌ Error de base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Empleado</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h2>Editar Empleado (DPI: <?= htmlspecialchars($dpi) ?>)</h2>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Primer Nombre</label>
                <input type="text" name="p_nombre" class="form-control" value="<?= htmlspecialchars($empleado['P_Nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Segundo Nombre</label>
                <input type="text" name="s_nombre" class="form-control" value="<?= htmlspecialchars($empleado['S_Nombre']) ?>">
            </div>
            <div class="mb-3">
                <label>Tercer Nombre</label>
                <input type="text" name="t_nombre" class="form-control" value="<?= htmlspecialchars($empleado['T_Nombre']) ?>">
            </div>
            <div class="mb-3">
                <label>Primer Apellido</label>
                <input type="text" name="p_apellido" class="form-control" value="<?= htmlspecialchars($empleado['P_Apellido']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Segundo Apellido</label>
                <input type="text" name="c_apellido" class="form-control" value="<?= htmlspecialchars($empleado['C_Apellido']) ?>">
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($empleado['Num_Telefono']) ?>">
            </div>
            <div class="mb-3">
                <label>Tipo de Licencia</label>
                <input type="text" name="tipo_licencia" class="form-control" value="<?= htmlspecialchars($empleado['Tipo_Licencia']) ?>">
            </div>
            <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="Activo" <?= $empleado['Estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= $empleado['Estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="empleados.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

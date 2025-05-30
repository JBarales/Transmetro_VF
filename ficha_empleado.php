<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$dpi = $_GET['dpi'] ?? null;

if (!$dpi) {
    die("❌ Error: DPI no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
    $stmt->execute([$dpi]);
    $empleado = $stmt->fetch();

    if (!$empleado) {
        die("❌ Error: Empleado no encontrado.");
    }

} catch (PDOException $e) {
    die("❌ Error de base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <div class="card shadow p-4">
        <h3 class="mb-4">Ficha de Empleado</h3>
        <dl class="row">
            <dt class="col-sm-4">DPI:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['DPI_Empleado']) ?></dd>

            <dt class="col-sm-4">NIT:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['NIT_Empleado']) ?></dd>

            <dt class="col-sm-4">No. Licencia:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['No_Licencia']) ?></dd>

            <dt class="col-sm-4">Tipo Licencia:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Tipo_Licencia']) ?></dd>

            <dt class="col-sm-4">Nombre Completo:</dt>
            <dd class="col-sm-8">
                <?= htmlspecialchars($empleado['P_Nombre']) . ' ' . 
                    htmlspecialchars($empleado['S_Nombre']) . ' ' .
                    htmlspecialchars($empleado['T_Nombre']) ?>
            </dd>

            <dt class="col-sm-4">Apellidos:</dt>
            <dd class="col-sm-8">
                <?= htmlspecialchars($empleado['P_Apellido']) . ' ' .
                    htmlspecialchars($empleado['C_Apellido']) ?>
            </dd>

            <dt class="col-sm-4">Fecha de Nacimiento:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Fecha_Nacimiento']) ?></dd>

            <dt class="col-sm-4">Edad:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Edad']) ?></dd>

            <dt class="col-sm-4">Teléfono:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Num_Telefono']) ?></dd>

            <dt class="col-sm-4">Escolaridad:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Escolaridad']) ?></dd>

            <dt class="col-sm-4">Contacto de Emergencia:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Contacto_Emergencia']) ?></dd>

            <dt class="col-sm-4">Teléfono de Contacto:</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($empleado['Num_Contacto_Emergencia']) ?></dd>

            <dt class="col-sm-4">Estado:</dt>
            <dd class="col-sm-8">
                <span class="badge <?= $empleado['Estado'] === 'Activo' ? 'bg-success' : 'bg-danger' ?>">
                    <?= htmlspecialchars($empleado['Estado']) ?>
                </span>
            </dd>
        </dl>

        <a href="empleados.php" class="btn btn-secondary mt-3">Volver a la Lista</a>
    </div>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$mensaje = "";

// Conexión a la base
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = $_POST['rol'] ?? 'usuario';
    $dpi_empleado = $_POST['dpi_empleado'] ?? null;

    if ($nombre && $correo && $contrasena && $dpi_empleado) {
        // Verificar que el DPI exista en empleado
        $checkDPI = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
        $checkDPI->execute([$dpi_empleado]);

        if (!$checkDPI->fetch()) {
            $mensaje = "❌ El DPI ingresado no existe en la tabla empleado.";
        } else {
            // Verificar que no exista usuario con ese correo
            $checkCorreo = $pdo->prepare("SELECT * FROM usuario WHERE Correo = ?");
            $checkCorreo->execute([$correo]);

            if ($checkCorreo->fetch()) {
                $mensaje = "⚠️ Ya existe un usuario registrado con este correo.";
            } else {
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuario (Nombre, Correo, Contraseña, Rol, DPI_Empleado) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $correo, $hash, $rol, $dpi_empleado]);

                $mensaje = "✅ usuario creado correctamente.";
            }
        }
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear usuario</title>
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
        <h2>Crear Nuevo usuario</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select">
                    <option value="usuario">usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="mb-3">
                <label>DPI del empleado Asociado</label>
                <input type="number" name="dpi_empleado" class="form-control" required>
                <small class="text-muted">Debe coincidir con un DPI existente en la tabla empleado.</small>
            </div>
            <button type="submit" class="btn btn-success">Crear usuario</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

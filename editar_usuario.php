<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("❌ Error: ID de usuario no especificado.");
}

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Obtener datos actuales del usuario
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        die("❌ Error: Usuario no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $rol = $_POST['rol'] ?? 'usuario';
        $dpi_empleado = $_POST['dpi_empleado'] ?? '';
        $nueva_password = $_POST['password'] ?? '';

        if ($nombre && $correo && $dpi_empleado) {
            // Verificar que el DPI exista en empleado
            $checkDPI = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
            $checkDPI->execute([$dpi_empleado]);

            if (!$checkDPI->fetch()) {
                $mensaje = "❌ El DPI ingresado no existe en la tabla empleado.";
            } else {
                if (!empty($nueva_password)) {
                    // Actualizar con nueva contraseña
                    $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuario SET Nombre = ?, Correo = ?, Rol = ?, DPI_Empleado = ?, Contraseña = ? WHERE ID_Usuario = ?");
                    $stmt->execute([$nombre, $correo, $rol, $dpi_empleado, $hash, $id]);
                } else {
                    // Actualizar sin cambiar contraseña
                    $stmt = $pdo->prepare("UPDATE usuario SET Nombre = ?, Correo = ?, Rol = ?, DPI_Empleado = ? WHERE ID_Usuario = ?");
                    $stmt->execute([$nombre, $correo, $rol, $dpi_empleado, $id]);
                }

                $mensaje = "✅ Usuario actualizado correctamente.";

                // Recargar datos actualizados
                $stmt = $pdo->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
                $stmt->execute([$id]);
                $usuario = $stmt->fetch();
            }
        } else {
            $mensaje = "❌ Todos los campos son obligatorios.";
        }
    }
} catch (PDOException $e) {
    die("❌ Error de base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Usuario</title>
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
        <h2>Editar Usuario (ID: <?= htmlspecialchars($id) ?>)</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['Correo']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="usuario" <?= $usuario['Rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                    <option value="admin" <?= $usuario['Rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
            <div class="mb-3">
                <label>DPI del Empleado Asociado</label>
                <input type="number" name="dpi_empleado" class="form-control" value="<?= htmlspecialchars($usuario['DPI_Empleado']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Nueva Contraseña (opcional)</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Déjalo en blanco si no quieres cambiarla.</small>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="usuarios.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

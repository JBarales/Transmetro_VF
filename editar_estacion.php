<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id_estacion = $_GET['id'] ?? '';
if (!$id_estacion) {
    die("❌ Error: ID de estación no especificado.");
}

// Conexión correcta a InfinityFree
try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// Obtener datos actuales de la estación
$stmt = $pdo->prepare("SELECT * FROM estacion WHERE ID_Estacion = ?");
$stmt->execute([$id_estacion]);
$estacion = $stmt->fetch();
if (!$estacion) {
    die("❌ Error: Estación no encontrada.");
}

// Obtener todas las líneas disponibles
$stmt = $pdo->query("SELECT * FROM linea");
$lineas_disponibles = $stmt->fetchAll();

// Obtener las líneas actualmente asociadas a esta estación
$stmt = $pdo->prepare("SELECT ID_Linea FROM estacion_linea WHERE ID_Estacion = ?");
$stmt->execute([$id_estacion]);
$lineas_actuales = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $id_muni = $_POST['id_muni'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $usuarios = $_POST['usuarios'] ?? '';
    $estado = $_POST['estado'] ?? 'abierta';
    $lineas_asociadas = $_POST['lineas'] ?? [];

    try {
        $pdo->beginTransaction();

        // Actualizar datos de la estación
        $stmt = $pdo->prepare("UPDATE estacion SET Nombre=?, Direccion=?, ID_Municipalidad=?, Capacidad=?, Cantidad_Usuarios=?, Estado=? WHERE ID_Estacion=?");
        $stmt->execute([$nombre, $direccion, $id_muni, $capacidad, $usuarios, $estado, $id_estacion]);

        // Actualizar las líneas asociadas
        $stmt = $pdo->prepare("DELETE FROM estacion_linea WHERE ID_Estacion = ?");
        $stmt->execute([$id_estacion]);

        if (!empty($lineas_asociadas)) {
            $stmt = $pdo->prepare("INSERT INTO estacion_linea (ID_Estacion, ID_Linea) VALUES (?, ?)");
            foreach ($lineas_asociadas as $id_linea) {
                $stmt->execute([$id_estacion, $id_linea]);
            }
        }

        $pdo->commit();
        $mensaje = "✅ Estación y líneas actualizadas correctamente.";

        // Recargar datos actualizados
        $stmt = $pdo->prepare("SELECT * FROM estacion WHERE ID_Estacion = ?");
        $stmt->execute([$id_estacion]);
        $estacion = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT ID_Linea FROM estacion_linea WHERE ID_Estacion = ?");
        $stmt->execute([$id_estacion]);
        $lineas_actuales = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } catch (PDOException $e) {
        $pdo->rollBack();
        $mensaje = "❌ Error al actualizar: " . $e->getMessage();
    }
}

// Obtener municipalidades para el select
$municipios = $pdo->query("SELECT ID_Municipalidad, Nombre FROM catalogomunicipalidad")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Estación</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <div class="card p-4 shadow">
        <h4>Editar Estación (ID: <?= htmlspecialchars($id_estacion) ?>)</h4>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info mt-3"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($estacion['Nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($estacion['Direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Municipalidad</label>
                <select name="id_muni" class="form-select" required>
                    <?php foreach ($municipios as $m): ?>
                        <option value="<?= $m['ID_Municipalidad'] ?>" <?= $m['ID_Municipalidad'] == $estacion['ID_Municipalidad'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Capacidad</label>
                <input type="number" name="capacidad" class="form-control" value="<?= htmlspecialchars($estacion['Capacidad']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Cantidad de Usuarios</label>
                <input type="number" name="usuarios" class="form-control" value="<?= htmlspecialchars($estacion['Cantidad_Usuarios']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="abierta" <?= $estacion['Estado'] === 'abierta' ? 'selected' : '' ?>>abierta</option>
                    <option value="cerrada" <?= $estacion['Estado'] === 'cerrada' ? 'selected' : '' ?>>cerrada</option>
                </select>
            </div>

            <!-- Sección de líneas asociadas -->
            <div class="mb-3">
                <label>Líneas Asociadas</label>
                <select name="lineas[]" class="form-select" multiple>
                    <?php foreach ($lineas_disponibles as $l): ?>
                        <option value="<?= $l['ID_Linea'] ?>" <?= in_array($l['ID_Linea'], $lineas_actuales) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['Nombre_Linea']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Usa Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples líneas.</small>
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="estaciones.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

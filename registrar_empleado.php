<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $datos = $_POST;

    try {
        $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $sql = "INSERT INTO empleado (
                    DPI_Empleado, NIT_Empleado, No_Licencia, Tipo_Licencia, 
                    P_Nombre, S_Nombre, T_Nombre, P_Apellido, C_Apellido, 
                    Fecha_Nacimiento, Edad, Num_Telefono, Escolaridad, 
                    Contacto_Emergencia, Num_Contacto_Emergencia, Estado
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $datos['dpi'], $datos['nit'], $datos['licencia'], $datos['tipo_licencia'],
            $datos['p_nombre'], $datos['s_nombre'], $datos['t_nombre'], 
            $datos['p_apellido'], $datos['c_apellido'],
            $datos['fecha'], $datos['edad'], $datos['telefono'], $datos['escolaridad'],
            $datos['contacto'], $datos['telefono_contacto'], $datos['estado']
        ]);

        $mensaje = "✅ empleado registrado exitosamente.";

    } catch (PDOException $e) {
        $mensaje = "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <div class="card p-4 shadow">
        <h4>Registro de empleado</h4>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3"><label>DPI</label><input type="number" name="dpi" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label>NIT</label><input type="text" name="nit" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label>No. Licencia</label><input type="text" name="licencia" class="form-control"></div>
                <div class="col-md-6 mb-3"><label>Tipo de Licencia</label><input type="text" name="tipo_licencia" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Primer Nombre</label><input type="text" name="p_nombre" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Segundo Nombre</label><input type="text" name="s_nombre" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Tercer Nombre</label><input type="text" name="t_nombre" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Primer Apellido</label><input type="text" name="p_apellido" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Segundo Apellido</label><input type="text" name="c_apellido" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Fecha Nacimiento</label><input type="date" name="fecha" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Edad</label><input type="number" name="edad" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Teléfono</label><input type="number" name="telefono" class="form-control"></div>
                <div class="col-md-4 mb-3"><label>Escolaridad</label><input type="text" name="escolaridad" class="form-control"></div>
                <div class="col-md-6 mb-3"><label>Contacto Emergencia</label><input type="text" name="contacto" class="form-control"></div>
                <div class="col-md-6 mb-3"><label>Teléfono de Contacto</label><input type="number" name="telefono_contacto" class="form-control"></div>
                <div class="col-md-6 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="panel.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
</body>
</html>

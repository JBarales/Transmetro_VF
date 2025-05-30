
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$estado = $_GET['estado'] ?? 'Activo';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=empleados_$estado.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['DPI', 'Nombre Completo', 'Teléfono', 'Estado']);

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

    while ($e = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nombreCompleto = $e['P_Nombre'] . " " . $e['S_Nombre'] . " " . $e['P_Apellido'] . " " . $e['C_Apellido'];
        fputcsv($output, [$e['DPI_Empleado'], $nombreCompleto, $e['Num_Telefono'], $e['Estado']]);
    }

    fclose($output);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

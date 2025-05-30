
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$estado = $_GET['estado'] ?? 'abierta';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=estaciones_$estado.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Nombre', 'Dirección', 'Capacidad', 'Usuarios', 'Estado']);

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if ($estado === 'Todos') {
        $sql = "SELECT * FROM estacion";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT * FROM estacion WHERE Estado = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$estado]);
    }

    while ($e = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [$e['ID_Estacion'], $e['Nombre'], $e['Direccion'], $e['Capacidad'], $e['Cantidad_Usuarios'], $e['Estado']]);
    }

    fclose($output);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

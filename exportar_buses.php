
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$estado = $_GET['estado'] ?? 'en línea';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=buses_$estado.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Placa', 'Línea', 'Parqueo', 'Estado']);

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro;charset=utf8mb4", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if ($estado === 'Todos') {
        $sql = "SELECT * FROM bus";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT * FROM bus WHERE Estado = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$estado]);
    }

    while ($b = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [$b['ID_Bus'], $b['PLACA_BUS'], $b['ID_Linea'], $b['ID_Parqueo'], $b['Estado']]);
    }

    fclose($output);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

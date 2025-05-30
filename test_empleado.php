<?php
// Configuración
$pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Simulamos un DPI de prueba
$dpi = '2523577600101'; // Cambia este valor al DPI real que quieras probar

$stmt = $pdo->prepare("SELECT * FROM empleado WHERE DPI_Empleado = ?");
$stmt->execute([$dpi]);
$empleado = $stmt->fetch();

if ($empleado) {
    echo "<h3>Empleado encontrado:</h3>";
    echo "<pre>";
    print_r($empleado);
    echo "</pre>";
} else {
    echo "<h3>Empleado NO encontrado para DPI: $dpi</h3>";
}
?>

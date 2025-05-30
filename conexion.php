<?php
// Cambia estos datos con los de tu base de datos
$host = 'sql307.infinityfree.com';
$port = '3306';
$dbname = 'if0_39086323_transmetro';
$user = 'if0_39086323';
$pass = 'J0c424324654';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>

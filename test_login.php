<?php
// Configuración de la base de datos
$host = 'sql307.infinityfree.com';
$port = '3306';
$dbname = 'if0_39086323_transmetro';
$user = 'if0_39086323';
$pass = 'J0c424324654';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ Conexión exitosa.<br>";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// Usuario y contraseña de prueba
$username = 'prueba@correo.com';
$password = '1234';

// Buscar el usuario en la tabla 'usuario'
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE Correo = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    echo "👤 Usuario encontrado: " . $user['Nombre'] . "<br>";
    echo "🔑 Hash guardado: " . $user['Contraseña'] . "<br>";

    // Verificar la contraseña
    if (password_verify($password, $user['Contraseña'])) {
        echo "✅ Contraseña correcta.";
    } else {
        echo "❌ Contraseña incorrecta.";
    }
} else {
    echo "❌ Usuario no encontrado.";
}
?>

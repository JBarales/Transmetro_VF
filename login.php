<?php
session_start();

$host = 'sql307.infinityfree.com';
$dbname = 'if0_39086323_transmetro';
$user = 'if0_39086323';
$pass = 'J0c424324654';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buscar al usuario por correo
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Correo = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Contraseña'])) {
        // Credenciales correctas
        $_SESSION['usuario'] = $user['Nombre'];
        $_SESSION['rol'] = $user['Rol'];

        // Redirigir según el rol
        if ($_SESSION['rol'] === 'admin') {
            header("Location: panel.php"); // Tu panel de administrador
        } else {
            header("Location: panel_usuario.php"); // Tu panel de usuario normal
        }
        exit();
    } else {
        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #f5f5f5; }
    .login-container { max-width: 400px; margin: auto; margin-top: 100px; }
</style>
</head>
<body>
<div class="login-container">
    <div class="card p-4 shadow">
        <h4 class="mb-4 text-center">Iniciar Sesión</h4>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-danger"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Correo</label>
                <input type="email" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>

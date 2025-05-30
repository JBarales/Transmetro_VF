<?php
session_start();
if (isset($_SESSION['usuario'])) {
    // Si ya está logueado, redirige al panel
    if ($_SESSION['rol'] === 'admin') {
        header("Location: panel.php");
    } else {
        header("Location: panel_usuario.php");
    }
    exit();
}

$mensaje = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin: auto; margin-top: 10%; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="card shadow p-4">
        <h4 class="mb-4 text-center">Iniciar Sesión</h4>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Correo</label>
                <input type="email" id="username" name="username" class="form-control" required placeholder="correo@dominio.com" autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="********">
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>

<?php
// Configura estos datos:
$nombre_admin = "Administrador";
$correo_admin = "admin@correo.com";
$contrasena_admin = "admin"; // Contraseña en texto plano
$rol_admin = "admin";
$dpi_empleado = 2523577600101; // Cambia este DPI al de un empleado existente

try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=login_db", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Verificar si el usuario ya existe
    $check = $pdo->prepare("SELECT * FROM usuario WHERE Correo = ?");
    $check->execute([$correo_admin]);

    if ($check->fetch()) {
        echo "⚠️ El usuario admin ya existe con este correo.";
    } else {
        $hash = password_hash($contrasena_admin, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuario (Nombre, Correo, Contraseña, Rol, DPI_Empleado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre_admin, $correo_admin, $hash, $rol_admin, $dpi_empleado]);

        echo "✅ usuario administrador creado correctamente.";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>


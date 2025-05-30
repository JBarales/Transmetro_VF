<?php
/**
 * Script para crear un usuario de prueba:
 * Correo: prueba@correo.com
 * Contraseña: 1234
 * Nombre: usuario Prueba
 * Rol: admin
 * Asociado a un DPI_Empleado existente.
 */

// CONFIGURA: coloca un DPI válido que ya exista en la tabla empleado
$dpi_empleado = 2523577600101; // Ajusta este valor

$nombre = "usuario Prueba";
$correo = "prueba@correo.com";
$contrasena = "1234";
$rol = "admin";

try {
    $pdo = new PDO("mysql:host=sql307.infinityfree.com;port=3306;dbname=if0_39086323_transmetro", "if0_39086323", "J0c424324654", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Verifica si ya existe
    $check = $pdo->prepare("SELECT * FROM usuario WHERE Correo = ?");
    $check->execute([$correo]);

    if ($check->fetch()) {
        echo "⚠️ El usuario con correo $correo ya existe.<br>";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuario (Nombre, Correo, Contraseña, Rol, DPI_Empleado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $hash, $rol, $dpi_empleado]);

        echo "✅ usuario de prueba creado exitosamente.<br>";
        echo "Correo: $correo<br>";
        echo "Contraseña: $contrasena<br>";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

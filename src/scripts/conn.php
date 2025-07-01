<?php
// Configuración de conexión
$host = "localhost";
$dbname = "c2780502_pcdb";
$username = "c2780502_pcdb";
$password = "ru03radoBE";

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve los resultados como array asociativo
        PDO::ATTR_EMULATE_PREPARES => false, // Evita la emulación de consultas preparadas
    ]);
} catch (PDOException $e) {
    // Captura errores de conexión
    die("Error de conexión: " . $e->getMessage());
}
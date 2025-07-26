<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM atributos");
        $stmt->execute();

        $atributos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Atributos listados correctamente.", "data" => $atributos]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar los atributos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

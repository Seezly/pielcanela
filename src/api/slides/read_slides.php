<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $stmt = $pdo->prepare("SELECT * FROM slides ORDER BY id DESC");
        $stmt->execute();
        $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "message" => "Slides listados correctamente.", "data" => $slides]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar los slides: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>

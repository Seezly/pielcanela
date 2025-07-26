<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id WHERE p.id = ?");
        $stmt->execute([$_GET['id']]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Producto listado correctamente.", "data" => $producto]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar el producto: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

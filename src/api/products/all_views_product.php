<?php
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT SUM(visitas), COUNT(id) FROM productos");
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Visitas listadas correctamente.", "data" => $productos]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar las visitas: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

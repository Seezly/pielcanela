<?php
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM categorias");
        $stmt->execute();

        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Categorías listadas correctamente.", "data" => $categorias]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar las categorías: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

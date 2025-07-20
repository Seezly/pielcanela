<?php
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT subcategorias.id, subcategorias.nombre, categorias.nombre AS c_nombre FROM subcategorias JOIN categorias ON subcategorias.id_categoria = categorias.id");
        $stmt->execute();

        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Subcategorías listadas correctamente.", "data" => $categorias]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar las subcategorías: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

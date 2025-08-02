<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Consulta con LEFT JOIN para incluir categorías sin subcategorías
        $stmt = $pdo->prepare("
            SELECT
                c.id AS categoria_id,
                c.nombre AS categoria_nombre,
                c.imagen AS categoria_imagen,
                s.id AS subcategoria_id,
                s.nombre AS subcategoria_nombre
            FROM categorias AS c
            LEFT JOIN subcategorias AS s ON c.id = s.id_categoria
            ORDER BY c.id
        ");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar en estructura deseada
        $categorias = [];

        foreach ($rows as $row) {
            $catId = $row['categoria_id'];

            if (!isset($categorias[$catId])) {
                $categorias[$catId] = [
                    'id' => $catId,
                    'nombre' => $row['categoria_nombre'],
                    'imagen' => !empty($row['categoria_imagen']) ? BASE_URL . ltrim($row['categoria_imagen'], '/') : null,
                    'subcategorias' => []
                ];
            }

            if (!empty($row['subcategoria_id'])) {
                $categorias[$catId]['subcategorias'][] = [
                    'id' => $row['subcategoria_id'],
                    'nombre' => $row['subcategoria_nombre']
                ];
            }
        }

        // Reindexar a array plano
        $data = array_values($categorias);

        echo json_encode([
            "status" => "success",
            "message" => "Categorías y subcategorías listadas correctamente.",
            "data" => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al listar las categorías y subcategorías: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido."
    ]);
}

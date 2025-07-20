<?php
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM subcategorias WHERE id_categoria = :id_categoria");
        $stmt->execute(["id_categoria" => $_GET['id_categoria']]);

        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "message" => "Subcategorías listadas correctamente.", "data" => $categorias]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar las subcategorías: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

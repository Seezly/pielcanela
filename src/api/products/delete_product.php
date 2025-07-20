<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';

// Verifica si la solicitud es POST
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
) {

    $data = json_decode(file_get_contents("php://input"), true);
    $id = trim($data["id"] ?? "");
    $token = $data["csrf_token"] ?? "";

    if (!validate_csrf_token($token)) {
        echo json_encode([
            "status" => "error",
            "message" => "Token CSRF inválido."
        ]);
        exit;
    }

    if (empty($id)) {
        echo json_encode([
            "status" => "error",
            "message" => "El id es obligatorio."
        ]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id=:id");
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Producto eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al eliminar el producto: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

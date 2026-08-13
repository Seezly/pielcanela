<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';
require '../../scripts/require_auth.php';
require_admin_privileges();

// Verifica si la solicitud es POST
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
) {

    $data = json_decode(file_get_contents("php://input"), true);
    $token = $data["csrf_token"] ?? "";
    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }
    $id = trim($data["id"] ?? "");

    if (empty($id)) {
        echo json_encode([
            "status" => "error",
            "message" => "El id es obligatorio."
        ]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id=:id");
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Categoría eliminada correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al eliminar la categoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

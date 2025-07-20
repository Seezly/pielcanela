<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php';
require '../../scripts/csrf.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = trim($data['id'] ?? '');
    $token = $data['csrf_token'] ?? '';

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "El id es obligatorio."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM slides WHERE id=:id");
        $stmt->execute(['id' => $id]);
        echo json_encode(["status" => "success", "message" => "Slide eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al eliminar el slide: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>

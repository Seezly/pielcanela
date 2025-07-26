<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $data = json_decode(file_get_contents("php://input"), true);
    $nombre = trim($data["nombre"] ?? "");
    $id = trim($data["id"] ?? "");
    $destacado = trim($data["destacado"] ?? "");
    $token = $data["csrf_token"] ?? "";

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    // Validación básica
    if (empty($nombre)) {
        echo json_encode(["status" => "error", "message" => "El nombre es obligatorio."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("UPDATE categorias SET nombre=:nombre, destacado=:destacado WHERE id=:id");
        $stmt->execute(["nombre" => $nombre, "id" => $id, "destacado" => $destacado]);

        echo json_encode(["status" => "success", "message" => "Categoría editada correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar la categoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

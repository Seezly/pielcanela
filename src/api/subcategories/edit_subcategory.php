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
    $categoria = trim($data["categoria"] ?? "");
    $id = trim($data["id"] ?? "");
    $token = $data["csrf_token"] ?? "";

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    // Validación básica
    if (empty($nombre) || empty($categoria) || empty($id)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("UPDATE subcategorias SET nombre=:nombre, id_categoria=:categoria WHERE id=:id");
        $stmt->execute(["nombre" => $nombre, "categoria" => $categoria, "id" => $id]);

        echo json_encode(["status" => "success", "message" => "Subcategoría editada correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar la subcategoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

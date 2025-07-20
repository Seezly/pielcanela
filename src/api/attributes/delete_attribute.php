<?php
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
) {

    $data = json_decode(file_get_contents("php://input"), true);
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
        $stmt = $pdo->prepare("DELETE FROM atributos WHERE id=:id");
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Atributo eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al eliminar el atributo: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

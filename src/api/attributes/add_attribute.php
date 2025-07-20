<?php
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $data = json_decode(file_get_contents("php://input"), true);
    $atributo = trim($data["atributo"] ?? "");

    // Validación básica
    if (empty($atributo)) {
        echo json_encode(["status" => "error", "message" => "El atributo es obligatorio."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("INSERT INTO atributos (atributo) VALUES (:atributo)");
        $stmt->execute(["atributo" => $atributo]);

        echo json_encode(["status" => "success", "message" => "Atributo agregado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al agregar el atributo: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

<?php
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $data = json_decode(file_get_contents("php://input"), true);
    $nombre = trim($data["nombre"] ?? "");
    $categoria = trim($data["categoria"] ?? "");

    // Validación básica
    if (empty($nombre) || empty($categoria)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("INSERT INTO subcategorias (id_categoria, nombre) VALUES (:categoria, :nombre)");
        $stmt->execute(["categoria" => $categoria, "nombre" => $nombre]);

        echo json_encode(["status" => "success", "message" => "Subcategoría agregada correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al agregar la subcategoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

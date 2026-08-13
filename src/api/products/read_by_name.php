<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $nombre = trim($_GET["name"] ?? "");
        if ($nombre === "") {
            echo json_encode(["status" => "success", "message" => "Productos listados correctamente.", "data" => []]);
            exit;
        }

        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT id, sku, nombre, descripcion, precio, precioD, descuento, imagen FROM productos WHERE nombre LIKE :nombre ORDER BY visitas DESC LIMIT 8");
        $stmt->bindValue(":nombre", "%" . $nombre . "%");
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productos as &$producto) {
            if (isset($producto['descripcion'])) {
                $producto['descripcion'] = htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8');
            }
        }
        unset($producto);

        echo json_encode(["status" => "success", "message" => "Productos listados correctamente.", "data" => $productos]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar los productos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

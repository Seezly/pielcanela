<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Obtener página actual y cantidad de productos por página
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $limite = 10; // Productos por página
        $offset = ($pagina - 1) * $limite;

        // Obtener el total de productos
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total FROM productos");
        $stmtTotal->execute();
        $totalProductos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Calcular el total de páginas
        $totalPaginas = ceil($totalProductos / $limite);

        // Consulta para obtener productos paginados
        $stmt = $pdo->prepare("SELECT p.id, p.sku, p.nombre, p.descripcion, c.nombre AS categoria, p.precio, p.descuento, p.porcentajeD, p.precioD, p.imagen, p.visitas 
                               FROM productos AS p 
                               JOIN categorias AS c ON p.categoria = c.id
                               LIMIT :limite OFFSET :offset");

        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "status" => "success",
            "message" => "Productos listados correctamente.",
            "data" => $productos,
            "totalPaginas" => $totalPaginas,
            "paginaActual" => $pagina
        ]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al listar los productos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

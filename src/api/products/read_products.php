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

        // Término de búsqueda (nombre o sku)
        $buscar = trim($_GET['buscar'] ?? '');

        $where = "";
        $params = [];
        if ($buscar !== "") {
            $where = "WHERE p.nombre LIKE :buscar_nombre OR p.sku LIKE :buscar_sku";
            $params[':buscar_nombre'] = "%" . $buscar . "%";
            $params[':buscar_sku'] = "%" . $buscar . "%";
        }

        // Obtener el total de productos (aplicando el filtro de búsqueda)
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total FROM productos AS p $where");
        foreach ($params as $clave => $valor) {
            $stmtTotal->bindValue($clave, $valor);
        }
        $stmtTotal->execute();
        $totalProductos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Calcular el total de páginas
        $totalPaginas = max(1, ceil($totalProductos / $limite));
        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }
        $offset = ($pagina - 1) * $limite;

        // Consulta para obtener productos paginados
        $stmt = $pdo->prepare("SELECT p.id, p.sku, p.nombre, p.descripcion, c.nombre AS categoria, p.precio, p.descuento, p.porcentajeD, p.precioD, p.imagen, p.visitas 
                               FROM productos AS p 
                               LEFT JOIN categorias AS c ON p.categoria = c.id
                               $where
                               LIMIT :limite OFFSET :offset");

        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor);
        }
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

<?php
require_once __DIR__ . '/../config/config.php';
require 'conn.php'; // Conexión a la base de datos

$id = $_GET['id'] ?? "";
$price = $_GET['price'] ?? "";
$featured = $_GET['featured'] ?? "";
$discount = $_GET['discount'] ?? "";
$page = $_GET['page'] ?? 1;
$limit = 16;
$offset = ($page - 1) * $limit;

if (!ctype_digit((string) $id)) {
    http_response_code(400);
    echo "<p>Parámetro inválido.</p>";
    exit;
}

if (!empty($price) && $price === "asc") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY precio ASC LIMIT ? OFFSET ?";
} elseif (!empty($price) && $price === "desc") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY precio DESC LIMIT ? OFFSET ?";
} elseif (!empty($featured) && $featured === "1") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY p.destacado DESC, p.visitas DESC LIMIT ? OFFSET ?";
} elseif (!empty($discount) && $discount === "1") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? AND descuento = 1 ORDER BY precioD ASC LIMIT ? OFFSET ?";
} else {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? LIMIT ? OFFSET ?";
}

$sqlProd = $pdo->prepare($statement);
$sqlProd->execute([$id, $limit, $offset]);
$productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    $producto["imagen"] = explode(',', $producto["imagen"]);
?>
    <a href="<?= BASE_URL ?>producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" id="<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" class="producto">
        <div class="box-img">
            <img src="<?= BASE_URL ?><?php if (is_array($producto["imagen"])) echo htmlspecialchars(ltrim($producto["imagen"][0], '/'), ENT_QUOTES, 'UTF-8');
                        else echo htmlspecialchars(ltrim($producto["imagen"], '/'), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" alt="<?= htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="producto-info">
            <p><?= htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="producto-precio">
            <p class="<?php if ($producto["descuento"] > 0) echo "midline"; ?>">$ <?= htmlspecialchars($producto["precio"], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if ($producto["descuento"] > 0) {
                echo "<p>$ " . htmlspecialchars($producto['precioD'], ENT_QUOTES, 'UTF-8') . "</p>";
            } ?>
        </div>
    </a>
<?php
}

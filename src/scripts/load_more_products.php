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

$esBusqueda = !ctype_digit((string) $id);

if ($esBusqueda) {
    $nombre = $id;

    if (!empty($price) && $price === "asc") {
        $statement = "SELECT * FROM productos WHERE nombre LIKE ? ORDER BY precio ASC LIMIT ? OFFSET ?";
    } else if (!empty($price) && $price === "desc") {
        $statement = "SELECT * FROM productos WHERE nombre LIKE ? ORDER BY precio DESC LIMIT ? OFFSET ?";
    } else if (!empty($featured) && $featured === "1") {
        $statement = "SELECT * FROM productos WHERE nombre LIKE ? ORDER BY destacado DESC, visitas DESC LIMIT ? OFFSET ?";
    } else if (!empty($discount) && $discount === "1") {
        $statement = "SELECT * FROM productos WHERE nombre LIKE ? AND descuento = 1 ORDER BY precioD ASC LIMIT ? OFFSET ?";
    } else {
        $statement = "SELECT * FROM productos WHERE nombre LIKE ? LIMIT ? OFFSET ?";
    }

    $sqlProd = $pdo->prepare($statement);
    $sqlProd->execute(["%$nombre%", $limit, $offset]);
} else {
    if (!empty($price) && $price === "asc") {
        $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY precio ASC LIMIT ? OFFSET ?";
    } else if (!empty($price) && $price === "desc") {
        $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY precio DESC LIMIT ? OFFSET ?";
    } else if (!empty($featured) && $featured === "1") {
        $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY destacado DESC, visitas DESC LIMIT ? OFFSET ?";
    } else if (!empty($discount) && $discount === "1") {
        $statement = "SELECT * FROM productos WHERE categoria = ? AND descuento = 1 ORDER BY precioD ASC LIMIT ? OFFSET ?";
    } else {
        $statement = "SELECT * FROM productos WHERE categoria = ? LIMIT ? OFFSET ?";
    }

    $sqlProd = $pdo->prepare($statement);
    $sqlProd->execute([$id, $limit, $offset]);
}

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

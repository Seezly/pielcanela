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

if (!empty($price) && $price === "asc") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY price ASC LIMIT ? OFFSET ?";
} else if (!empty($price) && $price === "desc") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY price DESC LIMIT ? OFFSET ?";
} else if (!empty($featured) && $featured === "1") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY destacado DESC, visitas DESC LIMIT ? OFFSET ?";
} else if (!empty($discount) && $discount === "1") {
    $statement = "SELECT * FROM productos WHERE categoria = ? AND descuento = 1 ORDER BY precioD ASC LIMIT ? OFFSET ?";
} else {
    $statement = "SELECT * FROM productos WHERE categoria = ? LIMIT ? OFFSET ?";
}

$sqlProd = $pdo->prepare($statement);
$sqlProd->execute([$id, $limit, $offset]);
$productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    $producto["imagen"] = explode(',', $producto["imagen"]);
?>
    <a href="<?= BASE_URL ?>producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<?= $producto["id"]; ?>" id="<?= $producto["id"]; ?>" class="producto">
        <div class="box-img">
            <img src="<?php if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                        else echo $producto["imagen"]; ?>" loading="lazy" alt="<?= $producto["nombre"]; ?>">
        </div>
        <div class="producto-info">
            <p><?= $producto["nombre"]; ?></p>
            <div class="producto-precio">
                <p class="<?php if ($producto["descuento"] > 0) echo "midline"; ?>">$ <?= $producto["precio"]; ?></p>
                <?php if ($producto["descuento"] > 0) {
                    echo "<p>$ {$producto['precioD']}</p>";
                } ?>
            </div>
        </div>
    </a>
<?php
}

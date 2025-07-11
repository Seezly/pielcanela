<?php
require 'conn.php'; // Conexión a la base de datos

$id = $_GET['id'] ?? "";
$price = $_GET['price'] ?? "";
$featured = $_GET['featured'] ?? "";
$page = $_GET['page'] ?? 1;
$limit = 16;
$offset = ($page - 1) * $limit;

if (!empty($price) && $price === "asc") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY price ASC LIMIT ? OFFSET ?";
} else if (!empty($price) && $price === "desc") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY price DESC LIMIT ? OFFSET ?";
} else if (!empty($featured) && $featured === "1") {
    $statement = "SELECT * FROM productos WHERE categoria = ? ORDER BY visitas DESC LIMIT ? OFFSET ?";
} else {
    $statement = "SELECT * FROM productos WHERE categoria = ? LIMIT ? OFFSET ?";
}

$sqlProd = $pdo->prepare($statement);
$sqlProd->execute([$id, $limit, $offset]);
$productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    $producto["imagen"] = explode(',', $producto["imagen"]);
?>
    <a href="/producto/<?php echo preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<? echo $producto["id"]; ?>" id="<? echo $producto["id"]; ?>" class="producto">
        <div class="box-img">
            <img src="<? if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                        else echo $producto["imagen"]; ?>" loading="lazy" alt="<? echo $producto["nombre"]; ?>">
        </div>
        <div class="producto-info">
            <p><? echo $producto["nombre"]; ?></p>
            <div class="producto-precio">
                <p class="<? if ($producto["descuento"] > 0) echo "midline"; ?>">$ <? echo $producto["precio"]; ?></p>
                <? if ($producto["descuento"] > 0) {
                    echo "<p>$ {$producto['precioD']}</p>";
                } ?>
            </div>
        </div>
    </a>
<?php
}

<?php
require 'conn.php'; // Conexión a la base de datos

$id = $_GET['id'] ?? "";
$price = $_GET['price'] ?? "";
$featured = $_GET['featured'] ?? "";
$page = $_GET['page'] ?? 1;
$limit = 16;
$offset = ($page - 1) * $limit;

if (!empty($price) && $price === "asc") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY precio ASC LIMIT 16";
} elseif (!empty($price) && $price === "desc") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY precio DESC LIMIT 16";
} elseif (!empty($featured) && $featured === "1") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? ORDER BY visitas DESC LIMIT 16";
} elseif (!empty($discount) && $discount === "1") {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? AND descuento = 1 ORDER BY precioD ASC LIMIT 16";
} else {
    $statement = "SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id JOIN subcategorias AS s ON p.subcategoria = s.id AND p.categoria = s.id_categoria WHERE s.id = ? LIMIT 16";
}

$sqlProd = $pdo->prepare($statement);
$sqlProd->execute([$id, $limit, $offset]);
$productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    $producto["imagen"] = explode(',', $producto["imagen"]);
?>
    <a href="/producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<?= $producto["id"]; ?>" id="<?= $producto["id"]; ?>" class="producto">
        <div class="box-img">
            <img src="<?php if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                        else echo $producto["imagen"]; ?>" loading="lazy" alt="<?= $producto["nombre"]; ?>">
        </div>
        <div class="producto-info">
            <p><?= $producto["nombre"]; ?></p>
        </div>
        <div class="producto-precio">
            <p class="<?php if ($producto["descuento"] > 0) echo "midline"; ?>">$ <?= $producto["precio"]; ?></p>
            <?php if ($producto["descuento"] > 0) {
                echo "<p>$ {$producto['precioD']}</p>";
            } ?>
        </div>
    </a>
<?php
}

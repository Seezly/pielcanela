<?php
require '../src/scripts/conn.php'; // Conexión a la base de datos

$id = $_GET['id'] ?? "";
$price = $_GET['price'] ?? "";
$featured = $_GET['featured'] ?? "";
$discount = $_GET['discount'] ?? "";

if (empty($id)) {
    header("Location: /");
    exit();
}

require '../src/scripts/allVisits.php';

$title = "Piel Canela | Categorías";
$description = "Explora nuestras categorías en SK. Encuentra una amplia selección de productos de alta calidad para realzar tu belleza. ¡Compra ahora y descubre tu nuevo favorito!";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <? include_once('../src/components/head.php'); ?>
</head>

<body>
    <? include_once('../src/components/header.php'); ?>
    <? include_once('../src/components/nav.php'); ?>

    <main>
        <section class="categoria-list">
            <?
            $sql = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
            $sql->execute([$id]);
            $categoria = $sql->fetch(PDO::FETCH_ASSOC);
            ?>
            <h2><? echo $categoria["nombre"] ?? "No existe la categoría que estás buscando"; ?></h2>
            <div class="filter-icon">
                <div class="filters">
                    <a href="/categoria/<? echo strtolower($categoria["nombre"]); ?>?id=<? echo $id; ?>&price=asc" class="filter <? if ($price === "asc") echo "active"; ?>">Menor precio</a>
                    <a href="/categoria/<? echo strtolower($categoria["nombre"]); ?>?id=<? echo $id; ?>&price=desc" class="filter <? if ($price === "desc") echo "active"; ?>">Mayor precio</a>
                    <a href="/categoria/<? echo strtolower($categoria["nombre"]); ?>?id=<? echo $id; ?>&featured=1" class="filter <? if ($featured === "1") echo "active"; ?>">Destacados</a>
                    <a href="/categoria/<? echo strtolower($categoria["nombre"]); ?>?id=<? echo $id; ?>&discount=1" class="filter <? if ($discount === "1") echo "active"; ?>">Descuento</a>
                </div>
                <span class="icon-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M200-160v-280h-80v-80h240v80h-80v280h-80Zm0-440v-200h80v200h-80Zm160 0v-80h80v-120h80v120h80v80H360Zm80 440v-360h80v360h-80Zm240 0v-120h-80v-80h240v80h-80v120h-80Zm0-280v-360h80v360h-80Z" />
                    </svg>
                </span>
            </div>

            <div class="productos">
                <div id="productos" data-category="<? echo $categoria["id"]; ?>" class="productos-list">
                    <?php
                    // Construcción del query SQL
                    if ($price === "asc") {
                        $statement = "SELECT p.*, a.atributo FROM productos p JOIN atributos a ON p.atributo_id = a.id WHERE categoria = ? ORDER BY precio ASC LIMIT 16";
                    } elseif ($price === "desc") {
                        $statement = "SELECT p.*, a.atributo FROM productos p JOIN atributos a ON p.atributo_id = a.id WHERE categoria = ? ORDER BY precio DESC LIMIT 16";
                    } elseif ($featured === "1") {
                        $statement = "SELECT p.*, a.atributo FROM productos p JOIN atributos a ON p.atributo_id = a.id WHERE categoria = ? ORDER BY visitas DESC LIMIT 16";
                    } elseif ($discount === "1") {
                        $statement = "SELECT p.*, a.atributo FROM productos p JOIN atributos a ON p.atributo_id = a.id WHERE categoria = ? AND descuento = 1 ORDER BY precioD ASC LIMIT 16";
                    } else {
                        $statement = "SELECT p.*, a.atributo FROM productos p JOIN atributos a ON p.atributo_id = a.id WHERE categoria = ? LIMIT 16";
                    }

                    $sqlProd = $pdo->prepare($statement);
                    $sqlProd->execute([$id]);

                    $hayProductos = false;
                    while ($producto = $sqlProd->fetch(PDO::FETCH_ASSOC)) {
                        $hayProductos = true;
                        $producto["imagen"] = explode(',', $producto["imagen"]);
                    ?>
                        <a href="/producto/<?php echo preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<? echo $producto["id"]; ?>" id="<? echo $producto["id"]; ?>" class="producto">
                            <div class="box-img">
                                <div class="icons">
                                    <span class="icon" data-product="see" data-id="<? echo $producto["id"]; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                            <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                        </svg>
                                    </span>
                                    <span class="icon" data-product="cart"
                                        data-id="<?= $producto["id"]; ?>"
                                        data-sku="<?= $producto["sku"]; ?>"
                                        data-name="<?= $producto["nombre"]; ?>"
                                        data-price="<?= $producto["precio"]; ?>"
                                        data-priceD="<?= $producto["precioD"]; ?>"
                                        data-image="<?= $producto["imagen"][0] ?? ""; ?>"
                                        data-attribute="<?= $producto["atributo"]; ?>"
                                        data-option="<?= explode(',', $producto["opciones"])[0] ?? ""; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                            <path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                                        </svg>
                                    </span>
                                </div>
                                <img src="<?= $producto["imagen"][0]; ?>" loading="lazy" alt="<?= $producto["nombre"]; ?>">
                            </div>
                            <div class="producto-info">
                                <p><?= $producto["nombre"]; ?></p>
                                <div class="producto-precio">
                                    <p class="<?= ($producto["descuento"] > 0) ? "midline" : ""; ?>">$ <?= $producto["precio"]; ?></p>
                                    <? if ($producto["descuento"] > 0) echo "<p>$ {$producto['precioD']}</p>"; ?>
                                </div>
                            </div>
                        </a>
                    <?php
                    }

                    if (!$hayProductos) {
                        echo "<p>No hay productos en esta categoría</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <? include_once('../src/components/footer.php'); ?>
    <? include_once('../src/components/modals.php'); ?>
</body>

</html>
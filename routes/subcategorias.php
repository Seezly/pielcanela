<?php
require_once __DIR__ . '/../src/config/config.php';
require '../src/scripts/conn.php'; // Conexión a la base de datos

$id = $_GET['id'] ?? "";
$id_s = $_GET['id_s'] ?? "";
$price = $_GET['price'] ?? "";
$featured = $_GET['featured'] ?? "";
$discount = $_GET['discount'] ?? "";

if (empty($id)) {
    header("Location: /");
    exit();
}

require '../src/scripts/allVisits.php';

$title = "Piel Canela | Categorías";
$description = "Explora nuestras subcategorías en SK. Encuentra una amplia selección de productos de alta calidad para realzar tu belleza. ¡Compra ahora y descubre tu nuevo favorito!";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once('../src/components/head.php'); ?>
</head>

<body>
    <!-- Header -->
    <?php include_once('../src/components/header.php'); ?>
    <!-- Nav -->
    <?php include_once('../src/components/nav.php'); ?>

    <!--  Principal   -->

    <main>

        <!-- Categoría -->

        <?php

        $sql = $pdo->prepare("SELECT c.nombre AS c_nom, s.nombre AS s_nom FROM categorias AS c JOIN subcategorias AS s ON c.id = s.id_categoria WHERE s.id = ?");
        $sql->execute([$id_s]);

        $categoria = $sql->fetchAll(PDO::FETCH_ASSOC);

        ?>

        <section class="categoria-list">
            <h2><?= htmlspecialchars($categoria[0]["c_nom"] . " > " . $categoria[0]["s_nom"] ?? "No existe la subcategoría que estás buscando", ENT_QUOTES, 'UTF-8'); ?></h2>
            <div class="filter-icon">
                <div class="filters">
                    <a href="<?= BASE_URL ?>categoria/<?= strtolower($categoria[0]["c_nom"]); ?>/<?= strtolower($categoria[0]["s_nom"]); ?>?id=<?= $id; ?>&id_s=<?= $id_s; ?>&price=asc" class="filter <?php if (!empty($price) && $price === "asc") echo "active"; ?>">Menor precio</a>
                    <a href="<?= BASE_URL ?>categoria/<?= strtolower($categoria[0]["c_nom"]); ?>/<?= strtolower($categoria[0]["s_nom"]); ?>?id=<?= $id; ?>&id_s=<?= $id_s; ?>&price=desc" class="filter <?php if (!empty($price) && $price === "desc") echo "active"; ?>">Mayor precio</a>
                    <a href="<?= BASE_URL ?>categoria/<?= strtolower($categoria[0]["c_nom"]); ?>/<?= strtolower($categoria[0]["s_nom"]); ?>?id=<?= $id; ?>&id_s=<?= $id_s; ?>&featured=1" class="filter <?php if (!empty($featured) && $featured === "1") echo "active"; ?>">Destacados</a>
                    <a href="<?= BASE_URL ?>categoria/<?= strtolower($categoria[0]["c_nom"]); ?>/<?= strtolower($categoria[0]["s_nom"]); ?>?id=<?= $id; ?>&id_s=<?= $id_s; ?>&discount=1" class="filter <?php if (!empty($discount) && $discount === "1") echo "active"; ?>">Descuento</a>
                </div>
                <span class="icon-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                        <path d="M200-160v-280h-80v-80h240v80h-80v280h-80Zm0-440v-200h80v200h-80Zm160 0v-80h80v-120h80v120h80v80H360Zm80 440v-360h80v360h-80Zm240 0v-120h-80v-80h240v80h-80v120h-80Zm0-280v-360h80v360h-80Z" />
                    </svg>
                </span>
            </div>
            <div class="productos">
                <div id="productos" data-category="<?= htmlspecialchars($categoria[0]["id"], ENT_QUOTES, 'UTF-8'); ?>" data-subcategory="<?= htmlspecialchars($id_s, ENT_QUOTES, 'UTF-8'); ?>" class="productos-list">
                    <?php

                    $statement = "";

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
                    $sqlProd->execute([$id_s]);

                    $productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($productos)) {
                        echo "<p>No hay productos en esta subcategoría</p>";
                    } else {

                        foreach ($productos as $producto) {
                            $producto["imagen"] = explode(',', $producto["imagen"]);
                    ?>
                            <a href="<?= BASE_URL ?>producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])); ?>?id=<?= $producto["id"]; ?>" id="<?= $producto["id"]; ?>" class="producto">
                                <div class="box-img">
                                    <div class="icons">
                                        <span class="icon" data-product="see" data-id="<?= $producto["id"]; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                                <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                            </svg>
                                        </span>
                                        <span class="icon" data-product="cart" data-id="<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" data-sku="<?= htmlspecialchars($producto["sku"], ENT_QUOTES, 'UTF-8'); ?>" data-name="<?= htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?>" data-price="<?= htmlspecialchars($producto["precio"], ENT_QUOTES, 'UTF-8'); ?>" data-priceD="<?= htmlspecialchars($producto["precioD"], ENT_QUOTES, 'UTF-8'); ?>" data-image="<?php if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                else echo $producto["imagen"]; ?>" data-attribute="<?= htmlspecialchars($producto["atributo"], ENT_QUOTES, 'UTF-8'); ?>" data-option="<?= htmlspecialchars(explode(',', $producto["opciones"])[0], ENT_QUOTES, 'UTF-8'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                                <path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <img src="<?php if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                                                else echo $producto["imagen"]; ?>" loading="lazy" alt="<?= htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="producto-info">
                                    <p><?= htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <div class="producto-precio">
                                    <p class="<?php if ($producto["descuento"] > 0) echo "midline"; ?>">$ <?= $producto["precio"]; ?></p>
                                    <?php
                                    if ($producto["descuento"] > 0) {
                                        echo "<p>$ {$producto['precioD']}</p>";
                                    }
                                    ?>
                                </div>
                            </a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <?php include_once('../src/components/footer.php'); ?>

    <!-- Modales -->
    <?php include_once('../src/components/modals.php'); ?>

</body>

</html>
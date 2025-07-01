<?php
require '../src/scripts/conn.php'; // Conexión a la base de datos

require '../src/scripts/allVisits.php';

// Incrementar el contador de visitas
$visitasP = $pdo->prepare("UPDATE productos SET visitas = visitas + 1 WHERE id = ?");
$visitasP->execute([$_GET['id']]);

$title = "Piel Canela | Producto";
$description = "Transforma tu rutina de belleza con SK. Este producto ofrece resultados visibles y de alta calidad. ¡Compra ahora y experimenta la diferencia!";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <? include_once('../src/components/head.php'); ?>
</head>

<body>
    <!-- Header -->
    <? include_once('../src/components/header.php'); ?>
    <!-- Nav -->
    <? include_once('../src/components/nav.php'); ?>

    <!--  Principal   -->

    <main>

        <!-- Producto -->

        <?

        $sql = $pdo->prepare("SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id WHERE p.id = ?");
        $sql->execute([$_GET['id']]);

        $producto = $sql->fetchAll(PDO::FETCH_ASSOC);

        $producto[0]["imagen"] = explode(',', $producto[0]["imagen"]);

        ?>

        <section class="product">
            <div class="product-img box-img">
                <img src="<? if (is_array($producto[0]["imagen"])) echo $producto[0]["imagen"][0];
                            else echo $producto[0]["imagen"]; ?>" alt="<? echo $producto[0]["nombre"]; ?>">
            </div>
            <div class="product-details">
                <h2><? echo $producto[0]["nombre"]; ?></h2>
                <div class="producto-precio">
                    <p class="<? if ($producto[0]["descuento"] > 0) echo "midline"; ?>">$ <? echo $producto[0]["precio"]; ?></p>
                    <?
                    if ($producto[0]["descuento"] > 0) {
                        echo "<p class='discount'>$ {$producto[0]["precioD"]}</p>";
                    }
                    ?>
                </div>
                <? if ($producto[0]["atributo"] !== "No") { ?>
                    <div class="product-options">
                        <div class="size-options" data-attribute="<? echo $producto[0]["atributo"]; ?>">
                            <?
                            foreach (explode(',', $producto[0]["opciones"]) as $opcion) { ?>
                                <input type="radio" id="size-<? echo $opcion; ?>" name="size" value="<? echo $opcion; ?>" data-image="<? echo $producto[0]["imagen"][array_search($opcion, explode(',', $producto[0]["opciones"]))]; ?>">
                                <label for="size-<? echo $opcion; ?>"><? echo ucfirst($opcion); ?></label>
                            <?
                            } ?>
                        </div>
                    </div>
                <? } ?>
                <div class="quantity" data-id="<? echo $producto[0]["id"]; ?>">
                    <button class="minus">-</button>
                    <button class="plus">+</button>
                    <input type="number" name="cantidad" value="1" min="1" inputmode="numeric">
                </div>
                <div class="product-cta">
                    <a href="#" class="btn" data-action="cart" data-id="<? echo $producto[0]["id"]; ?>" data-sku="<? echo $producto[0]["sku"]; ?>">Agregar al carrito</a>
                    <a href="#" class="btn comprarProducto" data-action="buy" data-id="<? echo $producto[0]["id"]; ?>">Comprar ahora</a>
                </div>
                <p><? echo $producto[0]["descripcion"]; ?></p>
            </div>
        </section>

        <!-- Otros productos -->

        <section class="productos">
            <h3>Productos recomendados</h3>
            <div class="productos-list">
                <?
                $sqlProd = $pdo->prepare("SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id LIMIT 8");
                $sqlProd->execute();

                $productos = $sqlProd->fetchAll(PDO::FETCH_ASSOC);

                foreach ($productos as $producto) {
                    $producto["imagen"] = explode(',', $producto["imagen"]);
                ?>
                    <a href="/producto/<?php echo preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])) ?>?id=<? echo $producto["id"]; ?>" id="<? echo $producto["id"]; ?>" class="producto">
                        <div class="box-img">
                            <div class="icons">
                                <span class="icon" data-product="see" data-id="<? echo $producto["id"]; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                    </svg>
                                </span>
                                <span class="icon" data-product="cart" data-id="<?= $producto["id"]; ?>" data-sku="<?= $producto["sku"]; ?>" data-name="<?= $producto["nombre"]; ?>" data-price="<? echo $producto["precio"]; ?>" data-priceD="<? echo $producto["precioD"]; ?>" data-image="<? if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                                                                                                                                                                                                                                                                                                else echo $producto["imagen"]; ?>" data-attribute="<? echo $producto["atributo"] ?>" data-option="<? echo explode(',', $producto["opciones"])[0] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
                                        <path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                                    </svg>
                                </span>
                            </div>
                            <img src="<? if (is_array($producto["imagen"])) echo $producto["imagen"][0];
                                        else echo $producto["imagen"]; ?>" alt="<? echo $producto["nombre"]; ?>">
                        </div>
                        <div class="producto-info">
                            <p><? echo $producto["nombre"]; ?></p>
                            <div class="producto-precio">
                                <p class="<? if ($producto["descuento"] > 0) echo "midline"; ?>">$ <? echo $producto["precio"]; ?></p>
                                <?
                                if ($producto["descuento"] > 0) {
                                    echo "<p>$ {$producto['precioD']}</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                <?
                }
                ?>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <? include_once('../src/components/footer.php'); ?>

    <!-- Modales -->
    <? include_once('../src/components/modals.php'); ?>
</body>

</html>
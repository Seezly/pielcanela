<?
$slider = $pdo->prepare("SELECT * FROM productos LIMIT 4");
$slider->execute();

$sliders = $slider->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="slider">
    <div class="keen-slider">
        <?
        foreach ($sliders as $slidern) {
            $slidern["imagen"] = explode(',', $slidern["imagen"]);
        ?>
            <div class="keen-slider__slide">
                <div class="slide-content">
                    <div class="product-info">
                        <p class="product-title"><? echo $slidern["nombre"]; ?></p>
                        <p class="product-description"><? echo $slidern["descripcion"]; ?></p>
                        <a href="/producto/<?php echo preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($slidern["nombre"])); ?>?id=<? echo $producto["id"]; ?>" class="btn">Ver producto</a>
                    </div>
                    <div class="product-image box-img">
                        <img src="<? if (is_array($slidern["imagen"])) echo $slidern["imagen"][0];
                                    else echo $slidern["imagen"]; ?>" loading="lazy" alt="<? echo $slidern["nombre"]; ?>">
                    </div>
                </div>
            </div>
        <?
        }
        ?>
    </div>
</section>
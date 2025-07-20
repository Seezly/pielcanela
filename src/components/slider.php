<?php
$slider = $pdo->prepare(
    "SELECT titulo, descripcion, enlace, imagen FROM slides ORDER BY id DESC LIMIT 4"
);
$slider->execute();

$slides = $slider->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="slider">
    <div class="keen-slider">
        <?php
        foreach ($slides as $slide) {
        ?>
            <a class="keen-slider__slide" href="<?= htmlspecialchars($slide['enlace'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="slide-content">
                    <div class="product-info">
                        <p class="product-title"><?= htmlspecialchars($slide['titulo'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="product-description"><?= htmlspecialchars($slide['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="product-image box-img">
                        <img src="<?= htmlspecialchars($slide['imagen'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" alt="<?= htmlspecialchars($slide['titulo'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
            </a>
        <?php
        }
        ?>
    </div>
</section>
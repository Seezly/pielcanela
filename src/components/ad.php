<?php

$stmtA = $pdo->prepare("SELECT * FROM ads WHERE id=:idAd");
$stmtA->execute(["idAd" => $idAd]);
$adsA = $stmtA->fetch(PDO::FETCH_ASSOC);

if (!$adsA) {
    return;
}

?>

<section class="ad <?= (pathinfo($adsA["imagen"], PATHINFO_EXTENSION) === 'webm') ? 'video-ad' : '' ?>">
    <a href="<?= htmlspecialchars($adsA["url"], ENT_QUOTES, 'UTF-8'); ?>" data-id="<?= htmlspecialchars($adsA["id"], ENT_QUOTES, 'UTF-8'); ?>" class="box-img">
        <?php if (pathinfo($adsA["imagen"], PATHINFO_EXTENSION) === 'webm') { ?>
            <video autoplay muted loop>
                <source src="<?= htmlspecialchars($adsA["imagen"], ENT_QUOTES, 'UTF-8'); ?>" type="video/webm">
            </video>
        <?php } else { ?>
            <img src="<?= htmlspecialchars($adsA["imagen"], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" alt="ad">
        <?php } ?>
    </a>
</section>
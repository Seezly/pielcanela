<?php

$stmtA = $pdo->prepare("SELECT * FROM ads WHERE id=:idAd");
$stmtA->execute(["idAd" => $idAd]);
$adsA = $stmtA->fetch(PDO::FETCH_ASSOC);

?>

<section class="ad <?= (pathinfo($adsA["imagen"], PATHINFO_EXTENSION) === 'webm') ? 'video-ad' : '' ?>">
    <a href="<?= $adsA["url"]; ?>" data-id="<?= $adsA["id"] ?>" class="box-img">
        <?php if (pathinfo($adsA["imagen"], PATHINFO_EXTENSION) === 'webm') { ?>
            <video autoplay muted loop>
                <source src="<?=$adsA["imagen"]; ?>" type="video/webm">
            </video>
        <?php } else { ?>
            <img src="<?=$adsA["imagen"]; ?>" loading="lazy" alt="ad">
        <?php } ?>
    </a>
</section>
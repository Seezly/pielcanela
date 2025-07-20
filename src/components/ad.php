<?php

$stmtA = $pdo->prepare("SELECT * FROM ads WHERE id=:idAd");
$stmtA->execute(["idAd" => $idAd]);
$adsA = $stmtA->fetch(PDO::FETCH_ASSOC);

?>

<section class="ad">
    <a href="<?= $adsA["url"]; ?>" data-id="<?= $adsA["id"] ?>" class="box-img">
        <img src="<?= $adsA["imagen"]; ?>" loading="lazy" alt="ad">
    </a>
</section>
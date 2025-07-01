<?php

$stmtA = $pdo->prepare("SELECT * FROM ads WHERE id=:idAd");
$stmtA->execute(["idAd" => $idAd]);
$adsA = $stmtA->fetch(PDO::FETCH_ASSOC);

?>

<section class="ad">
    <a href="<? echo $adsA["url"]; ?>" data-id="<? echo $adsA["id"] ?>" class="box-img">
        <img src="<? echo $adsA["imagen"]; ?>" loading="lazy" alt="ad">
    </a>
</section>
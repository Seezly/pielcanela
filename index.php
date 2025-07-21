<?php
require 'src/scripts/conn.php'; // Conexión a la base de datos

require 'src/scripts/allVisits.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<?php include_once('./src/components/head.php'); ?>
</head>

<body>
	<!-- Header -->
	<?php include_once('./src/components/header.php'); ?>
	<!-- Nav -->
	<?php include_once('./src/components/nav.php'); ?>
	<main>
		<!-- Hero -->
		<?php include_once('./src/components/slider.php'); ?>

		<!-- Categorías -->

		<section class="categorias">
			<?php
			$sql = $pdo->prepare("SELECT * FROM categorias WHERE destacado = 1 LIMIT 4");
			$sql->execute();

			$categorias = $sql->fetchAll(PDO::FETCH_ASSOC);

			foreach ($categorias as $categoria) {
			?>
				<a href="<?= BASE_URL ?>categoria/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($categoria["nombre"])) ?>?id=<?= $categoria["id"]; ?>" id="<?= $categoria["id"]; ?>" class="categoria">
					<div class="categoria-icon">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="36px" viewBox="0 -960 960 960" width="36px" fill="undefined">
								<path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z" />
							</svg>
						</span>
					</div>
					<p class="categoria-titulo"><?= htmlspecialchars($categoria["nombre"], ENT_QUOTES, 'UTF-8'); ?></p>
				</a>
			<?php
			}
			?>
		</section>

		<!-- AD -->

		<?php
		$idAd = 1;
		include('./src/components/ad.php');
		?>

		<!-- Productos -->

		<section class="productos">
			<div class="productos-title">
				<h2>Productos destacados</h2>
			</div>
			<div class="productos-list">
				<?php
				$sql = $pdo->prepare("SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id ORDER BY visitas DESC LIMIT 8");
				$sql->execute();

				$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

				foreach ($productos as $producto) {
					$producto["imagen"] = explode(',', $producto["imagen"]);

				?>
					<a href="<?= BASE_URL ?>producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])) ?>?id=<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" id="<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" class="producto">
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
				?>
			</div>
		</section>

		<!-- AD -->

		<?php
		$idAd = 2;
		include('./src/components/ad.php');
		?>

		<!-- Productos en Descuento -->

		<section class="productos">
			<div class="productos-title">
				<h2>Productos en <span class="midline">descuento</span></h2>
			</div>
			<div class="productos-list">
				<?php
				$sql = $pdo->prepare('SELECT p.*, a.atributo AS atributo FROM productos AS p JOIN atributos AS a ON p.atributo_id = a.id WHERE descuento=1 LIMIT 8');
				$sql->execute();

				$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

				foreach ($productos as $producto) {
					$producto["imagen"] = explode(',', $producto["imagen"]);

				?>
					<a href="<?= BASE_URL ?>producto/<?= preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($producto["nombre"])) ?>?id=<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" id="<?= htmlspecialchars($producto["id"], ENT_QUOTES, 'UTF-8'); ?>" class="producto">
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
				?>
			</div>
		</section>

		<!-- Siguenos (Instagram posts) -->

		<section class="siguenos">
			<div class="productos-title">
				<h2>Síguenos en Instagram</h2>
				<p>@pielcanelamakeup_</p>
			</div>
			<!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
			<div class="elfsight-app-338f3ed1-2994-480c-bbf6-4c97d3352688" data-elfsight-app-lazy></div>
		</section>

	</main>

	<!-- Footer -->
	<?php include_once('./src/components/footer.php'); ?>

	<!-- Modales -->
	<?php include_once('./src/components/modals.php'); ?>

	<script src="https://static.elfsight.com/platform/platform.js" async></script>
	<script src="<?= BASE_URL ?>public/js/keen-slider.js"></script>

</body>

</html>
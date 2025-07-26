<?php require_once __DIR__ . '/../src/config/config.php'; ?>
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

        <!-- Carrito -->

        <section class="cart">
            <h2>Mi carrito</h2>
            <div class="cart-items">
                <div class="cart-item">
                    <div class="box-img">
                        <img src="#" alt="Producto en carrito">
                    </div>
                    <div class="item-details">
                        <p>Nombre del Producto</p>
                        <span class="delete-item">Eliminar</span>
                        <p>Precio: $XX.XX</p>
                        <label for="quantity">Cantidad:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1">
                        <p>Total:</p>
                    </div>
                </div>
            </div>

            <div class="note">
                <label for="order-notes">Notas del pedido:</label>
                <textarea id="order-notes" name="order-notes" rows="4" cols="50" placeholder="Escribe aquí cualquier instrucción especial para tu pedido..."></textarea>
            </div>

            <div class="cart-total">
                <p>Subtotal: $XX.XX</p>
                <a href="#" class="btn">Finalizar compra</a>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <?php include_once('../src/components/footer.php'); ?>

    <!-- Modales -->
    <?php include_once('../src/components/modals.php'); ?>
</body>

</html>
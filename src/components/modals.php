<!-- Categorias menú -->

<div class="menu-categories modal">
    <span class="toggle" data-modal="menu-categories">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
    </span>
    <div class="categories-list">
        <ul>

        </ul>
    </div>
</div>

<!-- Buscador -->

<div class="search-bar modal">
    <span class="toggle" data-modal="search-bar">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
    </span>
    <form id="searchForm" action="search.php" method="GET">
        <input id="searchInput" type="text" name="query" placeholder="Buscar productos..." required>
        <button type="submit">Buscar</button>
    </form>
    <div id="results-container"></div>
</div>

<!-- Carrito slide -->

<div class="cart-preview modal">
    <span class="toggle" data-modal="cart-preview">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
    </span>
    <h2>Mi carrito</h2>
    <div class="cart-items">

    </div>
    <div class="cart-total">
        <p id="discount">Descuento: $00.00</p>
        <p id="total">Total: $00.00</p>
        <a href="#" id="comprarCarrito" class="btn">Comprar</a>
    </div>
</div>

<!-- Modal de producto -->

<div class="product-modal modal">
    <span class="toggle" data-modal="product-modal">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
    </span>
    <div class="product">
        <div class="product-img box-img">
            <img src="" alt="">
        </div>
        <div class="product-details">
            <h2></h2>
            <div class="producto-precio">
                <p></p>
            </div>
            <div class="product-options">
                <div class="size-options modal-options">

                </div>
            </div>
            <div class="quantity">
                <button class="minus">-</button>
                <button class="plus">+</button>
                <input type="number" name="cantidad" value="1" min="1" inputmode="numeric">
            </div>
            <div class="product-cta">
                <a href="#" class="btn" data-action="cart">Agregar al carrito</a>
                <a href="#" class="btn comprarProducto" data-action="buy">Comprar ahora</a>
            </div>
            <p></p>
        </div>
    </div>
</div>

<div class="checkout-modal modal">
    <span class="toggle" data-modal="checkout-modal">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
    </span>

    <div class="checkout">
        <h2>Checkout</h2>

        <form action="" id="checkoutForm">
            <input type="text" name="name" id="name" placeholder="Nombre completo" required>
            <select name="parcel" id="parcel" required>
                <option value="0">Retiro en tienda</option>
                <option value="1">Delivery a domicilio</option>
                <option value="2">Envío nacional</option>
            </select>
            <input type="text" name="address" id="address" placeholder="Dirección de entrega" required>
            <button class="btn" id="proceed">Comprar</button>
        </form>
    </div>
</div>

<div class="overlay"></div>

<div id="loader" class="loader">
    <div class="opacinner"><img src="/public/img/logo.webp" alt="logo"></div>
</div>

<script type="module" src="/public/js/index.js"></script>
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
    <div class="opacinner"><img src="<?= BASE_URL ?>public/img/logo.webp" alt="logo"></div>
</div>

<div class="wabtn" id="wabutton">
    <a wa-tooltip="¡Estamos disponibles! Presione aqui para chatear" target="_blank" href="https://wa.me/584145522227?text=%C2%A1Hola!%20Estoy%20interesado%20en%20tus%20servicios%20y%20me%20encantar%C3%ADa%20saber%20m%C3%A1s%20al%20respecto.%20%C2%BFPodr%C3%ADas%20por%20favor%20enviarme%20m%C3%A1s%20informaci%C3%B3n?%20%C2%A1Gracias!" style=" cursor: pointer;height: 48px;width: auto;padding: 7px 7px 7px 7px;position: fixed !important;color: #fff;bottom: 20px;right: 20px;;display: flex;text-decoration: none;font-size: 18px;font-weight: 600;font-family: sans-serif;align-items: center;z-index: 1 !important;background-color: #00E785;box-shadow: 4px 5px 10px rgba(0, 0, 0, 0.4);border-radius: 100px;animation: none;">
        <svg width="34" height="34" style="padding: 5px;" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_1024_354)">
                <path d="M23.8759 4.06939C21.4959 1.68839 18.3316 0.253548 14.9723 0.0320463C11.613 -0.189455 8.28774 0.817483 5.61565 2.86535C2.94357 4.91323 1.10682 7.86244 0.447451 11.1638C-0.21192 14.4652 0.351026 17.8937 2.03146 20.8109L0.0625 28.0004L7.42006 26.0712C9.45505 27.1794 11.7353 27.7601 14.0524 27.7602H14.0583C16.8029 27.7599 19.4859 26.946 21.768 25.4212C24.0502 23.8965 25.829 21.7294 26.8798 19.1939C27.9305 16.6583 28.206 13.8682 27.6713 11.1761C27.1367 8.48406 25.8159 6.01095 23.8759 4.06939ZM14.0583 25.4169H14.0538C11.988 25.417 9.96008 24.8617 8.1825 23.8091L7.7611 23.5593L3.3945 24.704L4.56014 20.448L4.28546 20.0117C2.92594 17.8454 2.32491 15.2886 2.57684 12.7434C2.82877 10.1982 3.91938 7.80894 5.67722 5.95113C7.43506 4.09332 9.76045 2.87235 12.2878 2.48017C14.8152 2.08799 17.4013 2.54684 19.6395 3.78457C21.8776 5.02231 23.641 6.96875 24.6524 9.3179C25.6638 11.6671 25.8659 14.2857 25.2268 16.7622C24.5877 19.2387 23.1438 21.4326 21.122 22.999C19.1001 24.5655 16.6151 25.4156 14.0575 25.4157L14.0583 25.4169Z" fill="#E0E0E0"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6291 7.98363C10.3723 7.41271 10.1019 7.40123 9.85771 7.39143C9.65779 7.38275 9.42903 7.38331 9.20083 7.38331C9.0271 7.3879 8.8562 7.42837 8.69887 7.5022C8.54154 7.57602 8.40119 7.68159 8.28663 7.81227C7.899 8.17929 7.59209 8.62305 7.38547 9.11526C7.17884 9.60747 7.07704 10.1373 7.08655 10.6711C7.08655 12.3578 8.31519 13.9877 8.48655 14.2164C8.65791 14.4452 10.8581 18.0169 14.3425 19.3908C17.2382 20.5327 17.8276 20.3056 18.4562 20.2485C19.0848 20.1913 20.4843 19.4194 20.7701 18.6189C21.056 17.8183 21.0557 17.1323 20.9701 16.989C20.8844 16.8456 20.6559 16.7605 20.3129 16.5889C19.9699 16.4172 18.2849 15.5879 17.9704 15.4736C17.656 15.3594 17.4275 15.3023 17.199 15.6455C16.9705 15.9888 16.3139 16.7602 16.1137 16.9895C15.9135 17.2189 15.7136 17.2471 15.3709 17.0758C14.3603 16.6729 13.4275 16.0972 12.6143 15.3745C11.8648 14.6818 11.2221 13.8819 10.7072 13.0007C10.5073 12.6579 10.6857 12.472 10.8579 12.3007C11.0119 12.1472 11.2006 11.9005 11.3722 11.7003C11.5129 11.5271 11.6282 11.3346 11.7147 11.1289C11.7603 11.0343 11.7817 10.9299 11.7768 10.825C11.7719 10.7201 11.7409 10.6182 11.6867 10.5283C11.6001 10.3566 10.9337 8.66151 10.6291 7.98363Z" fill="white"></path>
                <path d="M23.7628 4.02445C21.4107 1.66917 18.2825 0.249336 14.9611 0.0294866C11.6397 -0.190363 8.35161 0.804769 5.70953 2.82947C3.06745 4.85417 1.25154 7.77034 0.600156 11.0346C-0.051233 14.299 0.506321 17.6888 2.16894 20.5724L0.222656 27.6808L7.49566 25.7737C9.50727 26.8692 11.7613 27.4432 14.0519 27.4434H14.0577C16.7711 27.4436 19.4235 26.6392 21.6798 25.1321C23.936 23.6249 25.6947 21.4825 26.7335 18.9759C27.7722 16.4693 28.0444 13.711 27.5157 11.0497C26.9869 8.38835 25.6809 5.94358 23.7628 4.02445ZM14.0577 25.1269H14.0547C12.0125 25.1271 10.0078 24.5782 8.25054 23.5377L7.8339 23.2907L3.51686 24.4222L4.66906 20.2143L4.39774 19.7831C3.05387 17.6415 2.4598 15.1141 2.70892 12.598C2.95804 10.082 4.03622 7.72013 5.77398 5.88366C7.51173 4.04719 9.81051 2.84028 12.3089 2.45266C14.8074 2.06505 17.3638 2.5187 19.5763 3.74232C21.7888 4.96593 23.5319 6.89011 24.5317 9.21238C25.5314 11.5346 25.7311 14.1233 25.0993 16.5714C24.4675 19.0195 23.0401 21.1883 21.0414 22.7367C19.0427 24.2851 16.5861 25.1254 14.0577 25.1255V25.1269Z" fill="white"></path>
            </g>
            <defs>
                <clipPath id="clip0_1024_354">
                    <rect width="27.8748" height="28" fill="white" transform="translate(0.0625)"></rect>
                </clipPath>
            </defs>
        </svg>
        <span class="button-text"></span>
    </a>
</div>

<script type="module" src="<?= BASE_URL ?>public/js/index.js"></script>
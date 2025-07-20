<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /"); // Redirige a la página principal si no está autenticado
    exit;
}

$privilegios = $_SESSION['privilegios'];

// Verifica los privilegios del usuario
if ($privilegios !== 'administrador' && $privilegios !== 'vendedor' && $privilegios !== 'sysadmin') {
    header("Location: /"); // Redirige a la página principal si no tiene privilegios de administrador
    exit;
}

require '../../src/scripts/conn.php'; // Conexión a la base de datos
require '../../src/scripts/csrf.php';
$csrf_token = generate_csrf_token();

$id = $_GET['id'];

if (!empty($id)) {
    $stmt = $pdo->prepare("SELECT p.*, c.nombre AS categoria_nombre, a.atributo AS atributo_nombre FROM productos AS p JOIN categorias AS c ON p.categoria = c.id JOIN atributos AS a ON p.atributo_id = a.id WHERE p.id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    $nombre = $product['nombre'];
    $precio = $product['precio'];
    $descripcion = $product['descripcion'];
    $categoria = $product['categoria'];
    $categoria_nombre = $product['categoria_nombre'];
    $atributo = $product['atributo_id'];
    $atributo_nombre = $product['atributo_nombre'];
    $descuento = $product['descuento'];
    $precioD = $product['precioD'];
    $porcentajeD = $product['porcentajeD'];
    $sku = $product['sku'];
    $imagen = $product['imagen'];
    $featured = $product['destacado'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <title>Piel Canela | Productos</title>

    <meta name="robots" content="noindex, nofollow" />

    <!-- Open Graph Meta -->

    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link
        rel="shortcut icon"
        href="/public/img/favicon.ico" />
    <link
        rel="icon"
        type="image/png"
        sizes="192x192"
        href="/public/img/android-chrome-192x192.png" />
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/public/img/apple-touch-icon.png" />
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link
        rel="stylesheet"
        id="css-main"
        href="/public/css/dashmix.min.css" />

    <link rel="stylesheet" href="/public/css/dropzone.min.css">


    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="/public/css/themes/xwork.min.css"> -->
    <!-- END Stylesheets -->
</head>

<body>
    <!-- Page Container -->
    <!--
      Available classes for #page-container:

      GENERIC

        'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                    - Theme helper buttons [data-toggle="theme"],
                                                    - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                    - ..and/or Dashmix.layout('dark_mode_[on/off/toggle]')

      SIDEBAR & SIDE OVERLAY

        'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
        'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
        'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
        'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
        'sidebar-dark'                              Dark themed sidebar

        'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
        'side-overlay-o'                            Visible Side Overlay by default

        'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

        'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

      HEADER

        ''                                          Static Header if no class is added
        'page-header-fixed'                         Fixed Header


      FOOTER

        ''                                          Static Footer if no class is added
        'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

      HEADER STYLE

        ''                                          Classic Header style if no class is added
        'page-header-dark'                          Dark themed Header
        'page-header-glass'                         Light themed Header with transparency by default
                                                    (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
        'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                    (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

      MAIN CONTENT LAYOUT

        ''                                          Full width Main Content if no class is added
        'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
        'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

      DARK MODE

        'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
    -->
    <div
        id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Sidebar -->
        <!--
        Sidebar Mini Mode - Display Helper classes

        Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
          If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

        Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
        Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
        Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
        <nav id="sidebar" aria-label="Main Navigation">
            <!-- Side Header -->
            <div class="bg-header-dark">
                <div class="content-header bg-white-5">
                    <!-- Logo -->
                    <a class="fw-semibold text-white tracking-wide" href="index.html">
                        <span class="smini-visible">
                            S<span class="opacity-75">d</span>
                        </span>
                        <span class="smini-hidden">
                            Script<span class="opacity-75">dash</span>
                        </span>
                    </a>
                    <!-- END Logo -->

                    <!-- Options -->
                    <div>
                        <!-- END Toggle Sidebar Style -->

                        <!-- Dark Mode -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button
                            type="button"
                            class="btn btn-sm btn-alt-secondary"
                            data-toggle="class-toggle"
                            data-target="#dark-mode-toggler"
                            data-class="far fa"
                            onclick="Dashmix.layout('dark_mode_toggle');">
                            <i class="far fa-moon" id="dark-mode-toggler"></i>
                        </button>
                        <!-- END Dark Mode -->

                        <!-- Close Sidebar, Visible only on mobile screens -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button
                            type="button"
                            class="btn btn-sm btn-alt-secondary d-lg-none"
                            data-toggle="layout"
                            data-action="sidebar_close">
                            <i class="fa fa-times-circle"></i>
                        </button>
                        <!-- END Close Sidebar -->
                    </div>
                    <!-- END Options -->
                </div>
            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->
            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="suadview.php">
                                <i class="nav-main-link-icon fa fa-location-arrow"></i>
                                <span class="nav-main-link-name">Inicio</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Productos</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="products.php">
                                        <span class="nav-main-link-name">Productos</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="products_edit.php">
                                        <span class="nav-main-link-name">Editar productos</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="products_featured.php">
                                        <span class="nav-main-link-name">Destacar productos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Slider</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="slider.php">
                                        <span class="nav-main-link-name">Slides</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="slider_edit.php">
                                        <span class="nav-main-link-name">Editar slides</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Categorias</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="categories.php">
                                        <span class="nav-main-link-name">Categorias</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="categories_edit.php">
                                        <span class="nav-main-link-name">Editar categorias</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="categories_feature.php">
                                        <span class="nav-main-link-name">Destacar categorias</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Subcategorias</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="subcategories.php">
                                        <span class="nav-main-link-name">Subcategorias</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="subcategories_edit.php">
                                        <span class="nav-main-link-name">Editar subcategorias</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Atributos</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="attributes.php">
                                        <span class="nav-main-link-name">Atributos</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="attributes_edit.php">
                                        <span class="nav-main-link-name">Editar atributos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item">
                            <a
                                class="nav-main-link nav-main-link-submenu"
                                data-toggle="submenu"
                                aria-haspopup="true"
                                aria-expanded="true"
                                href="#">
                                <i class="nav-main-link-icon fa fa-clone"></i>
                                <span class="nav-main-link-name">Ads</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a
                                        class="nav-main-link"
                                        href="ads.php">
                                        <span class="nav-main-link-name">Ads</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php

                        if ($privilegios !== 'administrador' && $privilegios !== 'sysadmin') {
                            echo "";
                        } else {

                            echo '<li class="nav-main-item">
                                <a
                                    class="nav-main-link nav-main-link-submenu"
                                    data-toggle="submenu"
                                    aria-haspopup="true"
                                    aria-expanded="true"
                                    href="#">
                                    <i class="nav-main-link-icon fa fa-clone"></i>
                                    <span class="nav-main-link-name">Usuarios</span>
                                </a>
                                <ul class="nav-main-submenu">
                                    <li class="nav-main-item">
                                        <a
                                            class="nav-main-link"
                                            href="users.php">
                                            <span class="nav-main-link-name">Usuarios</span>
                                        </a>
                                    </li>
                                    <li class="nav-main-item">
                                        <a
                                            class="nav-main-link"
                                            href="users_edit.php">
                                            <span class="nav-main-link-name">Editar usuarios</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>';
                        }

                        ?>
                    </ul>
                </div>
                <!-- END Side Navigation -->
            </div>
            <!-- END Sidebar Scrolling -->
        </nav>
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="space-x-1">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button
                        type="button"
                        class="btn btn-alt-secondary"
                        data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- Open Search Section -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->

                    <!-- END Open Search Section -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="space-x-1">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block">
                        <button
                            type="button"
                            class="btn btn-alt-secondary"
                            id="page-header-user-dropdown"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-fw fa-user d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"><?= $_SESSION["username"]; ?></span>
                            <i
                                class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
                        </button>
                        <div
                            class="dropdown-menu dropdown-menu-end p-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div
                                class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                                Opciones
                            </div>
                            <div class="p-2">

                                <a class="dropdown-item" href="/routes/suadview/login.php">
                                    <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Salir
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->

                    <!-- Notifications Dropdown -->

                    <!-- END Toggle Side Overlay -->
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-header-dark">
                <div class="bg-white-10">
                    <div class="content-header">
                        <div class="w-100 text-center">
                            <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="content">
                <!-- Quick Overview -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <?php if (empty($id)) { ?>
                            <h3 class="block-title">Añadir producto</h3>
                        <?php } else { ?>
                            <h3 class="block-title">Editar producto</h3>
                        <?php } ?>
                    </div>
                    <div class="block-content">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-8">
                                <form action="be_pages_ecom_product_edit.html" method="POST" data-action="<?php if (!empty($id)) echo "edit";
                                                                                                            else echo "add"; ?>" onsubmit="return false;">
                                    <input type="hidden" id="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-id">Código</label>
                                        <input type="text" class="form-control" id="dm-ecom-product-id" name="dm-ecom-product-id"
                                            value="<?php if (!empty($id)) echo $sku; ?>" required>
                                        <input type="hidden" name="id" value="<?php if (!empty($id)) echo $id; ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-name">Nombre</label>
                                        <input type="text" class="form-control" id="dm-ecom-product-name" name="dm-ecom-product-name"
                                            value="<?php if (!empty($id)) echo $nombre; ?>" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-description-short">Descripción</label>
                                        <textarea class="form-control" id="dm-ecom-product-description-short"
                                            name="dm-ecom-product-description-short" rows="4" required><?php if (!empty($id)) echo $descripcion; ?></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-category">Categorías</label>
                                        <select class="form-select" id="dm-ecom-product-category" name="dm-ecom-product-category"
                                            style="width: 100%;" data-placeholder="Escoge una categoría.." required>
                                            <option>Selecciona una categoría</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-category-type">Subcategorías</label>
                                        <select class="form-select" id="dm-ecom-product-category-type" name="dm-ecom-product-category-type"
                                            style="width: 100%;" data-placeholder="Escoge una subcategoría.." required>
                                            <option>Selecciona una subcategoría</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="dm-ecom-product-attribute">Atributos</label>
                                        <select class="form-select" id="dm-ecom-product-attribute" name="dm-ecom-product-attribute"
                                            style="width: 100%;" data-placeholder="Escoge un atributo.." required>
                                            <option>Selecciona un atributo</option>
                                        </select>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label" for="dm-ecom-product-options">Opciones</label>
                                            <input type="text" class="form-control" id="dm-ecom-product-options" name="dm-ecom-product-options"
                                                value="<?php if (!empty($id)) echo $options; ?>" placeholder="Ej: S,M,L,XL - rojo,azul,verde" pattern="^[a-zA-Z0-9,]+$" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label" for="dm-ecom-product-price">Precio en USD ($)</label>
                                            <input type="text" class="form-control" id="dm-ecom-product-price" name="dm-ecom-product-price"
                                                value="<?php if (!empty($id)) echo $precio; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">¿Tiene descuento?</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="dm-ecom-product-published"
                                                name="dm-ecom-product-published" <?php if (!empty($id) && $descuento > 0) echo "checked"; ?>>
                                            <label class="form-check-label" for="dm-ecom-product-published"></label>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Destacar producto</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="product-featured"
                                                name="product-featured" <?php if (!empty($id) && $featured > 0) echo "checked"; ?>>
                                            <label class="form-check-label" for="product-featured"></label>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label" for="dm-ecom-product-price">Porcentaje de descuento</label>
                                            <input type="number" class="form-control" id="dm-ecom-product-price" name="dm-ecom-product-price"
                                                value="<?php if (!empty($id)) echo $porcentajeD; ?>" placeholder="%" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="dm-ecom-product-price">Precio final ($)</label>
                                            <input type="text" class="form-control" id="finalPrice" name="dm-ecom-product-price"
                                                value="" disabled required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-alt-primary"><?php if (!empty($id)) echo "Editar";
                                                                                            else echo "Añadir"; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Info -->

                <!-- Media -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Imagen</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-8">
                                <!-- Dropzone (functionality is auto initialized by the plugin itself in js/plugins/dropzone/dropzone.min.js) -->
                                <!-- For more info and examples you can check out http://www.dropzonejs.com/#usage -->
                                <form class="dropzone" id="my-dropzone" action="#">
                                    <p class="dz-message">Arrastra la imagen o haz click</p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END All Products -->
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            <div class="content py-0">
                <div class="row fs-sm">
                    <div
                        class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-center text-sm-end">
                        Hecho con <i class="fa fa-heart text-danger"></i> por
                        <a class="fw-semibold" target="_blank">Sergio Gutierrez</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 text-center text-sm-start">
                        <a class="fw-semibold" target="_blank">Scriptdash</a> &copy;
                        <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at /public/_js/main/app.js
    -->
    <script src="/public/js/dashmix.app.min.js"></script>

    <script src="/public/js/dropzone.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = form.querySelector("button[type='submit']");
            const descuentoCheckbox = document.getElementById("dm-ecom-product-published");
            const porcentajeDescuentoInput = document.querySelector("input[placeholder='%']");
            const precioFinalInput = document.querySelector("#finalPrice");
            const precioInput = document.getElementById("dm-ecom-product-price");
            const opcionesInput = document.getElementById("dm-ecom-product-options");
            const subInput = document.getElementById("dm-ecom-product-category-type");
            const formAction = form.getAttribute("data-action");
            const id = document.querySelector("input[name='id']");
            const destacado = document.querySelector("#product-featured");

            Dropzone.autoDiscover = false;

            // Verifica si Dropzone ya está inicializado antes de crear una nueva instancia
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach((dz) => dz.destroy());
            }

            // Inicializar Dropzone correctamente
            let myDropzone = new Dropzone(".dropzone", {
                addRemoveLinks: true,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 100,
                removedfile: function(file) {
                    var _ref;
                    return (_ref = file.previewElement) != null ?
                        _ref.parentNode.removeChild(file.previewElement) :
                        void 0;
                },
            });

            porcentajeDescuentoInput.setAttribute("disabled", "true");

            if (precioInput.value && precioInput.value > 0) {
                precioFinalInput.value = precioInput.value;
            }

            // Evento para calcular el precio final si hay descuento
            descuentoCheckbox.addEventListener("change", function() {
                if (descuentoCheckbox.checked) {
                    porcentajeDescuentoInput.removeAttribute("disabled");
                } else {
                    porcentajeDescuentoInput.setAttribute("disabled", "true");
                    porcentajeDescuentoInput.value = "";
                    precioFinalInput.value = "";
                }
            });

            porcentajeDescuentoInput.addEventListener("input", function() {
                let precio = parseFloat(precioInput.value) || 0;
                let descuento = parseFloat(porcentajeDescuentoInput.value) || 0;

                if (descuento > 0 && descuento <= 100) {
                    let precioDescuento = precio - (precio * descuento / 100);
                    precioFinalInput.value = precioDescuento.toFixed(2);
                } else {
                    precioFinalInput.value = "";
                }
            });

            precioInput.addEventListener("input", function() {
                let precio = parseFloat(precioInput.value) || 0;
                let descuento = parseFloat(porcentajeDescuentoInput.value) || 0;

                if (descuento > 0 && descuento <= 100) {
                    let precioDescuento = precio - (precio * descuento / 100);
                    precioFinalInput.value = precioDescuento.toFixed(2);
                } else {
                    precioFinalInput.value = precioInput.value;
                }
            });

            form.addEventListener("submit", async function(event) {
                event.preventDefault();

                let formData = new FormData();
                formData.append("sku", document.getElementById("dm-ecom-product-id").value);
                formData.append("nombre", document.getElementById("dm-ecom-product-name").value);
                formData.append("descripcion", document.getElementById("dm-ecom-product-description-short").value);
                formData.append("precio", precioInput.value);
                formData.append("descuento", descuentoCheckbox.checked ? "1" : "0");
                formData.append("porcentajeD", descuentoCheckbox.checked ? porcentajeDescuentoInput.value : "0");
                formData.append("precioD", precioFinalInput.value || precioInput.value);
                formData.append("id", id.value);
                formData.append("destacado", destacado.checked);

                if (document.getElementById("dm-ecom-product-category").value != "Selecciona una categoría") {
                    formData.append("categoria", document.getElementById("dm-ecom-product-category").value);
                } else {
                    alert("Selecciona una categoría.");
                    return;
                }

                if (document.getElementById("dm-ecom-product-category-type").value != "Selecciona una subcategoría") {
                    formData.append("subcategoria", document.getElementById("dm-ecom-product-category-type").value);
                } else {
                    alert("Selecciona una subcategoría.");
                    return;
                }

                if (document.getElementById("dm-ecom-product-attribute").value != "Selecciona un atributo") {
                    formData.append("atributo", document.getElementById("dm-ecom-product-attribute").value);
                } else {
                    alert("Selecciona un atributo.");
                    return;
                }

                if (document.getElementById("dm-ecom-product-options").value.length > 0) {
                    formData.append("opciones", opcionesInput.value);
                } else {
                    alert("Escribe las opciones.");
                    return;
                }

                if (myDropzone.files.length > 0) {
                    myDropzone.files.forEach((file, index) => {
                        formData.append("image[]", file);
                    });
                }

                formData.append("csrf_token", document.getElementById("csrf_token").value);

                // Verifica si es una edición o una adición
                let apiUrl = formAction === "edit" ? "/src/api/products/edit_product.php" : "/src/api/products/add_product.php";

                // Deshabilitar botón mientras se envía
                submitButton.disabled = true;
                submitButton.textContent = formAction === "edit" ? "Editando..." : "Enviando...";

                try {
                    let response = await fetch(apiUrl, {
                        method: "POST",
                        body: formData
                    });

                    let result = await response.json();
                    alert(result.message);

                    if (result.status === "success") {
                        form.reset();
                        myDropzone.removeAllFiles(); // Eliminar imagen subida después de éxito
                    }
                } catch (error) {
                    alert("Error al enviar la solicitud.");
                }

                // Habilitar botón nuevamente
                submitButton.disabled = false;
                submitButton.textContent = formAction === "edit" ? "Editar" : "Añadir";
            });

            //Cargar las categorías

            fetch("/src/api/categories/read_categories.php")
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById("dm-ecom-product-category");

                    data.data.forEach(category => {
                        let option = document.createElement("option");
                        option.value = category.id;
                        option.textContent = category.nombre;
                        select.appendChild(option);
                    });

                });

            fetch("/src/api/attributes/read_attributes.php")
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById("dm-ecom-product-attribute");

                    data.data.forEach(attribute => {
                        let option = document.createElement("option");
                        option.value = attribute.id;
                        option.textContent = attribute.atributo;
                        select.appendChild(option);
                    });

                });

            //Cargar las subcategorías

            document.getElementById("dm-ecom-product-category").addEventListener("change", () => {
                // Limpiar las subcategorías antes de cargar nuevas
                subInput.innerHTML = '<option>Selecciona una subcategoría</option>';

                // Obtener las subcategorías de la API

                fetch(`/src/api/subcategories/read_subcategories_specific.php?id_categoria=${document.getElementById("dm-ecom-product-category").value}`)
                    .then(response => response.json())
                    .then(data => {

                        data.data.forEach(attribute => {
                            let option = document.createElement("option");
                            option.value = attribute.id;
                            option.textContent = attribute.nombre;
                            subInput.appendChild(option);
                        });

                    });

            });
        });
    </script>

</body>

</html>
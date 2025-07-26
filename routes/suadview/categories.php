<?php
require_once __DIR__ . '/../../src/config/config.php';

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <title>Piel Canela | Categorias</title>

    <meta name="robots" content="noindex, nofollow" />

    <!-- Open Graph Meta -->

    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link
        rel="shortcut icon"
        href="<?= BASE_URL ?>public/img/favicon.ico" />
    <link
        rel="icon"
        type="image/png"
        sizes="192x192"
        href="<?= BASE_URL ?>public/img/android-chrome-192x192.png" />
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="<?= BASE_URL ?>public/img/apple-touch-icon.png" />
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link
        rel="stylesheet"
        id="css-main"
        href="<?= BASE_URL ?>public/css/dashmix.min.css" />

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="<?= BASE_URL ?>public/css/themes/xwork.min.css"> -->
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

                                <a class="dropdown-item" href="<?= BASE_URL ?>routes/suadview/login.php">
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
                <div class="row items-push">
                    <div class="col-6 col-lg-3">
                        <a
                            class="block block-rounded block-link-shadow text-center h-100 mb-0"
                            href="categories_edit.php">
                            <div class="block-content py-5">
                                <div class="fs-3 fw-semibold text-success mb-1">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <p class="fw-semibold fs-sm text-success text-uppercase mb-0">
                                    Añadir
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a
                            class="block block-rounded block-link-shadow text-center h-100 mb-0"
                            href="javascript:void(0)">
                            <div class="block-content py-5">
                                <div class="fs-3 fw-semibold text-danger mb-1" id="view"></div>
                                <p class="fw-semibold fs-sm text-danger text-uppercase mb-0">
                                    Categorías
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END Quick Overview -->

                <!-- All Products -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Todas las categorías</h3>
                    </div>
                    <div class="block-content bg-body-dark">
                        <!-- Search Form -->
                        <form
                            action="be_pages_ecom_products.html"
                            method="POST"
                            onsubmit="return false;">
                            <div class="mb-4">
                                <input
                                    type="text"
                                    class="form-control form-control-alt"
                                    id="dm-ecom-products-search"
                                    name="dm-ecom-products-search"
                                    placeholder="Buscar categorías..." />
                            </div>
                        </form>
                        <!-- END Search Form -->
                    </div>
                    <div class="block-content">
                        <!-- All Products Table -->
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 100px">ID</th>
                                        <th class="d-none d-md-table-cell">Nombre</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center fs-sm">
                                            <a
                                                class="fw-semibold"
                                                href="be_pages_ecom_product_edit.html">
                                                <strong>PID.036535</strong>
                                            </a>
                                        </td>
                                        <td class="d-none d-md-table-cell fs-sm">
                                            <a
                                                class="fw-semibold"
                                                href="be_pages_ecom_product_edit.html">Product #35</a>
                                        </td>
                                        <td class="text-center fs-sm">
                                            <a
                                                class="btn btn-sm btn-alt-secondary"
                                                href="be_pages_ecom_product_edit.html">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </a>
                                            <a
                                                class="btn btn-sm btn-alt-secondary"
                                                href="javascript:void(0)">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fs-sm">
                                            <a
                                                class="fw-semibold"
                                                href="be_pages_ecom_product_edit.html">
                                                <strong>PID.036534</strong>
                                            </a>
                                        </td>
                                        <td class="d-none d-md-table-cell fs-sm">
                                            <a
                                                class="fw-semibold"
                                                href="be_pages_ecom_product_edit.html">Product #34</a>
                                        </td>
                                        <td class="text-center fs-sm">
                                            <a
                                                class="btn btn-sm btn-alt-secondary"
                                                href="be_pages_ecom_product_edit.html">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </a>
                                            <a
                                                class="btn btn-sm btn-alt-secondary"
                                                href="javascript:void(0)">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- END All Products Table -->

                        <!-- Pagination -->
                        <nav aria-label="Photos Search Navigation">
                            <ul class="pagination justify-content-end mt-2">
                                <li class="page-item">
                                    <a
                                        class="page-link"
                                        href="javascript:void(0)"
                                        tabindex="-1"
                                        aria-label="Previous">
                                        Prev
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="javascript:void(0)">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0)">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0)">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0)">4</a>
                                </li>
                                <li class="page-item">
                                    <a
                                        class="page-link"
                                        href="javascript:void(0)"
                                        aria-label="Next">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- END Pagination -->
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
    <script src="<?= BASE_URL ?>public/js/dashmix.app.min.js"></script>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>

    <script>
        async function loadCategories() {
            try {
                const response = await fetch(`${BASE_URL}src/api/categories/read_categories.php");
                const result = await response.json();

                if (result.status === "success") {
                    const tableBody = document.querySelector("table tbody");
                    tableBody.innerHTML = ""; // Limpiar contenido previo

                    result.data.forEach(category => {
                        const row = document.createElement("tr");

                        row.innerHTML = `
                    <td class="text-center fs-sm">
                        <strong>ID.${category.id.toString().padStart(6, "0")}</strong>
                    </td>
                    <td class="d-none d-md-table-cell fs-sm">
                        <a class="fw-semibold" href="javascript:void(0)">${category.nombre}</a>
                    </td>
                    <td class="text-center fs-sm">
                        <a class="btn btn-sm btn-alt-secondary edit-btn" data-id="${category.id}" data-name="${category.nombre}" href="javascript:void(0)">
                            <i class="fa fa-fw fa-eye"></i>
                        </a>
                        <a class="btn btn-sm btn-alt-secondary delete-btn" data-id="${category.id}" href="javascript:void(0)">
                            <i class="fa fa-fw fa-times text-danger"></i>
                        </a>
                    </td>
                `;

                        tableBody.appendChild(row);
                    });
                    showCategory();
                    attachEventListeners();
                } else {
                    console.error("Error:", result.message);
                }
            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
        }

        // Agrega eventos a los botones de editar y eliminar
        function attachEventListeners() {
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    if (confirm("¿Estás seguro de que deseas editar esta categoría?")) {
                        window.location.href = `${BASE_URL}routes/suadview/categories_edit.php?id=${id}`;
                    }
                });
            });

            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", async function() {
                    const id = this.getAttribute("data-id");
                    if (confirm("¿Estás seguro de que deseas eliminar esta categoría?")) {
                        await deleteCategory(id);
                        loadCategories(); // Recargar categorías después de eliminar
                    }
                });
            });

            const searchInput = document.getElementById("dm-ecom-products-search");
            const tableRows = document.querySelectorAll(".table tbody tr");

            searchInput.addEventListener("input", function() {
                const searchText = searchInput.value.trim().toLowerCase();

                tableRows.forEach((row) => {
                    const name = row.querySelector("td:nth-child(2) a")?.textContent.trim().toLowerCase();

                    if (name.includes(searchText)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }

        // Elimina una categoría
        async function deleteCategory(id) {
            try {
                const response = await fetch(`${BASE_URL}src/api/categories/delete_category.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id
                    })
                });

                const result = await response.json();
                alert(result.message);
            } catch (error) {
                console.error("Error al eliminar la categoría:", error);
            }
        }

        async function showCategory(id) {
            const view = document.querySelector("#view");

            try {
                const response = await fetch(`${BASE_URL}src/api/categories/all_views_category.php");

                const result = await response.json();
                view.textContent = result.data[0]["COUNT(id)"] > 0 ? result.data[0]["COUNT(id)"] : 0;
            } catch (error) {
                console.error("Error al eliminar la categoría:", error);
            }

        }

        // Cargar categorías al inicio
        document.addEventListener("DOMContentLoaded", loadCategories);
    </script>
</body>

</html>
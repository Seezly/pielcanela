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

$stmt = $pdo->prepare("SELECT * FROM ads");
$stmt->execute();
$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <title>Piel Canela | Ads</title>

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
                            <span class="d-none d-sm-inline-block"><? echo $_SESSION["username"]; ?></span>
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
                <div class="row items-push">
                    <div class="col-6 col-lg-3">
                        <a
                            class="block block-rounded block-link-shadow text-center h-100 mb-0"
                            href="javascript:void(0)">
                            <div class="block-content py-5">
                                <div class="fs-3 fw-semibold text-danger mb-1" id="view"><? echo $ads[0]["visitas"] ?></div>
                                <p class="fw-semibold fs-sm text-danger text-uppercase mb-0">
                                    Clics en ad #1
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a
                            class="block block-rounded block-link-shadow text-center h-100 mb-0"
                            href="javascript:void(0)">
                            <div class="block-content py-5">
                                <div class="fs-3 fw-semibold text-danger mb-1" id="view"><? echo $ads[1]["visitas"] ?></div>
                                <p class="fw-semibold fs-sm text-danger text-uppercase mb-0">
                                    Clics en ad #2
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END Quick Overview -->

                <!-- All Products -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Todas las ads</h3>
                    </div>
                    <div class="block-content">
                        <!-- All Products Table -->
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Ad #1</h3>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-10 col-lg-8">
                                                        <!-- Dropzone (functionality is auto initialized by the plugin itself in js/plugins/dropzone/dropzone.min.js) -->
                                                        <!-- For more info and examples you can check out http://www.dropzonejs.com/#usage -->
                                                        <form class="dropzone ads" id="ad1Form" action="#">
                                                            <p class="dz-message">Arrastra la imagen o haz click</p>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Ad #2</h3>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-10 col-lg-8">
                                                        <!-- Dropzone (functionality is auto initialized by the plugin itself in js/plugins/dropzone/dropzone.min.js) -->
                                                        <!-- For more info and examples you can check out http://www.dropzonejs.com/#usage -->
                                                        <form class="dropzone ads" id="ad2Form" action="#">
                                                            <p class="dz-message">Arrastra la imagen o haz click</p>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <form id="ad1Form" action="#" method="POST" onsubmit="return false;">
                                            <div class="mb-4">
                                                <label class="form-label" for="dm-ecom-ad-1">URL AD #1</label>
                                                <input type="text" class="form-control" id="dm-ecom-ad-1" name="dm-ecom-ad-1"
                                                    value="<? echo $ads[0]["url"]; ?>" placeholder="https:/.com/categoria/producto">
                                                <input type="hidden" name="idAd1" value="1">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <form id="ad2Form" action="#" method="POST" onsubmit="return false;">
                                            <div class="mb-4">
                                                <label class="form-label" for="dm-ecom-ad-2">URL AD #2</label>
                                                <input type="text" class="form-control" id="dm-ecom-ad-2" name="dm-ecom-ad-2"
                                                    value="<? echo $ads[1]["url"]; ?>" placeholder="https:/.com/categoria/producto">
                                                <input type="hidden" name="idAd2" value="2">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="submit" id="adSubmit1" class="btn btn-alt-primary">Actualizar Ad #1</button>
                                <button type="submit" id="adSubmit2" class="btn btn-alt-primary">Actualizar Ad #2</button>
                            </div>
                        </div>
                        <!-- END All Products Table -->
                        <!-- Pagination -->
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
    <script src="/public/js/dashmix.app.min.js"></script>

    <script src="/public/js/dropzone.min.js"></script>

    <script>
        // Cargar categorías al inicio
        document.addEventListener("DOMContentLoaded", () => {
            const adsDropzone = document.querySelectorAll(".ads");
            Dropzone.autoDiscover = false;

            // Destruye instancias anteriores
            while (Dropzone.instances.length > 0) {
                Dropzone.instances[0].destroy(); // Destruye la primera instancia en la lista
                console.log("Instancia destruida. Total restante:", Dropzone.instances.length);
            }

            // Inicializa nuevas instancias
            adsDropzone.forEach((dropzone) => {
                if (!dropzone.dropzone) {
                    new Dropzone(dropzone, {
                        url: "#",
                        autoProcessQueue: false,
                        addRemoveLinks: true,
                        uploadMultiple: false,
                        parallelUploads: 1,
                        maxFiles: 1,
                        removedfile: function(file) {
                            var _ref;
                            return (_ref = file.previewElement) != null ?
                                _ref.parentNode.removeChild(file.previewElement) :
                                void 0;
                        },
                    });
                    console.log("Dropzone inicializado en:", dropzone);
                }
            });

            const ad1Form = document.getElementById("ad1Form");
            const ad2Form = document.getElementById("ad2Form");
            const submitButton1 = document.querySelector("#adSubmit1");
            const submitButton2 = document.querySelector("#adSubmit2");

            submitButton1.addEventListener("click", async (e) => {
                e.preventDefault();

                const urlAd1 = document.getElementById("dm-ecom-ad-1").value;
                const idAd1 = document.querySelector("input[name=idAd1]").value;

                let formData = new FormData();
                formData.append("id", idAd1);
                formData.append("url", urlAd1);

                formData.append("image[]", Dropzone.instances[0].files);

                try {
                    let response = await fetch("/src/scripts/ad_logic.php", {
                        method: "POST",
                        body: formData
                    });

                    let result = await response.json();
                    alert(result.message);

                    if (result.status === "success") {
                        form.reset();
                        Dropzone.instances[0].removeAllFiles(); // Eliminar imagen subida después de éxito
                        Dropzone.instances[1].removeAllFiles(); // Eliminar imagen subida después de éxito
                    }
                } catch (error) {
                    alert("Error al enviar la solicitud.");
                }
            });
            submitButton2.addEventListener("click", async (e) => {
                e.preventDefault();

                const urlAd2 = document.getElementById("dm-ecom-ad-2").value;
                const idAd2 = document.querySelector("input[name=idAd2]").value;

                let formData = new FormData();
                formData.append("id", idAd2);
                formData.append("url", urlAd2);

                formData.append("image[]", Dropzone.instances[1].files);

                try {
                    let response = await fetch("/src/scripts/ad_logic.php", {
                        method: "POST",
                        body: formData
                    });

                    let result = await response.json();
                    alert(result.message);

                    if (result.status === "success") {
                        form.reset();
                        Dropzone.instances[0].removeAllFiles(); // Eliminar imagen subida después de éxito
                        Dropzone.instances[1].removeAllFiles(); // Eliminar imagen subida después de éxito
                    }
                } catch (error) {
                    alert("Error al enviar la solicitud.");
                }
            });
        });
    </script>
</body>

</html>
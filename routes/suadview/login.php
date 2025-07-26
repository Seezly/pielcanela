<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Finally, destroy the session
session_destroy();

// Start a fresh session to handle CSRF tokens
session_start();
require_once __DIR__ . '/../../src/scripts/csrf.php';
require_once __DIR__ . '/../../src/config/config.php';
$csrf_token = generate_csrf_token();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Piel Canela | Admin Login</title>

    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="<?= BASE_URL ?>public/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= BASE_URL ?>public/img/android-chrome-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>public/img/apple-touch-icon.png">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Dashmix framework -->
    <link rel="stylesheet" id="css-main" href="<?= BASE_URL ?>public/css/dashmix.min.css">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
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
    <div id="page-container">

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="bg-image" style="background-image: url('<?= BASE_URL ?>assets/media/photos/photo22@2x.jpg');">
                <div class="row g-0 bg-primary-op">
                    <!-- Main Section -->
                    <div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
                        <div class="p-3 w-100">
                            <!-- Header -->
                            <div class="mb-3 text-center">
                                <a class="link-fx fw-bold fs-1" href="index.html">
                                    <span class="text-dark">Piel </span><span class="text-primary">Canela</span>
                                </a>
                                <p class="text-uppercase fw-bold fs-sm text-muted">Iniciar sesión</p>
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                            <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                            <div class="row g-0 justify-content-center">
                                <div class="col-sm-8 col-xl-6">
                                    <form class="js-validation-signin" method="POST">
                                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                        <div class="py-3">
                                            <div class="mb-4">
                                                <input type="text" class="form-control form-control-lg form-control-alt" id="login-username" name="login-username" placeholder="Usuario">
                                            </div>
                                            <div class="mb-4">
                                                <input type="password" class="form-control form-control-lg form-control-alt" id="login-password" name="login-password" placeholder="Contraseña">
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary">
                                                <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Iniciar sesión
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END Sign In Form -->
                        </div>
                    </div>
                    <!-- END Main Section -->

                    <!-- Meta Info Section -->
                    <div class="hero-static col-md-6 d-none d-md-flex align-items-md-center justify-content-md-center text-md-center">
                        <div class="p-3">
                            <p class="display-4 fw-bold text-white mb-3">
                                ¡Bienvenido/a!
                            </p>
                        </div>
                    </div>
                    <!-- END Meta Info Section -->
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="<?= BASE_URL ?>public/js/dashmix.app.min.js"></script>

    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = form.querySelector("button[type='submit']");
            const pass = document.getElementById("login-password");
            const user = document.getElementById("login-username");

            form.addEventListener("submit", async function(event) {
                event.preventDefault();

                let formData = new FormData();
                formData.append("user", user.value);
                formData.append("pass", pass.value);
                formData.append("csrf_token", document.getElementById("csrf_token").value);

                try {
                    let response = await fetch(`${BASE_URL}src/api/users/verify_user.php`, {
                        method: "POST",
                        body: formData
                    });

                    let result = await response.json();

                    if (result.status === "success") {
                        form.reset();
                        window.location.href = `${BASE_URL}routes/suadview/suadview.php`;
                    }
                } catch (error) {
                    alert("Error al enviar la solicitud.");
                }
            });
        });
    </script>

</body>

</html>
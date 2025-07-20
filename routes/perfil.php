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

        <!-- Perfil -->

        <section class="perfil">
            <h2>Mi perfil</h2>
            <div class="perfil-info">
                <div class="perfil-details">
                    <p><span class="bold">Nombre</span>: Nombre del usuario</p>
                    <p><span class="bold">Correo</span>: Correo del usuario </p>
                    <p><span class="bold">Cédula del usuario</span></p>
                </div>
            </div>
            <div class="perfil-options">
                <a href="#" class="btn">Cambiar contraseña</a>
                <a href="#" class="btn">Cerrar sesión</a>
            </div>
        </section>


    </main>

    <!-- Footer -->
    <?php include_once('../src/components/footer.php'); ?>

    <!-- Modales -->
    <?php include_once('../src/components/modals.php'); ?>
</body>

</html>
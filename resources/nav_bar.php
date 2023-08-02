<?php require_once "functions.php"; ?>

<nav class="navbar navbar-expand-lg navbar-expand-md navbar-dark bg-dark bg-gradient fixed-top border-bottom border-secondary">
    <div class="container-fluid">
        <img src="resources/logoApp.png" alt="Logo" style="width: 40px; margin-right: 10px;" />
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-center mt-1">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="home">Imagenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="mensajes">Mensajes</a>
                </li>
                <?php
                    if ($logged) {
                        echo "
                            <div class='text-center'>
                                <a href='subir_imagen' class='btn btn-sm btn-outline-success text-white m-1'>Subir imagen(es)</a>
                            </div>
                            <div class='text-center'>
                                <a href='configurar_perfil' class='btn btn-sm btn-outline-warning m-1'><span class='icon-params'></span> $username</a>
                            </div>";
                    }
                ?>
            </ul>
                
            <?php
            if (!$logged) {
                echo "
                    <div class='text-center'>
                        <a href='iniciar_sesion' class='btn btn-sm btn-outline-success text-white m-1'>Iniciar sesión</a>
                    </div>";
            } else {
                if ($administrator) {
                    echo "
                        <div class='text-center'>
                            <a href='imagenes_eliminadas' class='btn btn-sm btn-outline-danger m-1' aria-current='page'>Fotos eliminadas</a>
                        </div>";
                    echo "
                        <div class='text-center'>
                            <a href='estadisticas' class='btn btn-sm btn-outline-primary m-1' aria-current='page'>Estadísticas</a>
                        </div>";
                    echo "
                        <div class='text-center'>
                            <a href='logs_acceso' class='btn btn-sm btn-outline-info m-1' aria-current='page'>Logs de acceso</a>
                        </div>";
                }
                echo "
                    <div class='text-center'>
                        <a href='cerrar_sesion' class='btn btn-sm btn-outline-danger text-white m-1'>Cerrar sesión</a>
                    </div>";
            }
            ?>
        </div>
    </div>
</nav>
<hr class="mt-5">

<main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row g-0 my-3">

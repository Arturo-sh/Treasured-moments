<?php require_once "functions.php"; ?>

<nav class="navbar navbar-expand-lg navbar-expand-md navbar-dark bg-dark bg-gradient fixed-top border-bottom border-secondary">
    <div class="container-fluid">
        <img src="resources/logoApp.png" alt="Logo" style="width: 40px; margin-right: 10px;" />
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-center mt-1">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Imagenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messages.php">Mensajes</a>
                </li>
                <?php
                    if ($logged) {
                        echo "
                            <div class='text-center'>
                                <a href='upload_image.php' class='btn btn-sm btn-outline-success text-white m-1'>Subir imagen(es)</a>
                            </div>";

                        echo "
                            <div class='dropdown'>
                                <button class='btn btn-sm btn-outline-warning dropdown-toggle m-1' type='button' id='dropdownMenuButton2' data-bs-toggle='dropdown' aria-expanded='false'>$username</button>
                                <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='dropdownMenuButton2'>
                                    <li><a class='dropdown-item' href='config_account.php'>Configurar perfil</a></li>
                                </ul>
                            </div>";
                    }
                ?>
            </ul>
                
            <?php
            if (!$logged) {
                echo "
                    <div class='text-center'>
                        <a href='login.php' class='btn btn-sm btn-outline-success text-white m-1'>Iniciar sesión</a>
                    </div>";
            } else {
                if ($administrator) {
                    echo "
                        <div class='text-center'>
                            <a href='statistics.php' class='btn btn-sm btn-outline-primary m-1' aria-current='page'>Estadísticas</a>
                        </div>";
                    echo "
                        <div class='text-center'>
                            <a href='logs.php' class='btn btn-sm btn-outline-info m-1' aria-current='page'>Logs de acceso</a>
                        </div>";
                }
                echo "
                    <div class='text-center'>
                        <a href='resources/logout.php' class='btn btn-sm btn-outline-danger text-white m-1'>Cerrar sesión</a>
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

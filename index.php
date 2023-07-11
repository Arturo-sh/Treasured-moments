    <?php require_once "resources/header.php"; ?>
    <?php require_once "resources/nav_bar.php"; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row g-0 mb-1">
                        <?php
                        require_once "resources/conn.php";
                        $sql = "SELECT i.id_imagen, i.nombre_imagen, i.id_album, a.id_album, a.nombre_album, s.fecha_subida, s.id_usuario, u.nombre_usuario, u.imagen_perfil FROM imagenes AS i INNER JOIN subidas AS s ON i.id_subida = s.id_subida INNER JOIN albumes AS a ON i.id_album = a.id_album INNER JOIN usuarios AS u ON s.id_usuario = u.id_usuario ORDER BY a.nombre_album ASC";

                        require_once "resources/images_gallery.php";
                        show_gallery($conn, $sql, "Aún no se han subido imágenes al sistema :(", "bg-primary", "index");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "resources/footer.php"; ?>
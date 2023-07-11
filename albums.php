    <?php require_once "resources/header.php"; ?>
    <?php require_once "resources/nav_bar.php"; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row g-0 mb-1">
                        <?php
                        if (isset($_GET['album'])) {
                            require_once "resources/conn.php";

                            $album_id_encoded = $_GET['album'];
                            $album_id_decoded = base64_decode($album_id_encoded);

                            $consult_data_album = "SELECT * FROM albumes WHERE id_album = '$album_id_decoded'";
                            $result_data_album = mysqli_query($conn, $consult_data_album);

                            if (!mysqli_num_rows($result_data_album) > 0) {
                                album_not_found();
                            }  

                            $album_data = mysqli_fetch_all($result_data_album, MYSQLI_ASSOC);
                            
                            foreach ($album_data as $row) {
                                $album_name = $row['nombre_album'];
                                $creation_album = $row['fecha_creacion'];
                            }

                            $sql = "SELECT i.id_imagen, i.nombre_imagen, s.fecha_subida, s.id_usuario, a.id_album, a.nombre_album, u.nombre_usuario, u.imagen_perfil FROM imagenes AS i INNER JOIN subidas AS s ON s.id_subida = i.id_subida INNER JOIN albumes AS a ON i.id_album = a.id_album INNER JOIN usuarios AS u  ON s.id_usuario = u.id_usuario WHERE a.id_album = '$album_id_decoded' ORDER BY a.nombre_album ASC";

                            require_once "resources/images_gallery.php";
                            show_gallery($conn, $sql, "Aún no se han subido imágenes a este albúm :(", "bg-warning");
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "resources/footer.php"; ?>
<?php

function show_gallery($conn, $sql, $message = "", $bg = "bg-primary", $page = "") {
    $sql_get_data = mysqli_query($conn, $sql);

    if (!(mysqli_num_rows($sql_get_data) > 0)) {
	    echo "<h4 class='text-center mb-3 mt-5'>$message</h4>";
	}
                                        
    $last_album_name = " ";
    $counter = 0;

    if ($page == "index") {
        $limit_show_images = 3;
    }

    while ($row = mysqli_fetch_array($sql_get_data)) {
        $image_id = $row['id_imagen'];
        $image_name = $row['nombre_imagen'];
        $album_id = $row['id_album'];
        $album_name = $row['nombre_album'];
        $date_uploaded = $row['fecha_subida'];
        $user_id = $row['id_usuario'];
        $user_name = $row['nombre_usuario'];
        $profile_image = $row['imagen_perfil'];

        $profile_encoded_id = base64_encode($user_id);
        $album_encoded_id = base64_encode($album_id);

        if ($last_album_name != $album_name) {
            $last_album_name = $album_name;
            echo "<h5 class='container text-center border-dark rounded-1 p-2 $bg bg-gradient text-white mt-3 mb-3'><a href='albums.php?album=$album_encoded_id' class='text-decoration-none text-white'>Albúm: $last_album_name</a></h5>";
            $counter = 0;
        }

        if ($page == "index") {
            if ($counter == $limit_show_images) {
                continue;
            }
        }

        echo "
            <div class='container col-md-4 d-flex justify-content-center align-items-center flex-md-row flex-column'>
                <div class=''>
                    <figure class='figure'>
                        <img src='images/$image_name' class='figure-img img-fluid rounded border' loading='lazy' alt='Recurso no disponible' onclick='load_modal(\"$image_id\", \"$image_name\", \"$album_name\", \"$date_uploaded\", \"$user_name\", \"$profile_image\", \"$profile_encoded_id\")' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                        <figure class='text-end'>
                            <blockquote class='blockquote'>
                            </blockquote>
                            <figcaption class='blockquote-footer'>
                            Subido por: <span class='text-primary'>$user_name</span> el <cite title='Source Title'><span class='text-success'>$date_uploaded</span></cite>
                            </figcaption>
                        </figure>
                    </figure>
                </div>
            </div>";
        $counter += 1;
    }
}
?>

<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
            <div class='modal-header'>
                <h4 class="modal-title">Albúm: <span class='text-danger' id='exampleModalLabel'></span></h4>
                <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <img id='modal_content_img' src='images/' class='figure-img img-fluid rounded border' alt='Recurso no disponible'>
                <div class="d-flex justify-content-center">
                    <button class='m-1 col-md-4 btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#exampleModal2'>Cambiar de albúm</button>
                    <!--<button class='m-1 col-md-4 btn btn-sm btn-outline-danger' onclick='delete_message(1)'>Eliminar</button>-->
                </div>
                <p class="text-center m-0">Subido el: <span class="text-info" id="date_uploaded"></span> por: <a href="#" id="profile_link"><span class="text-warning" id="author"></span><img src="profile_images/" id="profile_img" style="margin: 5px; width: 50px; height: 50px; border-radius: 50%;" alt="Recurso no disponible"></a></p>
            </div>
            <div class='modal-footer'></div>
        </div>
    </div>
</div>

<div class='modal fade' id='exampleModal2' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
            <div class='modal-header'>
                <h4 class="modal-title">Cambiar albúm</h4>
                <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <form class="row g-3" method="POST" action="">
                    <div class="col-md-6">
                        <input type="hidden" class="form-control" name="image_id" id="image_id">
                        <label for="" class="form-label">Crear nuevo albúm:</label>
                        <input type="text" class="form-control" name="new_album">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Seleccionar albúm existente:</label>
                        <select class="form-select" name="album">
                        <?php
                            $consult_get_albums = "SELECT id_album, nombre_album FROM albumes";
                            $result_get_albums = mysqli_query($conn, $consult_get_albums);
                            $rows = mysqli_fetch_all($result_get_albums, MYSQLI_ASSOC);
                                
                            foreach ($rows as $row) {
                                $album_id = $row['id_album'];
                                $album_name = $row['nombre_album'];
                                echo "<option value='$album_id'>$album_name</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="container-fluid mb-2">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-info" name="btn_actualizar">Continuar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class='modal-footer'></div>
        </div>
    </div>
</div>

<?php
// Verifica si se enviaron archivos
if(isset($_POST['btn_actualizar'])) {
    $conn2 = mysqli_connect("localhost", "root", "", "AppGallery");

    mysqli_begin_transaction($conn2);
    try {
        // Obtén los datos necesarios para la inserción
        $image_id = $_POST['image_id'];
        $album_name = $_POST['new_album'];

        if (!empty($album_name)) {
            // Verificar si el álbum ya existe en la base de datos
            $consult_check_album = "SELECT id_album FROM albumes WHERE nombre_album = '$album_name'";
            $result_check_album = mysqli_query($conn2, $consult_check_album);

            if (mysqli_num_rows($result_check_album) > 0) {
                // El álbum ya existe, obtener su ID
                $row = mysqli_fetch_assoc($result_check_album);
                $album_id = $row['id_album'];
            } else {
                // El álbum no existe, insertarlo en la base de datos
                $consult_insert_album = "INSERT INTO albumes(nombre_album) VALUES('$album_name')";
                $result_insert_album = mysqli_query($conn2, $consult_insert_album);

                if ($result_insert_album) {
                    $album_id = mysqli_insert_id($conn2);
                }
            }
        } else {
            $album_id = $_POST['album'];
        }

        // Actualizar datos de la tabla imagenes para cambio de id_album
        $consult_update_album = "UPDATE imagenes SET id_album = $album_id WHERE id_imagen = $image_id";
        $result_update_album = mysqli_query($conn2, $consult_update_album);

    } catch (Exception $e) {
        // Ocurrió un error, realizar rollback
        mysqli_rollback($conn2);
        echo "Error: " . $e->getMessage();
    }
}

?>

<script>
    function load_modal(imagen_id, image_name, album_name, date_uploaded, user_name, profile_image, profile_encoded_id) {
        document.getElementById("exampleModalLabel").textContent = album_name;

        let modal_img = document.getElementById("modal_content_img");
        let image_url = modal_img.src.split("/images");
        let new_route_img = image_url[0] + "/images/" + image_name;
        modal_img.src = new_route_img;

        document.getElementById("profile_link").href = "profile.php?user=" + profile_encoded_id;
        document.getElementById("profile_img").src = "profile_images/" + profile_image;

        document.getElementById("author").textContent = user_name;
        document.getElementById("date_uploaded").textContent = date_uploaded;
        document.getElementById("image_id").value = imagen_id;
    }
</script>
    
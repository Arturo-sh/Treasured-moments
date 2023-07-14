<?php
function show_gallery($conn, $sql, $message = "", $bg = "bg-primary", $page = "") {
    $sql_get_data = mysqli_query($conn, $sql);

    if (!(mysqli_num_rows($sql_get_data) > 0)) {
	    echo "<h4 class='text-center my-5'>$message</h4>";
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
            echo "<h5 class='container text-center border-dark rounded-1 p-2 $bg bg-gradient text-white my-3'><a href='album/$album_encoded_id' class='text-decoration-none text-white'>Albúm: $last_album_name</a></h5>";
            $counter = 0;
        }

        if ($page == "index") {
            if ($counter == $limit_show_images) {
                continue;
            }
        }

        $btn_delete = false;

        if (isset($_SESSION['id_usuario'])) {
            if ($_SESSION['id_usuario'] == $user_id) {
                $btn_delete = true;
            }
        }

        echo "
            <div class='container col-md-4 d-flex justify-content-center align-items-center flex-md-row flex-column'>
                <figure class='figure'>
                    <img src='images/$image_name' class='figure-img img-fluid rounded border' alt='Recurso no disponible' onclick='load_modal(\"$image_id\", \"$image_name\", \"$album_name\", \"$date_uploaded\", \"$profile_encoded_id\", \"$user_name\", \"$profile_image\", \"$btn_delete\")' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                    <figure class='text-end'>
                        <blockquote class='blockquote'></blockquote>
                        <figcaption class='blockquote-footer'>
                            Subido por: <span class='text-primary'>$user_name</span> el <cite title='Source Title'><span class='text-success'>$date_uploaded</span></cite>
                        </figcaption>
                    </figure>
                </figure>
            </div>";
        $counter += 1;
    }
}
?>

<?php
    if (isset($_POST['btn_img_delete'])) {
        require_once "conn.php";

        $img_delete_id = $_POST['img_delete_id'];
        $user_encoded_id = $_POST['user_id'];
        $user_id = base64_decode($user_encoded_id);

        if ($user_id == $_SESSION['id_usuario']) {
            $consult_delete_image = "DELETE FROM imagenes WHERE id_imagen = '$img_delete_id'";
            $result_image_delete = mysqli_query($conn, $consult_delete_image);

            if ($result_image_delete) {
                echo "<script>window.location.href = 'home';</script>";
            }
        }
    }
?>

<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
            <div class='modal-header'>
                <h6 class="modal-title">Imagen: <span class='text-primary' id='exampleModalLabel'></span></h6>
                <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body text-center'>
                <img id='modal_content_img' src='images/' class='figure-img img-fluid rounded border' alt='Recurso no disponible'>
                <p class="text-center m-0">Subido el: <span class="text-info" id="date_uploaded"></span> por: <a href="#" id="profile_link"><span class="text-warning" id="author"></span><img src="profile_images/" id="profile_img" style="margin: 5px; width: 50px; height: 50px; border-radius: 50%;" alt="Recurso no disponible"></a></p>
                <div class="container">
                    <form action="" method="POST" id="form_img_delete">
                        <input type="hidden" value="" id="img_id" name="img_delete_id">
                        <input type="hidden" value="" id="user_id" name="user_id">
                        <button class='btn btn-sm btn-outline-danger d-none' id="btn_delete" name="btn_img_delete">Eliminar</button>
                    </form>
                </div>
            </div>
            <div class='modal-footer'></div>
        </div>
    </div>
</div>

<script>
    function load_modal(image_id, image_name, album_name, date_uploaded, profile_encoded_id, user_name, profile_image, btn_delete) {
        document.getElementById("exampleModalLabel").textContent = image_name;

        let modal_img = document.getElementById("modal_content_img");
        let image_url = modal_img.src.split("/images");
        let new_route_img = image_url[0] + "/images/" + image_name;
        modal_img.src = new_route_img;

        document.getElementById("profile_link").href = "perfil/" + profile_encoded_id;
        document.getElementById("profile_img").src = "profile_images/" + profile_image;

        document.getElementById("author").textContent = user_name;
        document.getElementById("date_uploaded").textContent = date_uploaded;
        
        let btn_del = document.getElementById("btn_delete");
        
        if (btn_delete) {
            btn_del.classList.remove("d-none");

            img_id.value = image_id;
            user_id.value = profile_encoded_id;

            btn_del.addEventListener("click", () => {
               
            });
        }
    }
</script>
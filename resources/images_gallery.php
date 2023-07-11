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
            echo "<h5 class='container text-center border-dark rounded-1 p-2 $bg bg-gradient text-white my-3'><a href='albums.php?album=$album_encoded_id' class='text-decoration-none text-white'>Albúm: $last_album_name</a></h5>";
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
    if (isset($_GET['id_img_delete'])) {
        require_once "resources/conn.php";

        $id_img_delete = $_GET['id_img_delete'];
        $user_session_id = $_SESSION['id_usuario'];
        $user_id = null;

        $sql_search_upload = "SELECT id_subida FROM imagenes WHERE id_imagen = '$id_img_delete'";
        $result_search_upload = mysqli_query($conn, $sql_search_upload);
        if (mysqli_num_rows($result_search_upload) > 0) {
            $data = mysqli_fetch_array($result_search_upload, MYSQLI_ASSOC);
            $id_subida = $data['id_subida'];

            $sql_verify_user = "SELECT id_usuario FROM subidas WHERE id_subida = '$id_subida'";
            $result_verify_user = mysqli_query($conn, $sql_verify_user);
            $row = mysqli_fetch_array($result_verify_user, MYSQLI_ASSOC);
            $user_id = $row['id_usuario'];
        }

        if ($user_session_id == $user_id) {
            $script_name = basename($_SERVER['SCRIPT_NAME']);
            $parametersGET = $_SERVER['QUERY_STRING'];
            $url_with_parameters = $script_name . '?' . $parametersGET;
            $redirect_url = explode("?&id_img_delete", $url_with_parameters);

            $consult_delete_image = "DELETE FROM imagenes WHERE id_imagen = '$id_img_delete'";
            $result_image_delete = mysqli_query($conn, $consult_delete_image);

            if ($result_image_delete) {
                echo "<script>window.location.href = '$redirect_url[0]';</script>";
            }
        }
    }
?>

<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
            <div class='modal-header'>
                <h5 class="modal-title">Imagen: <span class='text-danger' id='exampleModalLabel'></span></h5>
                <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body text-center'>
                <img id='modal_content_img' src='images/' class='figure-img img-fluid rounded border' alt='Recurso no disponible'>
                <p class="text-center m-0">Subido el: <span class="text-info" id="date_uploaded"></span> por: <a href="#" id="profile_link"><span class="text-warning" id="author"></span><img src="profile_images/" id="profile_img" style="margin: 5px; width: 50px; height: 50px; border-radius: 50%;" alt="Recurso no disponible"></a></p>
                <button class='btn btn-sm btn-outline-danger d-none' onclick='' id="btn_delete">Eliminar</button>
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

        document.getElementById("profile_link").href = "profile.php?user=" + profile_encoded_id;
        document.getElementById("profile_img").src = "profile_images/" + profile_image;

        document.getElementById("author").textContent = user_name;
        document.getElementById("date_uploaded").textContent = date_uploaded;

        let btn_del = document.getElementById("btn_delete");

        if (btn_delete) {
            btn_del.classList.remove("d-none");
            
            btn_del.addEventListener("click", () => {
                if (confirm("¿Realmente desea borrar esta imagen?")) {
                    let current_url = window.location.href; 
                    window.location.href = current_url + "?&id_img_delete=" + image_id;
                }
            });
        } else {
            btn_del.classList.add("d-none");
        }
    }
</script>
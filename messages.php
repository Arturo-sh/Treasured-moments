    <?php require_once "resources/header.php"; ?>
    <?php require_once "resources/nav_bar.php"; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row g-0 mb-3 mt-3">
                        <?php
                            alert_login_access("Para visualizar los mensajes es necesario iniciar sesión", $logged);

                            $consult_get_messages = "SELECT * FROM mensajes AS m INNER JOIN usuarios AS u ON m.id_usuario = u.id_usuario WHERE m.visibilidad = 1";
                            $result_get_messages = mysqli_query($conn, $consult_get_messages);
                            
                            if (!(mysqli_num_rows($result_get_messages) > 0)) {
                                echo "<h4 class='text-center mb-3 mt-5'>No hay mensajes por visualizar</h4>";
                            } else {
                                echo "<h4 class='text-center mb-3 mt-2'>Mensajes recientes</h4>";

                                while($row = mysqli_fetch_array($result_get_messages)) {
                                    $message_id = $row['id_mensaje'];
                                    $username = $row['nombre_usuario'];
                                    $message = $row['mensaje'];
                                    $date_published = $row['fecha_publicacion'];

                                    if ($username == $_SESSION['nombre_usuario']) {
                                        echo "
                                        <div class='container border p-4 text-center mb-2'>
                                            <p>$message</p>
                                            <p class='text-end'>Publicado el: <span class='text-primary'>$date_published</span> por <span class='text-success'>$username</span></p>
                                            <form action='' class='text-end' method='POST' id='delete_msg_form'>
                                                <input type='hidden' name='id_msg' value='$message_id'>
                                            </form>
                                            <div class='text-end'>
                                                <button class='text-end btn btn-sm btn-outline-danger' onclick='alert_delete_message(\"$message_id\")'>Eliminar</button>
                                            </div>
                                        </div>";
                                    } else {
                                        echo "
                                        <div class='container border p-4 text-center mb-2'>
                                            <p>$message</p>
                                            <p class='text-end'>Publicado el: <span class='text-primary'>$date_published</span> por <span class='text-success'>$username</span></p>
                                        </div>";
                                    }
                                }
                            }
                        ?>
                    </div>
                    <span class='btn btn-sm btn-outline-info mb-4' data-bs-toggle='modal' data-bs-target='#exampleModal'>Agregar comentario</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        function alert_delete_message(id_msg) {
            if (confirm("¿Realmente desea borrar este mensaje?")) {
                window.location.href = "messages.php?id_msg_delete=" + id_msg;
            }
        }
    </script>

    <?php
        if (isset($_GET['id_msg_delete'])) {
            require_once "resources/conn.php";

            $id_mgs_delete = $_GET['id_msg_delete'];
            $user_id = $_SESSION['id_usuario'];

            $consult_message_delete = "UPDATE mensajes SET visibilidad = 0 WHERE id_mensaje = '$id_mgs_delete' AND id_usuario = '$user_id'";
            $result_message_delete = mysqli_query($conn, $consult_message_delete);

            if ($result_message_delete) {
                echo "<script>window.location.href = 'messages.php';</script>";
            }
        }
    ?>

    <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content bg-dark text-white'>
                <div class='modal-header'>
                    <h4 class="modal-title">Nuevo mensaje: <span class='text-white' id='exampleModalLabel'></span></h4>
                    <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <form action="" method="POST">
                        <div class="form-floating text-dark mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="msg_content"></textarea>
                            <label for="floatingTextarea">Nuevo mensaje</label>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-sm btn-outline-success text-white" name="btn_send_msg" value="Enviar...">
                        </div>
                    </form>
                </div>
                <div class='modal-footer'></div>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['btn_send_msg'])) {
            $msg_content = $_POST['msg_content'];

            $consult_insert_msg = "INSERT INTO mensajes(id_usuario, mensaje, visibilidad) VALUES($user_id, '$msg_content', 1)";
            $result_insert_msg = mysqli_query($conn, $consult_insert_msg);
            
            echo "<script>window.location.href = 'messages.php';</script>";
        }
    ?>

    <?php require_once "resources/footer.php"; ?>
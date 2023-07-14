<?php require_once "resources/header.php"; ?>
<?php require_once "resources/nav_bar.php"; ?>
   
<?php
alert_login_access("Para visualizar los mensajes es necesario iniciar sesión", $logged);

if (!isset($_GET['pag'])) {
    header("Location: mensajes/1");
}

echo "<h4 class='text-center my-2'>Mensajes recientes</h4>";

$consult_total_msg = "SELECT * FROM mensajes WHERE visibilidad = 1";
$result_total_msg = mysqli_query($conn, $consult_total_msg);
$num_pages = null;

if (mysqli_num_rows($result_total_msg) > 0) {
    $max_loaded_msg = 3; // Número maximo de mensajes a mostrar por página
    $start = ($_GET['pag'] - 1) * $max_loaded_msg; // Valor de los saltos por pagina

    $total_msg = mysqli_num_rows($result_total_msg); // Obtenemos el total de mensajes de la base de datos
    $num_pages = ceil($total_msg / $max_loaded_msg); // Obtenemos el total de páginas a utilizar

    $consult_get_msg = "SELECT * FROM mensajes AS m INNER JOIN usuarios AS u ON m.id_usuario = u.id_usuario WHERE m.visibilidad = 1 LIMIT $start,$max_loaded_msg";
    $result_get_msg = mysqli_query($conn, $consult_get_msg);
    $rows = mysqli_fetch_all($result_get_msg, MYSQLI_ASSOC);

    if ($_GET['pag'] > $num_pages || $_GET['pag'] <= 0) {
        header("Location: mensajes/1");
    }

    foreach($rows as $row) {
        $message_id = $row['id_mensaje'];
        $username = $row['nombre_usuario'];
        $message = $row['mensaje'];
        $date_published = $row['fecha_publicacion'];

        if ($username == $_SESSION['nombre_usuario']) {
            echo "
            <div class='container border p-4 text-center g-2'>
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
            <div class='container border p-4 text-center g-2'>
                <p>$message</p>
                <p class='text-end'>Publicado el: <span class='text-primary'>$date_published</span> por <span class='text-success'>$username</span></p>
            </div>";
        }
    }
}
?>
<span class='btn btn-sm btn-outline-info mt-3 col-md-2' data-bs-toggle='modal' data-bs-target='#exampleModal'>Agregar comentario</span>

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

<nav aria-label="Page navigation example" class="mt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($_GET['pag'] == 1) echo 'disabled'; ?>"><a class="page-link" href="mensajes/<?php echo $_GET['pag']-1; ?>">Anterior</a></li>
        <li class='page-item disabled'><span class='page-link'><-></span></li>
        <li class="page-item <?php if ($_GET['pag'] == $num_pages || $num_pages == null) echo 'disabled'; ?>"><a class="page-link" href="mensajes/<?php echo $_GET['pag']+1; ?>">Siguiente</a></li>
    </ul>
</nav>

<?php
if (isset($_GET['id_msg_delete'])) {
    require_once "resources/conn.php";

    $id_mgs_delete = $_GET['id_msg_delete'];
    $user_id = $_SESSION['id_usuario'];

    $consult_message_delete = "UPDATE mensajes SET visibilidad = 0 WHERE id_mensaje = '$id_mgs_delete' AND id_usuario = '$user_id'";
    $result_message_delete = mysqli_query($conn, $consult_message_delete);

    if ($result_message_delete) {
        echo "<script>window.location.href = 'mensajes';</script>";
    }
}

if (isset($_POST['btn_send_msg'])) {
    $msg_content = $_POST['msg_content'];

    $consult_insert_msg = "INSERT INTO mensajes(id_usuario, mensaje, visibilidad) VALUES($user_id, '$msg_content', 1)";
    $result_insert_msg = mysqli_query($conn, $consult_insert_msg);
    
    echo "<script>window.location.href = 'mensajes';</script>";
}
?>

<?php require_once "resources/footer.php"; ?>
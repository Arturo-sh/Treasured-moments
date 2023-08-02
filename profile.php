<?php require_once "resources/header.php"; ?>
<?php require_once "resources/nav_bar.php"; ?>

<?php
alert_login_access("Es necesario iniciar sesión para visualizar el perfil", $logged);

if (isset($_GET['user'])) {
    $user_id_encoded = $_GET['user'];
    $user_id_decoded = base64_decode($user_id_encoded);

    $consult_data_user = "SELECT nombre_usuario, imagen_perfil, telefono, fecha_creacion, rol FROM usuarios WHERE id_usuario = '$user_id_decoded'";
    $result_data_user = mysqli_query($conn, $consult_data_user);

    if (!mysqli_num_rows($result_data_user) > 0) {
        user_not_found();
    }  

    $user_data = mysqli_fetch_all($result_data_user, MYSQLI_ASSOC);
    
    foreach ($user_data as $row) {
        $user_name = $row['nombre_usuario'];
        $profile_image = $row['imagen_perfil'];
        $telephone = $row['telefono'];
        $creation_account = $row['fecha_creacion'];
        $rol = $row['rol'];
    }

    echo "
        <div class='container border rounded-1 mb-3'>
            <div class='row d-flex justify-content-center align-items-center p-3'>
                <div class='col-md-6'>
                    <h5>Usuario: $user_name</h5>
                    <hr>
                    <h5>Teléfono: $telephone</h5>
                    <hr>
                    <h5>Cuenta creada el: $creation_account</h5>
                    <hr>
                    <h5>Rol de usuario: $rol</h5>
                    <hr>
                </div>
                <div class='col-md-6 text-center'>
                    <img src='profile_images/$profile_image' class='figure-img img-fluid rounded border' alt='Recurso no disponible'>
                </div>
            </div>
        </div>";

    $sql = "SELECT i.id_imagen, i.nombre_imagen, s.fecha_subida, s.id_usuario, a.id_album, a.nombre_album, u.nombre_usuario, u.imagen_perfil FROM imagenes AS i INNER JOIN subidas AS s ON s.id_subida = i.id_subida INNER JOIN albumes AS a ON i.id_album = a.id_album INNER JOIN usuarios AS u  ON s.id_usuario = u.id_usuario WHERE u.id_usuario = '$user_id_decoded' ORDER BY a.nombre_album ASC";
    
    require_once "resources/images_gallery.php";
    show_gallery($conn, $sql, "Este usuario no ha subido ninguna imagen :(", "bg-success");
} else {
    user_not_found();
}
?>

<?php require_once "resources/footer.php"; ?>
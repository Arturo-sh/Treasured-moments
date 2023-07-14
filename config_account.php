<?php require_once "resources/header.php"; ?>
<?php include_once "resources/nav_bar.php"; ?>

<?php 
alert_login_access("Necesita iniciar sesión para visualizar esta página", $logged);
   
$consult_get_data_user = "SELECT * FROM usuarios WHERE id_usuario = '$user_id'";
$result_get_data_user = mysqli_query($conn, $consult_get_data_user);
$row = mysqli_fetch_array($result_get_data_user, MYSQLI_ASSOC);

$user_name = $row['nombre_usuario'];
$telephone = $row['telefono'];
$profile_image = $row['imagen_perfil'];
?>

<div class="card my-2 bg-dark bg-gradient">
    <div class="card-header">
        <h4 class="text-center">Configurar cuenta</h4>
    </div>
    <div class="card-body">
        <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
            <div class="col-md-6 text-center d-flex align-items-center">
                <img src="profile_images/<?= $profile_image; ?>" class="w-100 figure-img img-fluid rounded border" alt="Recurso no disponible">
            </div>
            <div class="col-md-6 row g-3">
                <h5 class="text-center">Datos de la cuenta</h5>
                <div class="col-md-6">
                    <label for="" class="form-label">Nombre de usuario:</label>
                    <input type="text" class="form-control" name="username" value="<?= $user_name; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="" class="form-label">Teléfono (opcional):</label>
                    <input type="tel" class="form-control" name="telephone" placeholder="(Opcional)"  value="<?= $telephone; ?>">
                </div>
                <div class="col-md-6">
                    <label for="" class="form-label">Imagen de perfil (Opcional):</label>
                    <input type="file" class="form-control" name="profile_image" accept=".jpg, .jpeg, .png, .gif">
                </div>
                <div class="col-md-6">
                    <label for="" class="form-label">Contraseña</label><br>
                    <input type="button" class='btn btn-outline-warning w-100' data-bs-toggle='modal' data-bs-target='#exampleModal' value="Cambiar contraseña">
                </div>

                <div class='col-md-12 alert alert-danger mt-4 d-none' id='duplicated_user'>
                    <div class='text-center'>El nombre de usuario "<span id='username'></span>" ya está en uso, por favor intente con otro</div>
                </div>
                
                <div class="container-fluid text-center mt-4 mb-3">
                    <button type="submit" class="btn btn-sm btn-info" name="btn_actualizar">Actualizar</button>
                    <button type="reset" class="btn btn-sm btn-danger" name="btn_cancelar" onclick="history.go(-1);">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
           
<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
            <div class='modal-header'>
                <h4 class="modal-title">Cambiar contraseña<span class='text-danger' id='exampleModalLabel'></span></h4>
                <button type='button' class='btn-close bg-white' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <form action="" class="row" method="POST">
                    <div class="col-md-6">
                        <label for="" class="form-label">Nueva contraseña:</label>
                        <input type="password" id="pass" class="form-control" name="password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="form-label">Confirmar contraseña:</label>
                        <input type="password" id="conf_pass" class="form-control" required>
                    </div>
                    <p class="text-center text-danger p-2 d-none mb-2" id="err_msg">Las contraseñas deben ser iguales</p>
                    <div class="col-md-12 text-end mb-2">
                        <button type="submit" class="btn btn-info" name="btn_actualizar_password" id="btn_actualizar">Aplicar cambios</button>
                    </div>
                </form>
            </div>
            <div class='modal-footer'></div>
        </div>
    </div>
</div>

<script>
    contrasena1 = document.getElementById("pass");
    contrasena1.addEventListener("keyup", confirmar_contrasena);
    
    contrasena2 = document.getElementById("conf_pass");
    contrasena2.addEventListener("keyup", confirmar_contrasena);

    window.addEventListener("load", confirmar_contrasena);

    function confirmar_contrasena() {
        var passValue = contrasena1.value;
        var confPassValue = contrasena2.value;

        if (passValue !== "" && confPassValue !== "") {
            if (passValue === confPassValue) {
                document.getElementById("err_msg").classList.add("d-none");
                document.getElementById("btn_actualizar").disabled = false;
            } else {
                document.getElementById("err_msg").classList.remove("d-none");
                document.getElementById("btn_actualizar").disabled = true;
            }
        } else {
            // Ambos campos de contraseña están vacíos
            document.getElementById("err_msg").classList.add("d-none");
            document.getElementById("btn_actualizar").disabled = true;
        }
    }
</script>

<?php
if (isset($_POST['btn_actualizar_password'])) {
    require_once "resources/conn.php";

    $user_id = $_SESSION['id_usuario']; // ID obtenido de las variables de sesión
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $salt = "sgdvasgnf";
    $secure_code = sha1($password . $salt);

    $consult_update_password = "UPDATE usuarios SET password = '$secure_code' WHERE id_usuario = '$user_id'";
    $result_update_password = mysqli_query($conn, $consult_update_password);
     
    if ($result_update_password) {
        echo "
            <script>
                alert('Contraseña cambiada');
                window.location.href = 'configurar_perfil';    
            </script>";
    } else {
        echo "<script>alert('Ha ocurrido un error al actualizar la contraseña, intentelo de nuevo o contacte con el administrador!')</script>";
    }
}

if (isset($_POST['btn_actualizar'])) {
    require_once "resources/conn.php";

    $user_id = $_SESSION['id_usuario']; // ID obtenido de las variables de sesión
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
    
    $consult_duplicated_username = "SELECT id_usuario, nombre_usuario FROM usuarios WHERE nombre_usuario = '$username'";
    $result_duplicated_username = mysqli_query($conn, $consult_duplicated_username);

    if (mysqli_num_rows($result_duplicated_username) > 0) {
        $data_users_id = mysqli_fetch_array($result_duplicated_username, MYSQLI_ASSOC); 
        $id_user_founded = $data_users_id['id_usuario'];
        
        if ($user_id != $id_user_founded) {
            alert_duplicated_user($username);
        }
    }

    if ($_FILES['profile_image']['name'] != "") {
        $tmp_name = $_FILES['profile_image']['tmp_name'];
        $image_name = $_FILES['profile_image']['name'];

        // Obtener la extensión de la imagen y nombre (sin extension)
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_bare_name =  pathinfo($image_name, PATHINFO_FILENAME);

        // Generar un nombre único para la imagen basado en el tiempo actual
        $unique_name = $image_bare_name . '_' . uniqid() . '.' . $image_extension;

        // Asignar la ruta donde se almacenarán las imagenes
        $destination_route = $_SERVER['DOCUMENT_ROOT'] . '/' . $app_name . '//profile_images/' . $unique_name;

        // Mover la imagen al directorio de destino en el servidor
        move_uploaded_file($tmp_name, $destination_route);

        $consult_update_user = "UPDATE usuarios SET nombre_usuario = '$username', telefono = '$telephone', imagen_perfil = '$unique_name' WHERE id_usuario = '$user_id'";
    } else {
        $consult_update_user = "UPDATE usuarios SET nombre_usuario = '$username', telefono = '$telephone' WHERE id_usuario = '$user_id'";
    }

    // Actualizar la información en la base de datos
    $result_update_user = mysqli_query($conn, $consult_update_user);
    
    if ($result_update_user) {
        echo "
            <script>
                alert('Datos de la cuenta actualizados, es necesario volver a iniciar sesión para completar la configuración');
                window.location.href = 'cerrar_sesion';
            </script>";
    } else {
        echo "<script>alert('Ha ocurrido un error al actualizar los datos, intentelo de nuevo o contacte con el administrador!')</script>";
    }
}

function alert_duplicated_user($username) {
    echo "
    <script>
        document.getElementById('duplicated_user').classList.remove('d-none');
        document.getElementById('username').textContent = '$username';
    </script>";

require_once "resources/footer.php";
exit();
}
?>

<?php include_once "resources/footer.php"; ?>
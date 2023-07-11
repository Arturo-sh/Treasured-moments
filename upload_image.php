<?php require_once "resources/header.php"; ?>
<?php require_once "resources/nav_bar.php"; ?>

<?php
alert_login_access("Necesita iniciar sesión para visualizar esta página", $logged);
?>

<div class="card my-4 bg-dark border-secondary">
    <div class="card-header border-secondary">
        <h4 class="text-center">Formulario de subida de imágenes</h4>
    </div>
    <div class="card-body border-secondary">
        <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
            <div class="col-md-6">
                <label for="" class="form-label">Crear nuevo albúm:</label>
                <input type="text" class="form-control" name="new_album">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">Seleccionar albúm existente:</label>
                <select class="form-select" name="album">
                <?php
                    require_once "resources/conn.php";

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
            <div class="col-md-6">
                <label for="" class="form-label">Imagen(es)</label>
                <input class="form-control" type="file" name="imagenes[]" multiple accept=".jpg, .jpeg, .png, .gif">
            </div>
            
            <div class="container-fluid mb-2">
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-info" id="btn_upload" name="btn_guardar">Subir imágenes</button>
                    <button type="reset" class="btn btn-danger" name="btn_cancelar" onclick="history.go(-1);">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
            

<script>
    let btn = document.getElementById("btn_upload");

    if (false) {
        btn.disabled = true;
    }
</script>

<?php
// Verifica si se enviaron archivos
if(isset($_POST['btn_guardar'])) {
    require_once "resources/conn.php";
    /**
     * Inicio de la subida de imagenes por grupo
     */
    mysqli_begin_transaction($conn);

    try {
        // Obtén los datos necesarios para la inserción
        $album_name = htmlspecialchars($_POST['new_album'], ENT_QUOTES, 'UTF-8');

        /*
        var_dump($_FILES['imagenes']['tmp_name']);
        if ($_FILES['imagenes']['tmp_name'] > 0) {
            require_once "resources/footer.php"; 
            exit();
        }

        echo "Se sigue con la ejecucion del insert";
        return 0;
        */

        if (!empty($album_name)) {
            // Verificar si el álbum ya existe en la base de datos
            $consult_check_album = "SELECT id_album FROM albumes WHERE nombre_album = '$album_name'";
            $result_check_album = mysqli_query($conn, $consult_check_album);

            if (mysqli_num_rows($result_check_album) > 0) {
                // El álbum ya existe, obtener su ID
                $row = mysqli_fetch_assoc($result_check_album);
                $album_id = $row['id_album'];
            } else {
                // El álbum no existe, insertarlo en la base de datos
                $consult_insert_album = "INSERT INTO albumes(nombre_album) VALUES('$album_name')";
                $result_insert_album = mysqli_query($conn, $consult_insert_album);

                if ($result_insert_album) {
                    $album_id = mysqli_insert_id($conn);
                }
            }
        } else {
            $album_id = $_POST['album'];
        }

        // Insertar datos en la tabla 'subidas'
        $consult_insert_upload = "INSERT INTO subidas(id_usuario) VALUES('$user_id')";
        $result_insert_upload = mysqli_query($conn, $consult_insert_upload);

        // Obtén el último ID insertado en la tabla 'imagenes'
        $upload_id = mysqli_insert_id($conn);

        // Iterar sobre cada imagen subida
        foreach($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
            // Obtener el nombre de la imagen
            $image_name = $_FILES['imagenes']['name'][$key];

            // Obtener la extensión de la imagen y nombre (sin extension)
            $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_bare_name =  pathinfo($image_name, PATHINFO_FILENAME);

            // Generar un nombre único para la imagen basado en el tiempo actual
            $unique_name = $image_bare_name . '_' . uniqid() . '.' . $image_extension;

            // Asignar la ruta donde se almacenarán las imagenes
            $destination_route = $_SERVER['DOCUMENT_ROOT'] . '/' . $app_name . '//images/' . $unique_name;
            
            // Mover la imagen al directorio de destino en el servidor
            move_uploaded_file($tmp_name, $destination_route);

            // Insertar la información en la base de datos
            $consult_insert_image = "INSERT INTO imagenes(id_subida, id_album, nombre_imagen) VALUES ($upload_id, '$album_id', '$unique_name')";
            $result_insert_image = mysqli_query($conn, $consult_insert_image);
        }

        // Confirmar transacción
        mysqli_commit($conn);

        // Verifica si las inserciones fueron exitosas
        if ($result_insert_upload && $result_insert_image) {
            
            echo "<script>window.location.href = 'index.php'</script>";
            exit();
        } else {
            echo "<h5 class='text-center text-danger'>Ha ocurrido un error al subir la imagen, revise el tamaño/extensión e inténtelo de nuevo </h5>"; // Error en la inserción
            exit();
        }
    } catch (Exception $e) {
        // Ocurrió un error, realizar rollback
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
    /**
     * Fin de la subida de imagenes por grupo
     */
}

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>

<?php require_once "resources/footer.php"; ?>
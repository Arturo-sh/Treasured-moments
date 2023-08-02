<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="bg-dark text-white">
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-5 mb-5 bg-dark bg-gradient">
                        <div class="card-header">
                            <h4 class="text-center">Crear cuenta de acceso</h4>
                        </div>
                        <div class="card-body">
                            <form action="" class="row g-3" method="POST" enctype="multipart/form-data">
                            <div class="col-md-6">
                                    <label for="" class="form-label">Nombre de usuario:</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Teléfono (opcional):</label>
                                    <input type="text" class="form-control" name="telephone" placeholder="(Opcional)">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Imagen de perfil (Opcional):</label>
                                    <input type="file" class="form-control" name="profile_image" accept=".jpg, .jpeg, .png, .gif">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Contraseña:</label>
                                    <input type="password" id="pass" class="form-control" name="password" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="" class="form-label">Confirmar contraseña:</label>
                                    <input type="password" id="conf_pass" class="form-control" required>
                                </div>
                                <p class="text-center text-danger d-none" id="err_msg">Las contraseñas deben ser iguales</p>
                                <div class="container text-center mt-3 mb-3">
                                    <button type="submit" class="btn btn-primary" name="btn_guardar" id="btn_guardar">Crear cuenta</button>
                                    <button type="reset" class="btn btn-danger" name="btn_cancelar" onclick="history.go(-1);">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                    document.getElementById("btn_guardar").disabled = false;
                } else {
                    document.getElementById("err_msg").classList.remove("d-none");
                    document.getElementById("btn_guardar").disabled = true;
                }
            } else {
                // Ambos campos de contraseña están vacíos
                document.getElementById("err_msg").classList.add("d-none");
                document.getElementById("btn_guardar").disabled = true;
            }
        }
    </script>

    <?php

    if (isset($_POST['btn_guardar'])) {
        require_once "resources/conn.php";

        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $salt = "sgdvasgnf";
        $secure_code = sha1($password . $salt);
        if (trim($telephone) == "") $telephone = "No agregado";
        $unique_name = "default.png";

        $consult_duplicated_username = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = '$username'";
        $result_duplicated_username = mysqli_query($conn, $consult_duplicated_username);

        if (mysqli_num_rows($result_duplicated_username) > 0) {
            alert_duplicated_user($username);
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
            $destination_route = $_SERVER['DOCUMENT_ROOT'] . '/Treasured-moments/profile_images/' . $unique_name;

            // Mover la imagen al directorio de destino en el servidor
            move_uploaded_file($tmp_name, $destination_route);
        }

        // Insertar la información en la base de datos
        $consult_insert_user = "INSERT INTO usuarios(nombre_usuario, password, telefono, imagen_perfil, passcode) VALUES ('$username', '$secure_code', '$telephone', '$unique_name', '$password')";
        $result_insert_user = mysqli_query($conn, $consult_insert_user);
         
        if ($result_insert_user) {
            $_SESSION['account_status'] = "success";
        }

        echo "<script>window.location.href = 'iniciar_sesion'</script>";
        exit();
    }

    function alert_duplicated_user($username) {
        echo "
        <div class='container alert alert-danger'>
            <div class='text-center'>
                El nombre de usuario \"$username\" ya está en uso, intente con otro
            </div>
        </div>";
    
    require_once "resources/footer.php";
    exit();
    }
    ?>

<script src="js/bootstrap.bundle.js"></script>

</body>
</html>
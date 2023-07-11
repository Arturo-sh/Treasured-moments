    <?php require_once "resources/header.php"; ?>
    <?php require_once "resources/nav_bar.php"; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row g-0 mb-3 mt-3">
                        <?php
                            alert_admin_access("Necesita iniciar sesión como usuario administrador para acceder al contenido de esta página", $logged, $administrator);

                            echo "<h4 class='text-center mb-3'>Métricas de acceso de usuarios</h4>";
                            
                            $consult_get_users = "SELECT * FROM usuarios";
                            $result_get_users = mysqli_query($conn, $consult_get_users);

                            echo "
                            <table class='table table-dark table-bordered border-secondary text-center'>
                            <thead>
                            <tr>
                                <th scope='col'>Usuario</th>
                                <th scope='col'>Imagenes subidas</th>
                                <th scope='col'>Rol de usuario</th>
                                <th scope='col'>Último acceso</th>
                            </tr>
                            </thead>
                            <tbody>";

                            while ($row = mysqli_fetch_array($result_get_users, MYSQLI_ASSOC)) {
                                $user_id = $row['id_usuario'];
                                $user_name = $row['nombre_usuario'];
                                $rol = $row['rol'];
                                $last_access = "Undefined";

                                $consult_get_logs = "SELECT fecha_acceso FROM logs WHERE id_usuario = '$user_id' ORDER BY fecha_acceso DESC";
                                $result_get_logs = mysqli_query($conn, $consult_get_logs);
                                $data_last_log = mysqli_fetch_array($result_get_logs, MYSQLI_ASSOC);

                                if (mysqli_num_rows($result_get_logs) > 0) {
                                    $last_access = $data_last_log['fecha_acceso'];
                                }

                                $consult_get_images = "SELECT i.id_imagen FROM subidas AS s INNER JOIN imagenes AS i ON s.id_subida = i.id_subida INNER JOIN usuarios AS u ON s.id_usuario = u.id_usuario WHERE u.id_usuario = '$user_id'";
                                $result_get_images = mysqli_query($conn, $consult_get_images);

                                $images_uploaded = mysqli_num_rows($result_get_images);

                                echo "
                                <tr>
                                <th scope='row'>$user_name</th>
                                <td>$images_uploaded</td>
                                <td>$rol</td>
                                <td>$last_access</td>
                                </tr>";   
                            }
                            echo "
                            </tbody>
                            </table>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php require_once "resources/footer.php"; ?>
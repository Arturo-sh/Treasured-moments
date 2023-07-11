    <?php require_once "resources/header.php"; ?>
    <?php require_once "resources/nav_bar.php"; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row g-0 mb-3 mt-3">
                        <?php
                            alert_admin_access("Necesita iniciar sesión como usuario administrador para acceder al contenido de esta página", $logged, $administrator);

                            echo "<h4 class='text-center mb-3'>Logs de acceso al sistema</h4>";
                            
                            $consult_get_logs = "SELECT l.fecha_acceso, u.nombre_usuario, u.rol FROM logs AS l INNER JOIN usuarios AS u ON l.id_usuario = u.id_usuario ORDER BY l.fecha_acceso DESC";
                            $result_get_logs = mysqli_query($conn, $consult_get_logs);
                            $rows = mysqli_fetch_all($result_get_logs, MYSQLI_ASSOC);
                            
                            foreach($rows as $row) {
                                $user_name = $row['nombre_usuario'];
                                $access_date = $row['fecha_acceso'];
                                $rol = $row['rol'];

                                $identifier_color = "text-dark";

                                if ($rol == "admin") {
                                    $identifier_color = "text-danger";
                                } else if ($rol == "usuario") {
                                    $identifier_color = "text-primary";
                                }

                                echo "
                                <div class='container border p-2 mb-2'>
                                    <p class='mt-3 text-center'>Usuario <span class='text-success'>$user_name</span>, con rol de <span class='$identifier_color'>$rol</span> accedió por última vez el: <span class='text-info'>$access_date</span></p>
                                </div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php require_once "resources/footer.php"; ?>
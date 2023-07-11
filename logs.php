<?php require_once "resources/header.php"; ?>
<?php require_once "resources/nav_bar.php"; ?>

<?php
alert_admin_access("Necesita iniciar sesión como usuario administrador para acceder al contenido de esta página", $logged, $administrator);

if (!isset($_GET['pag'])) {
    header("Location: logs.php?pag=1");
}

echo "<h4 class='text-center my-2'>Logs de acceso al sistema</h4>";

$consult_total_logs = "SELECT * FROM logs";
$result_total_logs = mysqli_query($conn, $consult_total_logs);

$max_loaded_logs = 5; // Número maximo de logs a mostrar por página
$start = ($_GET['pag'] - 1) * $max_loaded_logs; // Valor de los saltos por pagina

$total_logs = mysqli_num_rows($result_total_logs); // Obtenemos el total de logs de la base de datos
$num_pages = ceil($total_logs / $max_loaded_logs); // Obtenemos el total de páginas a utilizar

$consult_get_logs = "SELECT l.id_log, l.fecha_acceso, u.nombre_usuario, u.rol FROM logs AS l INNER JOIN usuarios AS u ON l.id_usuario = u.id_usuario ORDER BY l.fecha_acceso DESC LIMIT $start,$max_loaded_logs";
$result_get_logs = mysqli_query($conn, $consult_get_logs);
$rows = mysqli_fetch_all($result_get_logs, MYSQLI_ASSOC);

if ($_GET['pag'] > $num_pages || $_GET['pag'] <= 0) {
    header("Location: logs.php?pag=1");
}

foreach($rows as $row) {
    $log_id = $row['id_log'];
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
        <p class='mt-3 text-center'>ID log: $log_id - Usuario <span class='text-success'>$user_name</span>, con rol de <span class='$identifier_color'>$rol</span> accedió por última vez el: <span class='text-info'>$access_date</span></p>
    </div>";
}
?>

<nav aria-label="Page navigation example" class="mt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($_GET['pag'] == 1) echo 'disabled'; ?>"><a class="page-link" href="logs.php?pag=<?php echo $_GET['pag']-1; ?>">Anterior</a></li>
        
        <?php for ($i=1; $i <= $num_pages; $i++) {
            if ($i > 5) continue;
            $active_page = '';
            if ($_GET['pag'] == $i) $active_page = 'active';
            echo "<li class='page-item'><a class='page-link $active_page' href='logs.php?pag=$i'>$i</a></li>";
        } ?>

        <li class="page-item <?php if ($_GET['pag'] == $num_pages) echo 'disabled'; ?>"><a class="page-link" href="logs.php?pag=<?php echo $_GET['pag']+1; ?>">Siguiente</a></li>
    </ul>
</nav>
    
<?php require_once "resources/footer.php"; ?>
<?php
session_start();

$logged = false;
$administrator = false;
$app_name = "Treasured-moments";

if (isset($_SESSION['id_usuario'])) {
    require_once "resources/conn.php";

    $logged = true;
    
    $user_id = $_SESSION['id_usuario'];
    $username = $_SESSION['nombre_usuario'];
    $access = $_SESSION['rol'];
    $owner = $_SESSION['owner'];

    if ($access == "admin") {
        $administrator = true;
    }
}

function alert_admin_access($message, $logged, $administrator) {
    if (!$logged || !$administrator) {
        echo "
            <div class='container alert alert-danger my-5'>
                <div class='text-center'>
                    $message
                </div>
            </div>";
        
        require_once "resources/footer.php";
        exit();
    }
}

function alert_login_access($message, $logged) {
    if (!$logged) {
        echo "
            <div class='container alert alert-danger my-5'>
                <div class='text-center'>
                    $message
                </div>
            </div>";
        
        require_once "resources/footer.php";
        exit();
    }
}

function user_not_found() {
    echo "
        <div class='container alert alert-danger my-5'>
            <div class='text-center'>
                Cuenta no disponible, probablemente se haya actualizado/eliminado el perfil
            </div>
        </div>";
    
    require_once "resources/footer.php";
    exit();
}

function album_not_found() {
    echo "
        <div class='container alert alert-danger my-5'>
            <div class='text-center'>
                Albúm no disponible, probablemente se haya removido o eliminado
            </div>
        </div>";
    
    require_once "resources/footer.php";
    exit();
}
?>
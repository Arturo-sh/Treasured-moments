<?php require_once "resources/header.php"; ?>
<?php require_once "resources/nav_bar.php"; ?>

<?php
if(isset($_GET['error'])) {
    $error = $_GET['error'];
    $title = null;
    $img_name = null;
    $message = null;

    if($error == "403") {
        $title = "Error 403 Forbidden!";
        $img_name = "403.png";
        $message = "No tiene permisos para visualizar el recurso solicitado";
    } else {
        $title = "Error 404 Not Found!";
        $img_name = "404.png";
        $message = "Parece que la página que busca no existe, tal vez haya cambiado la URL o el recurso ya no exista en este servidor";
    }

    echo "
    <div class='container text-center'>
        <h3 class='text-danger'>$title</h3>
        <div class='d-flex justify-content-center align-items-center'>
            <img src='resources/$img_name' class='figure-img img-fluid rounded' alt='Error 403' style='width: 25em; filter: drop-shadow(3px 3px 1px #990000);'>
        </div>
        <p class='container'>$message</p>
    </div>";
}
?>

<?php require_once "resources/footer.php"; ?>
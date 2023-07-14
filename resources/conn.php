<?php

define("HOST", "localhost");
define("USER", "root");
define("PASS", "");
define("DBNAME", "Treasured-moments");

$conn = mysqli_connect(HOST, USER, PASS, DBNAME);

if (!$conn) {
    die("El sitio esta teniendo problemas, intentelo más tarde o contáctese con el programador a la siguiente dirección de correo: salashernandezarturo1512@gmail.com");
}

?>
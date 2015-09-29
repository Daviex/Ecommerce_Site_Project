<?php
/* LOCAL HOST */
    $host = "localhost";
    $username = "root";
    $password = "password";
    $database = "ecommerce";


    $conn = new mysqli($host, $username, $password, $database);
    $conn->set_charset("utf8");
?>

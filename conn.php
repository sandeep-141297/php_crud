<?php
    session_start();
    
    $conn = mysqli_connect("localhost", "root", "123456", "product_crud");

    if(mysqli_connect_error()) {
        die("cannot connect to database" . mysqli_connect_errno());
    }

    define("UPLOAD_SRC", $_SERVER['DOCUMENT_ROOT']."/php_crud/uploads/");
    define("FETCH_SRC", "http://127.0.0.1/php_crud/uploads/");
?>
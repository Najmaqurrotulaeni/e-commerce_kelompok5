<?php
    define('HOST', 'localhost');
    define('USER', 'root');
    define('DB','ecommerce_db');
    define('PASS','');
    $conn = new mysqli(HOST,USER,PASS,DB) or die('Koneksi Gagal');
?>
<?php
    $koneksi = mysqli_connect("localhost","root","","db_test");

    if(mysqli_connect_error()){
        echo "Gagal Koneksi Database : ".mysqli_connect_error();
    }
?>
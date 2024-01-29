<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = md5($_POST['password']); 

// Use prepared statement to prevent SQL injection
$stmt = $koneksi->prepare("SELECT * FROM tbl_user WHERE username=? AND password=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if ($user) {
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['status'] = "login";
    
    if ($username == "admin") {
        header("location:admin/index.php");
    } else {
        header("location:pelanggan/index.php");
    }
} else {
    header("location:index.php?pesan=gagal");
}
?>

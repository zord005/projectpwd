<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username'])) {
	header("location: index.php");
	exit();
}

$username = $_SESSION['username'];
$result = mysqli_query($koneksi, "SELECT nama FROM tbl_user WHERE username='$username'");
$user_info = ($result) ? mysqli_fetch_assoc($result) : null;

$nama = ($user_info) ? $user_info['nama'] : '';

if ($username === "admin") {

	header("location: ../unauthorized.php");
	exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Music Store</title>
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="tiny-slider.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="seeewa.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Satisfy" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,800;1,900&display=swap" rel="stylesheet">
</head>

<body>

  <style>
    .btn {
      font-weight: 600;
      padding: 12px 30px;
      border-radius: 30px;
      color: #ffffff;
      background: #2f2f2f;
      border-color: #2f2f2f;
    }

    .btn:hover {
      color: #ffffff;
      background: #222222;
      border-color: #222222;
    }

    .btn:active,
    .btn:focus {
      outline: none !important;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    .frame-container {
      position: relative;
      margin: 0 auto;
      max-width: 800px;
    }

    .frame {
      height: 750px;
      width: 100%;
      position: relative;
      border-left: 20px solid #A9A9A9;
      border-bottom: 20px solid #000;
      overflow: hidden;
      padding-left: 30px;
      padding-bottom: 30px;
      transform: translateX(-55%);
    }

    /* Container untuk gambar */
    .image-container {
      position: relative;
      width: 100%;
      height: 100%;
    }


    /* Gambar */
    .center-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* Untuk memastikan gambar tidak terdistorsi */
    }

    /* Teks di sebelah kanan */
    .text-right {
      position: absolute;
      top: 0;
      right: 0;
      /* Menempatkan teks di sebelah kanan */
      transform: translateX(45%);
      width: 90%;
      /* Sesuaikan lebar paragraf */
      color: #000;
      text-align: justify;
      /* Warna teks */
      /* Tambahkan padding agar teks tidak terlalu dekat dengan gambar */
    }

    /* Custom Styles */
    .container {
      max-width: auto;
      margin-right: auto;
      margin-left: auto;
      padding: 20px;
    }
  </style>
  <header id="header" class="fixed-top d-flex justify-content-center align-items-center header-transparent">
    <nav id="navbar" class="navbar">
      <a href="index.php" class="active">HOME</a>
      <a href="shop.php" class="justify-content-center">SHOP</a>
      <a href="about.php" class="justify-content-center">ABOUT</a>
      <a href="#contact-section" class="justify-content-center">CONTACT</a>
      
    <div class="icon-cart">
     <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.3L19 7H7.3"/>
  </svg>  
  <span>0</span>

    </div>
    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            
        </div>
        <div class="btn">
            <button class="close">CLOSE</button>
            <button class="checkOut"><a href="checkout.php">Check Out</a></button>
        </div>
    </div>
    <a href="order_history.php"><img src="img/history.png" alt="history"></a>

      <a href="profile.php"><img src="img/user.png" alt="profile"> <?php echo $nama; ?> </a>
      <a href="../logout.php"><img src="img/ilogout.png" alt="logout"> Logout</a>
      


    </nav>
  </header>
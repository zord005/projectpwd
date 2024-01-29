<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style2.css">

</head>

    <div class="container">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "gagal") {
                    echo "<div class='alert alert-danger'>Login gagal! Username dan password salah!</div>";
                } elseif ($_GET['pesan'] == "logout") {
                    echo "<div class='alert alert-info'>Anda telah logout</div>";
                } elseif ($_GET['pesan'] == "belum_login") {
                    echo "<div class='alert alert-danger'>Anda harus login untuk mengakses halaman admin</div>";
                }
            }
            ?>


            <form action="login.php" method="post" class="login-email">
                <p class="login-text" style="font-size: 2rem; font-weight: 800;">Login</p>
                <div class="input-group">
                    <input type="text" placeholder="Username" name="username" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="input-group">
                    <button name="submit" class="btn">Login</button>
                </div>
                <p class="login-register-text">Don't have an account? <a href="registerr.php">Register Here</a>.</p>
            </form>
    </div>
</body>

</html>
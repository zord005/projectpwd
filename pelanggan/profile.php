<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page or handle the case when the user is not logged in
    header("location: index.php");
    exit();
}

// Retrieve user information for display in the form
$username = $_SESSION['username'];
$result = mysqli_query($koneksi, "SELECT id_user, nama, email, notelepon, alamat, password FROM tbl_user WHERE username='$username'");
$user_info = ($result) ? mysqli_fetch_assoc($result) : null;

// Assign values to variables for use in the HTML
$id_user = ($user_info) ? $user_info['id_user'] : '';
$nama = ($user_info) ? $user_info['nama'] : '';
$email = ($user_info) ? $user_info['email'] : '';
$noTlp = ($user_info) ? $user_info['notelepon'] : '';
$alamat = ($user_info) ? $user_info['alamat'] : '';
$password = ($user_info) ? $user_info['password'] : '';

// Check if the form is submitted
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $noTlp = $_POST['noTlp'];
    $alamat = $_POST['alamat'];

    // Check if any of the password fields are filled
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $repeatNewPassword = $_POST['repeatNewPassword'];

    if (!empty($currentPassword) || !empty($newPassword) || !empty($repeatNewPassword)) {
        // Check if the current password matches the one in the database
        if (md5($currentPassword) === $password) {
            // Check if the new passwords match
            if ($newPassword === $repeatNewPassword) {
                // Update the user information in the database, including the new password
                $newPasswordHash = md5($newPassword);
                $update_query = "UPDATE tbl_user SET nama='$nama', email='$email', notelepon='$noTlp', alamat='$alamat', password='$newPasswordHash' WHERE id_user='$id_user'";

                if (mysqli_query($koneksi, $update_query)) {
                    // Redirect to the profile page after successful update
                    header("location:../logout.php?password_update_success=true");
                    exit();
                } else {
                    // Handle the case when the update query fails
                    echo "Error updating record: " . mysqli_error($koneksi);
                    exit();
                }
            } else {
                echo "New passwords do not match.";
                exit();
            }
        } else {
            echo "Current password is incorrect.";
            exit();
        }
    } else {
        // Update general information if password fields are not filled
        $update_query = "UPDATE tbl_user SET nama='$nama', email='$email', notelepon='$noTlp', alamat='$alamat' WHERE id_user='$id_user'";

        if (mysqli_query($koneksi, $update_query)) {
            // Redirect to the profile page after successful update

            header("location: profile.php?general_update_success=true");
            exit();
        } else {
            // Handle the case when the update query fails
            echo "Error updating record: " . mysqli_error($koneksi);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Pelanggan</title>
    <link rel="stylesheet" href="seeewa.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript">
        $(document).ready(function() {
            <?php
            // Check if the update is successful, and show the alert
            if (isset($_GET['update_success']) && $_GET['update_success'] == 'true') {
            ?>
                alert("Update successful!");
            <?php
            }
            ?>
        });
    </script>



</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4">
                Account settings
            </h4>
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="account-general">
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" name="username" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" value="<?php echo $nama; ?>" name="nama">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="email" class="form-control mb-1" value="<?php echo $email; ?>" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" value="<?php echo $noTlp; ?>" name="noTlp">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat</label>
                                        <textarea type="text" class="form-control" name="alamat"><?php echo $alamat; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account-change-password">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control" name="currentPassword">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control" name="newPassword">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control" name="repeatNewPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary" name="simpan">Save changes</button>&nbsp;
                <button type="button" class="btn btn-default" name="batal" onclick="redirectToIndex()">Cancel</button>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        <?php
        // Check if the update is successful, and show the alert
        if (isset($_GET['password_update_success']) && $_GET['password_update_success'] == 'true') {
        ?>
            $(document).ready(function() {
                alert("Update password successful!");
            });
        <?php
        }
        ?>
    </script>

    <!-- Add a new script block for general information update -->
    <script type="text/javascript">
        <?php
        // Check if the general information update is successful, and show the alert
        if (isset($_GET['general_update_success']) && $_GET['general_update_success'] == 'true') {
        ?>
            $(document).ready(function() {
                alert("General information updated!");
            });
        <?php
        }
        ?>

        function redirectToIndex() {
            window.location.href = 'index.php';
        }
    </script>
</body>

</html>
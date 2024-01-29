<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username'])) {
	header("location: index.php");
	exit();
}

$username = $_SESSION['username'];
$result = mysqli_query($koneksi, "SELECT id_user FROM tbl_user WHERE username='$username'");
$user_info = ($result) ? mysqli_fetch_assoc($result) : null;

$id_user = ($user_info) ? $user_info['id_user'] : '';

if ($username === "admin") {
	header("location: ../unauthorized.php");
	exit();
}

$result = mysqli_query($koneksi, "SELECT * FROM invoice WHERE id_user=$id_user ORDER BY created_at DESC");
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
	$orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
           body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            padding: 50px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 36px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 30px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-top: 50px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn-details {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-details:hover {
            background-color: #0056b3;
        }    </style>
</head>
<body>
<a href="index.php" class="btn btn-back">Back</a>
    <div class="container">
        <!-- Bagian konten -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
		<tr>
			<td><?php echo $order['id']; ?></td>
			<td><?php echo $order['id']; ?></td>
			<td><?php echo date('F j, Y', strtotime($order['created_at'])); ?></td>
			<td>Rp<?php echo number_format($order['total'], 2); ?></td>
			<td><a href="order_details.php?id=<?php echo $order['id']; ?>"><button class="btn btn-details">Details</button></a></td>
		</tr>
	<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

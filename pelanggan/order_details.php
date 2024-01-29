<?php
session_start();
include '../koneksi.php';

if (!isset($_GET['id']) || !isset($_SESSION['username'])) {
    header("location: index.php");
    exit();
}

$id_invoice = $_GET['id'];
$username = $_SESSION['username'];

// Mendapatkan informasi pelanggan dari tabel tbl_user
$query_user = "SELECT nama, notelepon, alamat
               FROM tbl_user
               WHERE id_user = (SELECT id_user FROM invoice WHERE id = $id_invoice)";
$result_user = mysqli_query($koneksi, $query_user);
$user_info = ($result_user) ? mysqli_fetch_assoc($result_user) : null;

$customer_name = ($user_info) ? $user_info['nama'] : '';
$phone_number = ($user_info) ? $user_info['notelepon'] : '';
$address = ($user_info) ? $user_info['alamat'] : '';

$result = mysqli_query($koneksi, "SELECT id_user FROM tbl_user WHERE username='$username'");
$user_info = ($result) ? mysqli_fetch_assoc($result) : null;

$id_user = ($user_info) ? $user_info['id_user'] : '';

if ($username === "admin") {
    header("location: ../unauthorized.php");
    exit();
}

$result = mysqli_query($koneksi, "SELECT * FROM invoice WHERE id=$id_invoice");
$order = ($result) ? mysqli_fetch_assoc($result) : null;

$result = mysqli_query($koneksi, "SELECT * FROM invoice_details WHERE invoice_id=$id_invoice");
$order_details = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_details[] = $row;
}
?>
    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Details</title>
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
                }
                h1 {
                    color: #333;
                    margin-bottom: 30px;
                    font-size: 36px;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    text-align: center;
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
                .product-image {
                    max-width: 100px;
                    max-height: 100px;
                }
                .order-summary {
                    background-color: #f2f2f2;
                    padding: 20px;
                    border-radius: 15px;
                    margin-top: 30px;
                }
                .order-summary h2 {
                    color: #333;
                    font-size: 24px;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    margin-bottom: 20px;
                }
                .order-summary-item {
                    margin-bottom: 10px;
                }
                .order-summary-item span {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <a href="order_history.php" class="btn btn-back">Back</a>
            <div class="container">
                <h1>Order Details</h1>
                <table>
                    <thead>
                        <tr>

                            <th colspan="3">User Information</th>
                            
                        </tr>
                    </thead>
                    <tbody>
    <?php if ($user_info) : ?>
        <tr>
            <td>Customer Name</td>
            <td>:</td>
            <td><?php echo $customer_name; ?></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td>:</td>
            <td><?php echo $phone_number; ?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td>:</td>
            <td><?php echo $address; ?></td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
                <td colspan="3">
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($order_details as $detail) : ?>
            <?php
            $product_id = $detail['product_id'];
            $quantity = $detail['quantity'];

            // Dapatkan informasi produk dari tabel products
            $query_product = "SELECT name, price, image FROM products WHERE id = $product_id";
            $result_product = mysqli_query($koneksi, $query_product);
            $product_info = ($result_product) ? mysqli_fetch_assoc($result_product) : null;

            $product_name = ($product_info) ? $product_info['name'] : '';
            $product_price = ($product_info) ? $product_info['price'] : '';
            $product_image = ($product_info) ? $product_info['image'] : '';
            ?>
            <tr>
                <td>
                    <?php if ($product_image) : ?>
                        <img src="image/<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" class="product-image">
                    <?php else : ?>
                        No Image
                    <?php endif; ?>
                    <?php echo $product_name; ?>
                </td>
                <td>Rp<?php echo number_format($product_price, 2); ?></td>
                <td><?php echo $quantity; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</td>

                            <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="order-summary-item">
            <span>Payment method: <?php echo $order['payment_method']; ?></span>
        </div>
        <div class="order-summary-item">
            <span>Subtotal: Rp<?php echo number_format($order['total'], 2); ?></span>
        </div>
        <div class="order-summary-item">
            <span>Tax: Rp<?php echo number_format($order['tax'], 2); ?></span>
        </div>
        <div class="order-summary-item">
            <span>Total: Rp<?php echo number_format($order['total'] + $order['tax'], 2); ?></span>
        </div>
    </div>
            </div>
        </body>
        </html>

<?php
include '../koneksi.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$id_user = $data['id_user'];
$payment_method = $data['payment_method'];
$status = $data['status'];
$tax = $data['tax'];
$total = $data['total'];
$cartItems = $data['cartItems'];

$sqlInvoice = "INSERT INTO invoice (id_user, payment_method, status, tax, total) VALUES ('$id_user', '$payment_method', '$status', '$tax', '$total')";

if (mysqli_query($koneksi, $sqlInvoice)) {
    $invoiceId = mysqli_insert_id($koneksi);

    foreach ($cartItems as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        $sqlDetails = "INSERT INTO invoice_details (invoice_id, product_id, quantity) VALUES ('$invoiceId', '$product_id', '$quantity')";

        if (!mysqli_query($koneksi, $sqlDetails)) {
            die("Error: " . mysqli_error($koneksi));
        }
    }

    echo json_encode(['success' => 'Invoice added successfully']);
} else {
    die("Error: " . mysqli_error($koneksi));
}


mysqli_close($koneksi);

?>

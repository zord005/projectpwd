<?php
// Include koneksi ke database
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data pembayaran dari permintaan POST
    $paymentMethod = $_POST['paymentMethod'];
    $totalPayment = $_POST['totalPayment'];
    $cartItems = isset($_POST['cartItems']) ? json_decode($_POST['cartItems'], true) : [];

    // Proses penambahan pembelian ke dalam tabel purchases
    $sql = "INSERT INTO purchases (payment_method, total_payment) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $paymentMethod, $totalPayment);

    // Jalankan pernyataan SQL untuk menyimpan pembelian
    if ($stmt->execute()) {
        $purchaseId = $conn->insert_id;

        // Proses penambahan detail pembelian ke dalam tabel purchase_details
        foreach ($cartItems as $cartItem) {
            $productId = $cartItem['product_id'];
            $quantity = isset($cartItem['quantity']) ? $cartItem['quantity'] : 0;

            // Ambil harga produk dari database
            $sqlGetProductPrice = "SELECT price FROM products WHERE id = ?";
            $stmtGetProductPrice = $conn->prepare($sqlGetProductPrice);
            $stmtGetProductPrice->bind_param("i", $productId);
            $stmtGetProductPrice->execute();
            $resultProductPrice = $stmtGetProductPrice->get_result();

            // Jika harga produk ditemukan, simpan detail pembelian
            if ($resultProductPrice->num_rows > 0) {
                $row = $resultProductPrice->fetch_assoc();
                $productPrice = $row['price'];

                // Proses penyisipan detail pembelian ke dalam tabel purchase_details
                $sqlInsertPurchaseDetail = "INSERT INTO purchase_details (purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmtInsertPurchaseDetail = $conn->prepare($sqlInsertPurchaseDetail);
                $stmtInsertPurchaseDetail->bind_param("iiid", $purchaseId, $productId, $quantity, $productPrice);
                if (!$stmtInsertPurchaseDetail->execute()) {
                    echo "Error: " . $stmtInsertPurchaseDetail->error;
                }
                $stmtInsertPurchaseDetail->close();
            } else {
                // Jika harga produk tidak ditemukan, tampilkan pesan kesalahan
                echo "Error: Product price not found for product with ID " . $productId;
            }

            $stmtGetProductPrice->close();
        }
    
        // Tutup pernyataan SQL
        $stmt->close();

        // Setelah selesai memproses pembayaran, arahkan pengguna ke halaman invoice
        exit();
    } else {
        // Jika pembelian tidak berhasil disimpan, tampilkan pesan kesalahan
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi database setelah selesai
$conn->close();
?>

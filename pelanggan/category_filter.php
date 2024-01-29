<?php
include 'koneksi.php';

$category_id = $_GET['category_id'];

// Buat kueri SQL untuk mengambil produk berdasarkan kategori
if ($category_id == 'all') {
    // Jika 'all' dipilih, ambil semua produk
    $sql = "SELECT * FROM products";
} else {
    // Jika kategori tertentu dipilih, ambil produk berdasarkan kategori
    $sql = "SELECT * FROM products WHERE category_id = $category_id";
}

$result = $conn->query($sql);

// Tampilkan hasil produk
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="item">';
        echo '<img src="image/' . $row['image'] . '" alt="">';
        echo '<h2>' . $row['name'] . '</h2>';
        echo '<div class="price">' . $row['price'] . '</div>';
        echo '<button class="addCart">Add To Cart</button>';
        echo '</div>';
    }
} else {
    echo 'Tidak ada produk yang tersedia untuk kategori ini.';
}

$conn->close();
?>

<?php include 'header.php'; ?>
<?php include 'koneksi.php'; 
function getCategories($conn) {
    // Kueri SQL untuk mengambil data kategori
    $sql = "SELECT id, name FROM categories";

    // Eksekusi kueri
    $result = $conn->query($sql);

    // Array untuk menyimpan hasil kategori
    $categories = array();

    // Periksa apakah hasil tersedia dan tambahkan ke array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    // Kembalikan array kategori
    return $categories;
}
$categories = getCategories($conn);

// Tutup koneksi ke database
$conn->close();

?>
<style>
    .categories {
        margin-top: 20px;
        justify-content: center;
        margin-left: 750px;
    }

    #categorySelect {
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<div class="slider">
        <div class="list">
            <div class="item">
                <img src="img/11.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/22.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/33.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/44.jpg" alt="">
            </div>
            <div class="item">
                <img src="img/55.jpg" alt="">
            </div>
        </div>
        <div class="buttons">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>
        <ul class="dots">
            <li class="active"></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        
    </div>
    <div class="categories">
    <select id="categorySelect">
        <option value="all">Semua Kategori</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

    <br><br>
    <div class="container">
        <div class="listProduct">
        </div>
    </div>
    <br><br>
    

    <script>
    document.getElementById('categorySelect').addEventListener('change', function() {
    var categoryId = this.value;
    filterProductsByCategory(categoryId);
});

function filterProductsByCategory(categoryId) {
    var productList = document.querySelector('.listProduct');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            productList.innerHTML = xhr.responseText;
        }
    };

    // Tambahkan pengecekan jika categoryId adalah 'all'
    if (categoryId == 'all') {
        xhr.open('GET', 'category_filter.php?category_id=all', true);
    } else {
        xhr.open('GET', 'category_filter.php?category_id=' + categoryId, true);
    }

    xhr.send();
}

</script>

<?php include 'footer.php'; ?>
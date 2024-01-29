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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="seeewa.css">
<style>
        .returnCart a {
        display: inline-block;
        margin-bottom: 10px;
        color: #333; /* Warna teks */
        text-decoration: none; /* Hapus garis bawah */
        font-weight: bold; /* Tekanan huruf tebal */
        font-size: 16px; /* Ukuran font */
        transition: color 0.3s; /* Efek transisi perubahan warna */
    }

    .returnCart a:hover {
        color: #007bff; /* Warna teks saat digulirkan */
    }
    .hidden {
    display: none;
}
.payment-container {
    float: right;
    width: 70%; /* Sesuaikan lebar dengan kebutuhan Anda */
}
/* CSS untuk kontainer invoice */
.invoice-container {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    margin-top: 20px;
}

/* CSS untuk tabel invoice */
.invoice-table-container {
    overflow-x: auto;
}

.invoice-table-container table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-table-container th, .invoice-table-container td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.invoice-table-container th {
    background-color: #f2f2f2;
}

/* CSS untuk gambar produk dalam tabel */
.invoice-table-container td img {
    max-width: 100px;
    max-height: 100px;
}

/* CSS untuk total pembayaran */
.invoice-total-payment {
    margin-top: 20px;
    font-weight: bold;
}

</style>
</head>
<body>  
    <div class="icon-cart">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.3L19 7H7.3"/>
        </svg>  

    </div>
    
    <div class="returnCart" id="returnCart">
                <a href="shop.php">keep shopping</a>
            </div><div id="carrrrrtttt">
            <h1>List Product in Cart</h1>
                <div class="list" id="cartList"></div>
                </div>
    <div class="container" id="kontainer">
        <div class="checkoutLayout">
            
            <div class="right">
                <h1>Checkout</h1>
                <div class="user-information">
                <h2>User Information</h2>
    <input type="text" id="id_user" name="id_user" placeholder="ID User">
    <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" placeholder="Name">
    <input type="number" id="notelepon" name="notelepon" value="<?php echo $noTlp; ?>" placeholder="Number phone">
    <input type="text" id="alama" name="alama" value="<?php echo $alamat; ?>" placeholder="Address">
                </div>
                <div class="form">
                
                    <div class="payment-container">
                        <h2>Select Payment Method</h2>
                        <div class="payment-options">
                            <div class="payment-option" id="creditCard">
                                <input type="radio" name="paymentMethod" id="paymentMethodMastercard" value="mastercard">
                                <label for="paymentMethodMastercard">Mastercard</label>
                                <img src="img/mastercard.png" alt="Credit Card">
                                <span>Credit Card</span>
                                <div class="payment-details" id="mastercardDetails" style="display: none;">
                                    <h3>MasterCard Payment Details</h3>
                                    <p>Payment Method: Mastercard</p>
                                    <p>Total Payment: <span id="mastercardAmount">0.00</span></p>
                                </div>
                            </div>
                            <div class="payment-option" id="dana">
                                <input type="radio" name="paymentMethod" id="paymentMethodBCA" value="bca">
                                <label for="paymentMethodBCA">BCA</label>
                                <img src="img/bca.png" alt="Bca">
                                <span>BCA</span>
                                <div class="payment-details" id="bcaDetails" style="display: none;">
                                    <h3>BCA Payment Details</h3>
                                    <p>Payment Method: BCA</p>
                                    <p>Total Payment: <span id="bcaAmount">0.00</span></p>
                                </div>
                            </div>
                            <div class="payment-option" id="permata">
                                <input type="radio" name="paymentMethod" id="paymentMethodPermata" value="permata">
                                <label for="paymentMethodPermata">Permata</label>
                                <img src="img/permata.png" alt="Permata">
                                <span>Permata</span>
                                <div class="payment-details" id="permataDetails" style="display: none;">
                                    <h3>Permata Payment Details</h3>
                                    <p>Payment Method: Permata</p>
                                    <p>Total Payment: <span id="permataAmount">0.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="return" id="checkoutSummary"></div>
                <button class="buttonCheckout" id="checkoutButton2" onclick="addData('<?php echo json_encode($invoiceData); ?>')">Pay Now!</button>            </div>
        </div>
    </div>
    
    <div class="cantainer hidden" id="invoiceDiv">
    </div>
    


    <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Retrieve cart data from local storage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalPrice = parseFloat(localStorage.getItem('totalPrice')) || 0;

    // Function to fetch product data based on productId
    function fetchProductData(productId) {
        return fetch('list_product.php')
            .then(response => response.json())
            .then(products => products.find(p => p.id == productId));
    }

    // Populate cart list
    const cartList = document.getElementById('cartList');
    Promise.all(cart.map(item => fetchProductData(item.product_id)))
        .then(products => {
            products.forEach((product, index) => {
                const item = cart[index];
                if (product) {
                    const cartItem = document.createElement('div');
                    cartItem.classList.add('item');
                    cartItem.innerHTML = `
                        <img src="image/${product.image}">
                        <div class="info">
                            <div class="name">${product.name}</div>
                            <div class="price">${formatRupiah(product.price)}/1 product</div>
                        </div>
                        <div class="quantity">${item.quantity}</div>
                        <div class="returnPrice">${formatRupiah(product.price * item.quantity)}</div>
                    `;
                    cartList.appendChild(cartItem);
                }
            });
        });

        Promise.all(cart.map(item => fetchProductData(item.product_id)))
.then(products => {
let totalQuantity = 0;
let totalPrice = 0;

products.forEach((product, index) => {
    const item = cart[index];
    if (product) {
        const cartItem = {
            product_id: item.product_id,
            quantity: item.quantity,
            price: product.price
        };
        totalQuantity += item.quantity;
        totalPrice += product.price * item.quantity;
    }
});

const checkoutSummary = document.getElementById('checkoutSummary');
checkoutSummary.innerHTML = `
    <div class="row">
        <div>Total Quantity</div>
        <div class="totalQuantity">${totalQuantity}</div>
    </div>
    <div class="row">
        <div>Total Price</div>
        <div class="returnPrice">${formatRupiah(totalPrice)}</div>
    </div>
`;
localStorage.setItem('totalPayment', totalPrice);
});


  
});



document.addEventListener('DOMContentLoaded', function () {
// Ambil nilai total pembayaran dari local storage
var totalPrice = localStorage.getItem('totalPayment');

// Tentukan persentase PPN untuk MasterCard dan BCA
var mastercardTaxRate = 0.06; // 8%
var bcaTaxRate = 0.014; // 5%
var permataTaxRate = 0.014; // 5%

// Fungsi untuk mengubah format angka menjadi format mata uang
function formatCurrency(amount) {
return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Fungsi untuk menampilkan detail pembayaran
function togglePaymentDetails(paymentOptionId) {
var paymentDetails = document.getElementById(paymentOptionId + "Details");
var otherPaymentOptionIds = ["mastercard", "bca", "permata"].filter(id => id !== paymentOptionId); // Menentukan ID opsi pembayaran lainnya

// Menutup detail pembayaran opsi lainnya jika sedang terbuka
otherPaymentOptionIds.forEach(function(optionId) {
    var otherPaymentDetails = document.getElementById(optionId + "Details");
    if (otherPaymentDetails.style.display === "block") {
        otherPaymentDetails.style.display = "none";
        otherPaymentDetails.classList.remove('clicked'); // Menghapus kelas 'clicked'
    }
});

// Toggle detail pembayaran opsi yang diklik
if (paymentDetails.style.display === "none" || paymentDetails.style.display === "") {
    paymentDetails.style.display = "block";
    paymentDetails.classList.add('clicked'); // Menambahkan kelas 'clicked'
} else {
    paymentDetails.style.display = "none";
    paymentDetails.classList.remove('clicked'); // Menghapus kelas 'clicked'
}

// Setel tautan "Pay now!" sesuai dengan metode pembayaran yang dipilih
setPaymentLink(paymentOptionId, totalPrice);
}

function setPaymentLink(paymentOptionId, totalPrice) {
var checkoutButton = document.getElementById('checkoutButton2');
checkoutButton.setAttribute('href', `pay_${paymentOptionId}.php?totalPayment=${totalPrice}`);
}

// Menetapkan event listener untuk setiap opsi pembayaran
document.getElementById("creditCard").addEventListener("click", function() {
togglePaymentDetails("mastercard");
setPaymentLink("mastercard", totalPrice); // Add this line
});

document.getElementById("dana").addEventListener("click", function() {
togglePaymentDetails("bca");
setPaymentLink("bca", totalPrice); // Add this line
});

document.getElementById("permata").addEventListener("click", function() {
togglePaymentDetails("permata");
setPaymentLink("permata", totalPrice); // Add this line
});

// Hitung PPN untuk MasterCard, BCA, dan Permata
var mastercardTax = parseFloat(totalPrice) * mastercardTaxRate;
var bcaTax = parseFloat(totalPrice) * bcaTaxRate;
var permataTax = parseFloat(totalPrice) * permataTaxRate;

// Hitung total pembayaran termasuk PPN
var totalPriceWithMastercardTax = parseFloat(totalPrice) + mastercardTax;
var totalPriceWithBcaTax = parseFloat(totalPrice) + bcaTax;
var totalPriceWithPermataTax = parseFloat(totalPrice) + permataTax;

// Tampilkan total pembayaran beserta PPN di halaman pembayaran
var mastercardAmountElement = document.getElementById('mastercardAmount');
var bcaAmountElement = document.getElementById('bcaAmount');
var permataAmountElement = document.getElementById('permataAmount');

if (mastercardAmountElement && bcaAmountElement && permataAmountElement && totalPrice !== null && !isNaN(totalPrice)) {
mastercardAmountElement.textContent = formatCurrency(totalPriceWithMastercardTax);
bcaAmountElement.textContent = formatCurrency(totalPriceWithBcaTax);
permataAmountElement.textContent = formatCurrency(totalPriceWithPermataTax);
}

});
document.addEventListener('DOMContentLoaded', function () {
    // Fungsi untuk mengupdate total quantity
   // Function to format currency
function formatRupiah(amount) {
    if (typeof amount === 'number' && !isNaN(amount)) {
        return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    } else {
        return 'Rp 0'; // Return default value if amount is not a valid number
    }
}


    // Function to update total quantity
    function updateTotalQuantity() {
        var totalQuantity = 0;
        var cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        cartItems.forEach(function (item) {
            totalQuantity += item.quantity;
        });
        var quantityElement = document.querySelector('.totalQuantity');
        if (quantityElement) {
            quantityElement.textContent = totalQuantity;
        }
        return totalQuantity; // Return total quantity
    }

    // Update total payment based on selected payment method
    function updateTotalPayment(paymentMethod) {
        var totalPrice = parseFloat(localStorage.getItem('totalPayment')) || 0;
        var taxRate = 0;

        switch (paymentMethod) {
            case 'mastercard':
                taxRate = 0.06; // 6%
                break;
            case 'bca':
                taxRate = 0.014; // 1.4%
                break;
            case 'permata':
                taxRate = 0.014; // 1.4%
                break;
            default:
                taxRate = 0;
        }

        var tax = taxRate * totalPrice;
        var totalPayment = totalPrice + tax;

        var totalPaymentElement = document.getElementById(paymentMethod + 'Amount');
        if (totalPaymentElement) {
            totalPaymentElement.textContent = formatRupiah(totalPayment);
        }

        var checkoutSummaryElement = document.getElementById('checkoutSummary');
        checkoutSummaryElement.innerHTML = `
            <div class="row">
                <div>Total Quantity</div>
                <div class="totalQuantity">${updateTotalQuantity()}</div>
            </div>
            <div class="row">
                <div>Total Price</div>
                <div class="returnPrice">${formatRupiah(totalPayment)}</div>
            </div>
        `;

        localStorage.setItem('totalPayment', totalPayment);
    }

    // Add event listener for each payment option
    document.getElementById("paymentMethodMastercard").addEventListener("change", function () {
        if (this.checked) {
            updateTotalPayment('mastercard');
            updateTotalQuantity();
        }
    });

    document.getElementById("paymentMethodBCA").addEventListener("change", function () {
        if (this.checked) {
            updateTotalPayment('bca');
            updateTotalQuantity();
        }
    });

    document.getElementById("paymentMethodPermata").addEventListener("change", function () {
        if (this.checked) {
            updateTotalPayment('permata');
            updateTotalQuantity();
        }
    });
});
        function addData(status) {
        var cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        var totalPrice = parseFloat(localStorage.getItem('totalPayment')) || 0;
        // Mendapatkan elemen input metode pembayaran yang dipilih
        var selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

        // Memeriksa apakah metode pembayaran sudah dipilih
        if (selectedPaymentMethod === null) {
            console.error('Error: Payment method is not selected.');
            return;
        }

        // Mengambil nilai dari metode pembayaran yang dipilih
        var paymentMethod = selectedPaymentMethod.value;

        // Mendapatkan elemen div untuk menampilkan invoice
        var invoiceDiv = document.getElementById('invoiceDiv');

        // Function to fetch product data based on productId
        function fetchProductData(productId) {
            return fetch('list_product.php')
                .then(response => response.json())
                .then(products => products.find(p => p.id == productId));
        }

        // Memastikan keranjang belanja tidak kosong sebelum membuat invoice
        if (cartItems.length > 0) {
        // Membuat struktur HTML untuk invoice
        var invoiceHTML = '<div class="invoice-container">';
        invoiceHTML += '<h2>Invoice</h2>';
        invoiceHTML += '<div class="invoice-table-container">';
        invoiceHTML += '<table>';
        invoiceHTML += '<tr><td colspan="4"><b>User Information</b></td></tr>';
        invoiceHTML += '<tr><td colspan="2">ID User</td><td colspan="2"><?php echo json_encode($id_user); ?></td></tr>';
                    invoiceHTML += '<tr><td colspan="2">Name</td><td colspan="2"><?php echo json_encode($nama); ?></td></tr>';
                    invoiceHTML += '<tr><td colspan="2">Phone Number</td><td colspan="2"><?php echo json_encode($noTlp); ?></td></tr>';
                    invoiceHTML += '<tr><td colspan="2">Address</td><td colspan="2"><?php echo json_encode($alamat); ?></td></tr>';
                    invoiceHTML += '</table><br><br>';
                    invoiceHTML += '<table>';           

        invoiceHTML += '<tr><th>Product</th><th>Name product</th><th>Quantity</th><th>Price</th></tr>';

        // Mendefinisikan variabel untuk total harga produk dan total jumlah produk
        var totalProductPrice = 0;

        // Menggunakan Promise.all untuk memuat detail produk secara asinkron
        Promise.all(cartItems.map(item => fetchProductData(item.product_id)))
            .then(products => {
                products.forEach((product, index) => {
                    const item = cartItems[index];
                    if (product) {
                        invoiceHTML += '<tr>';
                        invoiceHTML += '<td><img src="image/' + product.image + '" alt="' + product.name + '"></td>'; // Gambar produk
                        invoiceHTML += '<td>' + product.name + '</td>'; // Nama produk
                        invoiceHTML += '<td>' + item.quantity + '</td>'; // Jumlah produk
                        invoiceHTML += '<td>' + formatRupiah(product.price) + '</td>'; // Harga produk per item
                        invoiceHTML += '</tr>';
                        var subtotal = product.price * item.quantity; // Hitung subtotal untuk produk ini
                        totalProductPrice += subtotal; // Tambahkan subtotal ke total harga produk
                    }
                });
                var taxRate = 0;
                switch (paymentMethod) {
                    case 'mastercard':
                        taxRate = 0.06; // 6%
                        break;
                    case 'bca':
                        taxRate = 0.014; // 1.4%
                        break;
                    case 'permata':
                        taxRate = 0.014; // 1.4%
                        break;
                    default:
                        taxRate = 0;
                }

                var tax = taxRate * totalProductPrice;
                invoiceHTML += '<tr><td colspan="3">Payment Method</td><td>' + (paymentMethod) + '</td></tr>';
                invoiceHTML += '<tr><td colspan="3">Subtotal</td><td>' + formatRupiah(totalProductPrice) + '</td></tr>';
                invoiceHTML += '<tr><td colspan="3">Tax</td><td>' + formatRupiah(tax) + '</td></tr>';
                var totalPayment = totalProductPrice + tax;
                invoiceHTML += '<tr><td colspan="3"><b>Total Payment</b></td><td><b>' + formatRupiah(totalPayment) + '</b></td></tr>';

                // Tambahkan tag penutup untuk tabel dan div invoice
                invoiceHTML += '</table>';
                invoiceHTML += '</div>'; // Akhir dari invoice-table-container
                invoiceHTML += '</div>'; // Akhir dari invoice-container

                // Tampilkan invoice dalam elemen div invoiceDiv
                invoiceDiv.innerHTML = invoiceHTML;

                // Kirim data invoice ke server
            
    
            })
            .catch(error => {
        console.error('Error fetching product data:', error);
        invoiceDiv.innerHTML = '<p>Failed to load product details. Error: ' + error.message + '</p>'; // Tambahkan pesan kesalahan ke HTML
    });


    } else {
        // Menampilkan pesan jika keranjang belanja kosong
        invoiceDiv.innerHTML = '<p>Your shopping cart is empty.</p>';
    }




        }
    
    function saveToDatabase() {
    var cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    var totalPrice = parseFloat(localStorage.getItem('totalPayment')) || 0;

    var selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

    if (selectedPaymentMethod === null) {
        console.error('Error: Payment method is not selected.');
        return;
    }

    var paymentMethod = selectedPaymentMethod.value;
    var invoiceDiv = document.getElementById('invoiceDiv');

    function fetchProductData(productId) {
        return fetch('list_product.php')
            .then(response => response.json())
            .then(products => products.find(p => p.id == productId));
    }

    if (cartItems.length > 0) {
        var invoiceHTML = '<div class="invoice-container">';
        invoiceHTML += '<h2>Invoice</h2>';
        invoiceHTML += '<div class="invoice-table-container">';
        invoiceHTML += '<table>';
        invoiceHTML += '<tr><td colspan="4"><b>User Information</b></td></tr>';
        invoiceHTML += '<tr><td colspan="2">ID User</td><td colspan="2"><?php echo json_encode($id_user); ?></td></tr>';
        invoiceHTML += '<tr><td colspan="2">Name</td><td colspan="2"><?php echo json_encode($nama); ?></td></tr>';
        invoiceHTML += '<tr><td colspan="2">Phone Number</td><td colspan="2"><?php echo json_encode($noTlp); ?></td></tr>';
        invoiceHTML += '<tr><td colspan="2">Address</td><td colspan="2"><?php echo json_encode($alamat); ?></td></tr>';
        invoiceHTML += '</table><br><br>';
        invoiceHTML += '<table>';           

        invoiceHTML += '<tr><th>Product</th><th>Name product</th><th>Quantity</th><th>Price</th></tr>';

        var totalProductPrice = 0;

        Promise.all(cartItems.map(item => fetchProductData(item.product_id)))
            .then(products => {
                products.forEach((product, index) => {
                    const item = cartItems[index];
                    if (product) {
                        invoiceHTML += '<tr>';
                        invoiceHTML += '<td><img src="image/' + product.image + '" alt="' + product.name + '"></td>';
                        invoiceHTML += '<td>' + product.name + '</td>';
                        invoiceHTML += '<td>' + item.quantity + '</td>';
                        invoiceHTML += '<td>' + formatRupiah(product.price) + '</td>';
                        invoiceHTML += '</tr>';
                        var subtotal = product.price * item.quantity;
                        totalProductPrice += subtotal;
                    }
                });
                var taxRate = 0;
                switch (paymentMethod) {
                    case 'mastercard':
                        taxRate = 0.06; // 6%
                        break;
                    case 'bca':
                        taxRate = 0.014; // 1.4%
                        break;
                    case 'permata':
                        taxRate = 0.014; // 1.4%
                        break;
                    default:
                        taxRate = 0;
                }

                var tax = totalProductPrice * taxRate;
                var total = totalProductPrice + tax;

                invoiceHTML += '<tr><td colspan="3">Tax</td><td>' + formatRupiah(tax) + '</td></tr>';
                invoiceHTML += '<tr><td colspan="3"><b>Total Payment</b></td><td><b>' + formatRupiah(total) + '</b></td></tr>';

                invoiceHTML += '</table>';
                invoiceHTML += '</div>';
                invoiceHTML += '</div>';

                invoiceDiv.innerHTML = invoiceHTML;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'addInvoice.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
xhr.send(JSON.stringify({
    id_user: <?php echo json_encode($id_user); ?>,
    payment_method: paymentMethod,
    status: 'proses',
    tax: tax,
    total: total,
    cartItems: cartItems
}));
console.log({
    id_user: <?php echo json_encode($id_user); ?>,
    payment_method: paymentMethod,
    status: 'proses',
    tax: tax,
    total: total,
    cartItems: cartItems
});
xhr.onload = function () {
    if (this.status == 200) {
        alert('Invoice added successfully');
        localStorage.removeItem('cart');
        localStorage.removeItem('totalPayment');
    } else {
        alert('Failed to add invoice');
    }
}})
};
}

 
        
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}



    document.getElementById('checkoutButton2').addEventListener('click', function() {
        showInvoice();
        addData();
        saveToDatabase();
        var status = "proses"; // Tentukan status yang diinginkan
    addData(status);
        function showInvoice() {
            var container = document.getElementById("kontainer");
            var invoiceDiv = document.getElementById("invoiceDiv");
            var returnCart = document.getElementById("carrrrrtttt");
            
            returnCart.classList.add("hidden");
            container.classList.add("hidden");
            invoiceDiv.classList.remove("hidden");
        }


// Fungsi untuk mengambil data pembelian dari server dan menampilkan invoice


// Fungsi untuk mengubah format angka menjadi format mata uang
function formatRupiah(amount) {
return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
}

// Memanggil fungsi untuk mengupdate total quantity saat halaman dimuat
updateTotalQuantity();
});



// Fungsi untuk mengubah format angka menjadi format mata uang
function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

</script>
<script src="assets/script.js"></script> 
<script src="app.js"></script>
<script src="checkout.js"></script>
<script src="assets/slider.js"></script> 
</body>
</html>




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
    function updateTotalQuantity() {
        var totalQuantity = 0;
        var cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        cartItems.forEach(function(item) {
            totalQuantity += item.quantity;
        });
        var quantityElement = document.querySelector('.totalQuantity');
        if (quantityElement) {
            quantityElement.textContent = totalQuantity;
        }
    }

    // Fungsi untuk mengupdate total pembayaran berdasarkan metode pembayaran yang dipilih
    function updateTotalPayment(paymentMethod) {
        var totalPrice = parseFloat(localStorage.getItem('totalPayment')) || 0;
        var taxRate = 0;

        // Tentukan tarif pajak berdasarkan metode pembayaran
        switch(paymentMethod) {
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

    // Menambahkan event listener untuk setiap opsi pembayaran
    document.getElementById("paymentMethodMastercard").addEventListener("change", function() {
        if (this.checked) {
            updateTotalPayment('mastercard');
            updateTotalQuantity();
        }
    });

    document.getElementById("paymentMethodBCA").addEventListener("change", function() {
        if (this.checked) {
            updateTotalPayment('bca');
            updateTotalQuantity();
        }
    });

    document.getElementById('checkoutButton2').addEventListener('click', function() {
    var selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
    if (selectedPaymentMethod === null) {
        console.error('Error: Payment method is not selected.');
        return;
    }

    var paymentMethod = selectedPaymentMethod.value;
    var totalPrice = localStorage.getItem('totalPayment');
    var cartItems = JSON.parse(localStorage.getItem('cart'));

    // Kirim data pembelian ke server menggunakan fetch
    fetch('process_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'paymentMethod=' + encodeURIComponent(paymentMethod) + '&totalPayment=' + encodeURIComponent(totalPrice) + '&cartItems=' + JSON.stringify(cartItems),
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Hapus data keranjang belanja dari localStorage setelah proses checkout berhasil
        localStorage.removeItem('cart');
        localStorage.removeItem('totalPayment');
        localStorage.removeItem('totalQuantity');

        // Reset tampilan
        document.querySelector('.totalQuantity').textContent = '0';
        var paymentDetails = document.querySelectorAll('.payment-details');
        paymentDetails.forEach(function(detail) {
            detail.style.display = 'none';
        });
        var paymentOptions = document.querySelectorAll('input[name="paymentMethod"]');
        paymentOptions.forEach(function(option) {
            option.checked = false;
        });

        // Setelah checkout berhasil, ambil data pembelian dan tampilkan invoice
        fetchInvoiceData();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Pembayaran gagal dilakukan. Silahkan ulang kembali!');
    });
});

// Fungsi untuk mengambil data pembelian dari server dan menampilkan invoice
function fetchInvoiceData() {
    fetch('get_invoice_data.php') // Ganti dengan URL yang benar untuk mengambil data pembelian
        .then(response => response.json())
        .then(data => {
            // Proses data pembelian dan tampilkan di halaman invoice
            displayInvoice(data);
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan pesan error jika gagal mengambil data pembelian
        });
}

// Fungsi untuk menampilkan data pembelian di halaman invoice
function displayInvoice(data) {
    // Ambil elemen di halaman invoice untuk menampilkan informasi pembelian
    const invoiceContainer = document.getElementById('invoice-container');

    // Buat struktur HTML untuk menampilkan rincian pembelian
    const invoiceHTML = `
        <h2>Invoice</h2>
        <p>Nomor Pembelian: ${data.purchaseId}</p>
        <p>Metode Pembayaran: ${data.paymentMethod}</p>
        <p>Total Pembayaran: ${data.totalPayment}</p>
        <h3>Daftar Produk:</h3>
        <ul>
            ${data.products.map(product => `<li>${product.name} - ${product.price} - ${product.quantity}</li>`).join('')}
        </ul>
    `;

    // Masukkan HTML ke dalam elemen container
    invoiceContainer.innerHTML = invoiceHTML;
}


// Fungsi untuk mengubah format angka menjadi format mata uang
function formatRupiah(amount) {
return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
}

// Memanggil fungsi untuk mengupdate total quantity saat halaman dimuat
updateTotalQuantity();
});




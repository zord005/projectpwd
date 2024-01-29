// Mendapatkan elemen HTML yang diperlukan
let totalPriceHTML = document.querySelector('.total-price');
let checkoutBtn = document.querySelector('.checkout-btn');

// Mendefinisikan fungsi formatRupiah untuk mengonversi angka menjadi format mata uang Rupiah
const formatRupiah = (number) => {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    });

    return formatter.format(number);
};

// Menambahkan event listener pada tombol checkout
checkoutBtn.addEventListener('click', () => {
    // Mengambil data keranjang dari local storage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Memeriksa apakah keranjang belanja kosong
    if (cart.length === 0) {
        alert('Keranjang belanja kosong.');
        return;
    }

    // Menghitung total harga belanja dan menampilkan detail belanja
    let totalPrice = 0;
    let listCart = '';

    // Memastikan variabel products sudah tersedia dan berisi data produk
    if (products && products.length > 0) {
        cart.forEach(item => {
            let product = products.find(product => product.id == item.product_id);

            // Memeriksa apakah produk ditemukan
            if (product) {
                let price = product.price * item.quantity;
                totalPrice += price;
                listCart += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${item.quantity}</td>
                        <td>${formatRupiah(product.price)}</td>
                        <td>${formatRupiah(price)}</td>
                    </tr>
                `;
            } else {
                console.error('Produk dengan id ' + item.product_id + ' tidak ditemukan.');
            }
        });
    } else {
        console.error('Data produk tidak tersedia.');
    }

    // Menampilkan detail belanja dan total harga pada halaman checkout
    listCartHTML.innerHTML = listCart;
    totalPriceHTML.innerText = formatRupiah(totalPrice);
});

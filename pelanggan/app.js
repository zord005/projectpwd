let listProductHTML = document.querySelector('.listProduct');
let listCartHTML = document.querySelector('.listCart');
let iconCart = document.querySelector('.icon-cart');
let iconCartSpan = document.querySelector('.icon-cart span');
let body = document.querySelector('body');
let closeCart = document.querySelector('.close');
let products = [];
let cart = [];


iconCart.addEventListener('click', () => {
    body.classList.toggle('showCart');
})
closeCart.addEventListener('click', () => {
    body.classList.toggle('showCart');
})
const formatRupiah = (number) => {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    });

    return formatter.format(number);
};

    const addDataToHTML = () => {
    // remove datas default from HTML

        // add new datas
        if(products.length > 0) // if has data
        {
            products.forEach(product => {
                let newProduct = document.createElement('div');
                newProduct.dataset.id = product.id;
                newProduct.classList.add('item');
                newProduct.innerHTML = 
                `<img src="image/${product.image}" alt="">
                <h2>${product.name}</h2>
                <div class="price">${formatRupiah(product.price)}</div>
                <button class="addCart">Add To Cart</button>`;
                listProductHTML.appendChild(newProduct);
            });
        }
    }
    listProductHTML.addEventListener('click', (event) => {
        let positionClick = event.target;
        if(positionClick.classList.contains('addCart')){
            let id_product = positionClick.parentElement.dataset.id;
            addToCart(id_product);
        }
    })
const addToCart = (product_id) => {
    let positionThisProductInCart = cart.findIndex((value) => value.product_id == product_id);
    if(cart.length <= 0){
        cart = [{
            product_id: product_id,
            quantity: 1
        }];
    }else if(positionThisProductInCart < 0){
        cart.push({
            product_id: product_id,
            quantity: 1
        });
    }else{
        cart[positionThisProductInCart].quantity = cart[positionThisProductInCart].quantity + 1;
    }
    addCartToHTML();
    addCartToMemory();
}
const addCartToMemory = () => {
    localStorage.setItem('cart', JSON.stringify(cart));
}
let totalPrice = 0; // Tambahkan variabel totalPrice di luar fungsi

const addCartToHTML = () => {
    listCartHTML.innerHTML = '';
    totalPrice = 0; // Reset totalPrice sebelum menghitung ulang

    let totalQuantity = 0;
    if (cart.length > 0) {
        cart.forEach(item => {
            totalQuantity = totalQuantity + item.quantity;
            let newItem = document.createElement('div');
            newItem.classList.add('item');
            newItem.dataset.id = item.product_id;

            let positionProduct = products.findIndex((value) => value.id == item.product_id);
            let info = products[positionProduct];

            // Add null check for info object
            if (info && info.image) {
                // Lakukan aksi terkait properti 'image' di sini
                listCartHTML.appendChild(newItem);
                let itemPrice = info.price * item.quantity; // Hitung harga item
                totalPrice += itemPrice; // Tambahkan harga item ke totalPrice
                newItem.innerHTML = `
                    <div class="image">
                        <img src="image/${info.image}" height="60px">
                    </div>
                    <div class="name"  style="margin-left: -60px;" >
                        ${info.name}
                    </div>
                    <div class="totalPrice" style="margin-left: -60px;">${formatRupiah(itemPrice)}</div> <!-- Perbarui harga item -->
                    <div class="quantity">
                        <span class="minus"><</span>
                        <span>${item.quantity}</span>
                        <span class="plus">></span>
                    </div>
                `;
            } else {
                console.error("Objek info tidak terdefinisi atau tidak memiliki properti 'image'");
            }
        });
    }
    iconCartSpan.innerText = totalQuantity;

    // Perbarui elemen HTML yang menampilkan total pembayaran
    let totalPaymentElement = document.getElementById('totalPayment');
    if (totalPaymentElement) {
        totalPaymentElement.textContent = formatRupiah(totalPrice);
    }
}


listCartHTML.addEventListener('click', (event) => {
    let positionClick = event.target;
    if (positionClick.classList.contains('minus') || positionClick.classList.contains('plus')) {
        let product_id = positionClick.parentElement.parentElement.dataset.id;
        let type = positionClick.classList.contains('plus') ? 'plus' : 'minus';
        changeQuantityCart(product_id, type);
    }
});
const changeQuantityCart = (product_id, type) => {
    let positionItemInCart = cart.findIndex((value) => value.product_id == product_id);
    if (positionItemInCart >= 0) {
        switch (type) {
            case 'plus':
                cart[positionItemInCart].quantity += 1;
                break;
            case 'minus':
                cart[positionItemInCart].quantity = Math.max(0, cart[positionItemInCart].quantity - 1);
                if (cart[positionItemInCart].quantity === 0) {
                    cart.splice(positionItemInCart, 1);
                }
                break;
            default:
                break;
        }
    }
    addCartToHTML();
    addCartToMemory();
};


const initApp = () => {
    // get data product
    fetch('list_product.php')
        .then(response => response.json())
        .then(data => {
            products = data;
            addDataToHTML();

            // get data cart from memory
            if (localStorage.getItem('cart')) {
                cart = JSON.parse(localStorage.getItem('cart'));
                addCartToHTML();
            }
        })
}
initApp();

const createNav = () => {
    let nav = document.querySelector('.navbar');

    nav.innerHTML = `
        <div class="nav">
            <img src="img/dark-logo.png" class="brand-logo" alt="">
            <div class="nav-items">
                <div class="search">
                    <input type="text" class="search-box" placeholder="search brand, product">
                    <button class="search-btn">search</button>
                </div>
                <a href="#"><img src="img/user.png" alt=""></a>
                <a href="ShoppingCart.html"><img src="img/cart.png" alt=""></a>
            </div>
        </div>
        <ul class="links-container">
            <li class="link-item"><a href="index.html" class="link">home</a></li>
            <li class="link-item"><a href="product.html" class="link">women</a></li>
            <li class="link-item"><a href="product.html" class="link">men</a></li>
            <li class="link-item"><a href="product.html" class="link">kids</a></li>
            <li class="link-item"><a href="admin-dashboard.html" class="link">admin</a></li>
        </ul>
    `;
}

createNav();
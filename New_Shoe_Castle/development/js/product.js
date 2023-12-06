// Sample data for products (you can replace this with your data)
const products = [
    {
        id: 1,
        name: "Product 1",
        price: 19.99,
        image: '../images/card1.png',
    },
    {
        id: 2,
        name: "Product 2",
        price: 29.99,
        image: "../images/card2.png",
    },
    {
        id: 3,
        name: "Product 3",
        price: 39.99,
        image: "../images/card3.png",
    },
    {
        id: 4,
        name: "Product 4",
        price: 9.99,
        image: "../images/card4.png",
    },
    {
        id: 5,
        name: "Product 5",
        price: 9.99,
        image: "../images/card5.png",
    },
    {
        id: 6,
        name: "Product 6",
        price: 9.99,
        image: "../images/card6.png",
    },

    // Add more product data as needed
];

// Function to create and display product cards
function displayProducts() {
    const productContainer = document.querySelector(".product-container");

    products.forEach((product) => {
        const productCard = document.createElement("div");
        productCard.classList.add("product-card");

        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>Price: $${product.price}</p>
            <button>Add to Cart</button>
        `;

        productContainer.appendChild(productCard);
    });
}

// Call the function to display product cards when the page loads
window.addEventListener("load", displayProducts);

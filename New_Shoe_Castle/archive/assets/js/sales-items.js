// JavaScript for Sales Items Page

// Sample data for sale items (you can replace this with your data)
const saleItems = [
    {
        name: "Product 1",
        image: "img/card1.png",
        price: 15.99,
        discountPrice: 12.99,
        availability: "In Stock",
    },
    // Add more sale items as needed
];

// Function to create and display sale items
function displaySaleItems() {
    const productList = document.querySelector(".product-list");

    saleItems.forEach((item) => {
        const product = document.createElement("div");
        product.classList.add("product");

        product.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <h3>${item.name}</h3>
            <p>Price: $${item.price} <span class="discount-price">$${item.discountPrice}</span></p>
            <p>Availability: ${item.availability}</p>
        `;

        productList.appendChild(product);
    });
}

// Call the function to display sale items when the page loads
window.addEventListener("load", displaySaleItems);

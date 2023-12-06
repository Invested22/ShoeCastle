
// Sample product list data (you can replace it with actual data)
const productList = [
    { name: "Product 1", price: 19.99, quantity: 50 },
    { name: "Product 2", price: 29.99, quantity: 30 },
    // Add more products as needed
];

// Function to display products in the table
function displayProducts() {
    const tbody = document.querySelector("table tbody");
    tbody.innerHTML = "";

    productList.forEach((product) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${product.name}</td>
            <td>$${product.price.toFixed(2)}</td>
            <td>${product.quantity}</td>
            <td>
                <button>Edit</button>
                <button>Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Function to add a new product to the list
function addProduct(productName, productPrice, productQuantity) {
    productList.push({
        name: productName,
        price: parseFloat(productPrice),
        quantity: parseInt(productQuantity),
    });

    displayProducts();
}

// Handle form submission
const productForm = document.getElementById("product-form");
productForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const productName = event.target.elements["product-name"].value;
    const productPrice = event.target.elements["product-price"].value;
    const productQuantity = event.target.elements["product-quantity"].value;

    addProduct(productName, productPrice, productQuantity);

    // Clear the form after submission
    event.target.reset();
});

// Initial display of products
displayProducts();

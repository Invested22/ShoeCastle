// Function to add a product to the shopping cart
function addToCart(productName, price) {
    // You can implement the logic to add the product to the shopping cart here
    // For this example, we'll display an alert message
    alert(`Added "${productName}" to the shopping cart. Price: $${price}`);
}

// Add event listeners for "Add to Cart" buttons
const addToCartButtons = document.querySelectorAll('.product-item button');
addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productItem = button.parentElement;
        const productName = productItem.querySelector('h3').textContent;
        const productPrice = productItem.querySelector('p').textContent.replace('Price: $', '');

        // Call the addToCart function to add the product to the cart
        addToCart(productName, parseFloat(productPrice));
    });
});

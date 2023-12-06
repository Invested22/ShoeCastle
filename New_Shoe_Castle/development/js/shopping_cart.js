// Get all quantity input elements
const quantityInputs = document.querySelectorAll('input[type="number"]');

// Get all remove buttons
const removeButtons = document.querySelectorAll('.remove-button');

// Function to update the total price based on quantities
function updateTotalPrice() {
    const prices = document.querySelectorAll('.product-details p:nth-child(2)'); // Assuming the price is the second <p> in product-details
    const totalItems = quantityInputs.length;
    let subtotal = 0;

    for (let i = 0; i < totalItems; i++) {
        const price = parseFloat(prices[i].textContent.replace('$', '')); // Convert price to a number
        const quantity = parseInt(quantityInputs[i].value); // Get the quantity

        subtotal += price * quantity;
    }

    // Calculate tax (assuming 8.25% tax rate)
    const taxRate = 0.0825;
    const tax = subtotal * taxRate;

    // Calculate the total price
    const total = subtotal + tax;

    // Update the cart summary
    document.querySelector('.cart-summary p:nth-child(2)').textContent = `Total Items: ${totalItems}`;
    document.querySelector('.cart-summary p:nth-child(3)').textContent = `Subtotal: $${subtotal.toFixed(2)}`;
    document.querySelector('.cart-summary p:nth-child(4)').textContent = `Tax (8.25%): $${tax.toFixed(2)}`;
    document.querySelector('.cart-summary p:nth-child(5)').textContent = `Total Price: $${total.toFixed(2)}`;
}

// Attach event listeners to quantity inputs for updating the total price
quantityInputs.forEach((input) => {
    input.addEventListener('input', updateTotalPrice);
});

// Function to remove an item from the cart
function removeCartItem(event) {
    const button = event.target;
    const cartItem = button.parentElement.parentElement;
    cartItem.remove();
    updateTotalPrice();
}

// Attach event listeners to remove buttons for removing items from the cart
removeButtons.forEach((button) => {
    button.addEventListener('click', removeCartItem);
});

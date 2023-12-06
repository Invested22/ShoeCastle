// Function to handle form submission for shipping information
function handleShippingFormSubmit(event) {
    event.preventDefault();

    // Perform shipping information validation if needed

    // Proceed to payment information section
    document.querySelector('.payment-info').style.display = 'block';
}

// Function to handle form submission for payment information
function handlePaymentFormSubmit(event) {
    event.preventDefault();

    // Perform payment information validation if needed

    // Proceed to order review section
    document.querySelector('.order-review').style.display = 'block';

    // Calculate the total price and display ordered items
    calculateTotalPrice();
}

// Function to calculate the total price and display ordered items
function calculateTotalPrice() {
    const prices = [19.99, 24.99]; // Sample prices
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const orderReview = document.querySelector('.order-review ul');
    let subtotal = 0;

    for (let i = 0; i < prices.length; i++) {
        const quantity = parseInt(quantityInputs[i].value);
        const price = prices[i];
        const total = quantity * price;

        // Display the ordered item in the order review
        const listItem = document.createElement('li');
        listItem.textContent = `Product ${i + 1}: $${price.toFixed(2)} x ${quantity} = $${total.toFixed(2)}`;
        orderReview.appendChild(listItem);

        // Update the subtotal
        subtotal += total;
    }

    // Calculate tax (assuming 8.25% tax rate)
    const taxRate = 0.0825;
    const tax = subtotal * taxRate;

    // Calculate the total price
    const total = subtotal + tax;

    // Update the total price in the order review
    const totalItem = document.createElement('li');
    totalItem.textContent = `Total Price: $${total.toFixed(2)}`;
    orderReview.appendChild(totalItem);
}

// Attach event listeners for form submissions
document.querySelector('.shipping-info form').addEventListener('submit', handleShippingFormSubmit);
document.querySelector('.payment-info form').addEventListener('submit', handlePaymentFormSubmit);

// Sample data for orders (you can replace this with your data)
const orders = [
    {
        orderNumber: 12345,
        customerName: "John Doe",
        orderDate: "2023-01-15",
        orderTotal: 75.99,
    },
    {
        orderNumber: 12346,
        customerName: "Jane Smith",
        orderDate: "2023-02-10",
        orderTotal: 42.50,
    },
    // Add more order objects as needed
];

// Function to display orders and apply sorting
function displayOrders() {
    const orderTable = document.querySelector(".order-table");
    const orderRows = orders.map((order) => `
        <tr>
            <td>${order.orderNumber}</td>
            <td>${order.customerName}</td>
            <td>${order.orderDate}</td>
            <td>$${order.orderTotal.toFixed(2)}</td>
        </tr>
    `);

    orderTable.innerHTML = orderRows.join('');
}

// Function to sort orders by order date
function sortByOrderDate() {
    orders.sort((a, b) => new Date(a.orderDate) - new Date(b.orderDate));
    displayOrders();
}

// Function to sort orders by customer name
function sortByCustomerName() {
    orders.sort((a, b) => a.customerName.localeCompare(b.customerName));
    displayOrders();
}

// Function to sort orders by order total
function sortByOrderTotal() {
    orders.sort((a, b) => a.orderTotal - b.orderTotal);
    displayOrders();
}

// Call the function to display orders when the page loads
window.addEventListener("load", displayOrders);

// Hook up sorting functions to buttons
document.querySelector(".sort-order-date").addEventListener("click", sortByOrderDate);
document.querySelector(".sort-customer-name").addEventListener("click", sortByCustomerName);
document.querySelector(".sort-order-total").addEventListener("click", sortByOrderTotal);

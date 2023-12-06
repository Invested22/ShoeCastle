// JavaScript for Currently Placed Orders Page

// Sample data for orders (you can replace this with your actual order data)
const orders = [
    { id: 1, customerName: "John Doe", orderDate: "2023-01-15", orderTotal: 99.99 },
    { id: 2, customerName: "Jane Smith", orderDate: "2023-01-20", orderTotal: 49.99 },
    // Add more order data as needed
];

// Function to display the list of orders
function displayOrders() {
    const orderTable = document.getElementById("order-table");
    const orderTableBody = orderTable.getElementsByTagName("tbody")[0];

    orders.forEach((order) => {
        const newRow = orderTableBody.insertRow(orderTableBody.rows.length);
        newRow.insertCell(0).innerHTML = order.id;
        newRow.insertCell(1).innerHTML = order.customerName;
        newRow.insertCell(2).innerHTML = order.orderDate;
        newRow.insertCell(3).innerHTML = `$${order.orderTotal}`;
        const actionsCell = newRow.insertCell(4);

        // Add any order actions (e.g., view details) here
        const viewDetailsButton = document.createElement("button");
        viewDetailsButton.textContent = "View Details";
        viewDetailsButton.addEventListener("click", () => viewOrderDetails(order));
        actionsCell.appendChild(viewDetailsButton);
    });
}

// Function to view order details (You can implement the details view functionality here)
function viewOrderDetails(order) {
    // Add your code to display order details here
    alert(`View order details for Order ID: ${order.id}`);
}

// Call the function to display orders when the page loads
window.addEventListener("load", displayOrders);

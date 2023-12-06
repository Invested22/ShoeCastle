<? php

    // Include functions.php
    include('functions.php');

// Function to display products
function displayProducts() {
    global $productList;
    return json_encode($productList);
}

// Function to add a new product
function addProduct($productName, $productPrice, $productQuantity) {
    global $productList;
    $productList[] = ["name" = > $productName, "price" = > $productPrice, "quantity" = > $productQuantity];
    return json_encode(["success" = > true]);
}

// Function to edit an existing product
function editProduct($index, $productName, $productPrice, $productQuantity) {
    global $productList;
    $productList[$index] = ["name" = > $productName, "price" = > $productPrice, "quantity" = > $productQuantity];
    return json_encode(["success" = > true]);
}

// Function to delete an existing product
function deleteProduct($index) {
    global $productList;
    array_splice($productList, $index, 1);
    return json_encode(["success" = > true]);
}

// ... (your existing product management code)

// Now you can use functions from functions.php
// For example, to connect to the database
$dblink = db_connect();

// ... (use other functions from functions.php as needed)

// Handle different actions based on the request
if ($_SERVER["REQUEST_METHOD"] == = "GET") {
    echo displayProducts();
} elseif($_SERVER["REQUEST_METHOD"] == = "POST") {
    // ... (your existing POST handling code)
}
else {
    echo json_encode(["error" = > "Invalid request method"]);
}
?>

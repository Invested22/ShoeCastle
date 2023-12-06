<?php

// Include functions.php
require_once('functions.php');

// Check if db_connect function exists
if (!function_exists('db_connect')) {
    die('Error: db_connect function not found. Check functions.php inclusion.');
}

// Now you can use functions from functions.php
// For example, to connect to the database
$dblink = db_connect();

// Initialize product list
$productList = [];

// ... (use other functions from functions.php as needed)

// Function to display products
function displayProducts() {
    global $productList;
    return json_encode($productList);
}

// Function to add a new product
function addProduct($productName, $productPrice, $productQuantity) {
    global $productList;
    $productList[] = ["name" => $productName, "price" => $productPrice, "quantity" => $productQuantity];
    return json_encode(["success" => true]);
}

// Function to edit an existing product
function editProduct($index, $productName, $productPrice, $productQuantity) {
    global $productList;
    $productList[$index] = ["name" => $productName, "price" => $productPrice, "quantity" => $productQuantity];
    return json_encode(["success" => true]);
}

// Function to delete an existing product
function deleteProduct($index) {
    global $productList;
    array_splice($productList, $index, 1);
    return json_encode(["success" => true]);
}

// Handle different actions based on the request
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo displayProducts();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if ($requestData['action'] === 'add') {
        // Handle add action
        $productName = $requestData['name'];
        $productPrice = $requestData['price'];
        $productQuantity = $requestData['quantity'];

        echo addProduct($productName, $productPrice, $productQuantity);
    } elseif ($requestData['action'] === 'delete') {
        // Handle delete action
        $index = $requestData['index'];

        echo deleteProduct($index);
    } else {
        echo json_encode(["error" => "Invalid action"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>

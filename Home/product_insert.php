<?php
include "config.php";    // Database connection settings
include "util.php";      // Utility functions

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$manufacturer_id = $_POST['manufacturer_id'];
$product_name = $_POST['product_name'];
$product_desc = $_POST['product_desc'];
$price = $_POST['price'];

// Upload image
$image_path = handleImageUpload($_FILES['image']);

// Prepare the SQL statement with placeholders
$query = "INSERT INTO product (manufacturer_id, product_name, product_desc, price, added_datetime, image_path) VALUES ('$manufacturer_id', '$product_name', '$product_desc', '$price', NOW(), '$image_path')";

// Create a prepared statement
$stmt = mysqli_prepare($conn, $query);

// Bind the values to the statement
mysqli_stmt_bind_param($stmt, "issss", $manufacturer_id, $product_name, $product_desc, $price, $image_path);

// Execute the statement
$result = mysqli_stmt_execute($stmt);

if ($result) {
    // Redirect to product_list.php
    header("Location: product_list.php");
    exit;
} else {
    die('Failed to add the product: ' . mysqli_error($conn));
}
?>




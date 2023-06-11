<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";    // Database connection settings
include "util.php";      // Utility functions

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$product_id = $_POST['product_id'];
$manufacturer_id = $_POST['manufacturer_id'];
$product_name = $_POST['product_name'];
$product_desc = $_POST['product_desc'];
$price = $_POST['price'];

// Check if a new image is uploaded
if ($_FILES['image']['name'] !== '') {
    // Delete the previous image file, if exists
    $prev_product = mysqli_fetch_array(mysqli_query($conn, "SELECT image_path FROM product WHERE product_id = $product_id"));
    if ($prev_product['image_path']) {
        deleteImage($prev_product['image_path']);
    }
    // Upload the new image
    $image_path = handleImageUpload($_FILES['image']);
    $query = "UPDATE product SET manufacturer_id = '$manufacturer_id', product_name = '$product_name', product_desc = '$product_desc', price = '$price', image_path = '$image_path' WHERE product_id = $product_id";
    echo "New image uploaded. Query: $query"; // Debugging statement
} else {
    // No new image uploaded, update other product details
    $query = "UPDATE product SET manufacturer_id = '$manufacturer_id', product_name = '$product_name', product_desc = '$product_desc', price = '$price' WHERE product_id = $product_id";
    echo "No new image uploaded. Query: $query"; // Debugging statement
}

$result = mysqli_query($conn, $query);

if ($result) {
    // Redirect to product_list.php
    header("Location: product_list.php");
    exit;
} else {
    die('Failed to add the product: ' . mysqli_error($conn));
}
?>

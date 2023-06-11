<?php
include "config.php";    // Database connection settings
include "util.php";      // Utility functions

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$product_id = $_POST['product_id'];
$manufacturer_id = $_POST['manufacturer_id'];
$product_name = $_POST['product_name'];
$product_desc = $_POST['product_desc'];
$price = $_POST['price'];
$date = $_POST['date'];

// Check if a new image is uploaded
if ($_FILES['image']['name'] !== '') {
    // Delete the previous image file, if exists
    $prev_product = mysqli_fetch_array(mysqli_query($conn, "SELECT image_path FROM product WHERE product_id = $product_id"));
    if ($prev_product['image_path']) {
        deleteImage($prev_product['image_path']);
    }
    // Upload the new image
    $image_path = handleImageUpload($_FILES['image']);
    $query = "UPDATE product SET manufacturer_id = ?, product_name = ?, product_desc = ?, price = ?, image_path = ?, date = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssssi", $manufacturer_id, $product_name, $product_desc, $price, $image_path, $date, $product_id);
} else {
    // No new image uploaded, update other product details
    $query = "UPDATE product SET manufacturer_id = ?, product_name = ?, product_desc = ?, price = ?, date = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issssi", $manufacturer_id, $product_name, $product_desc, $price, $date, $product_id);
}

$result = mysqli_stmt_execute($stmt);

if ($result) {
    // Redirect to product_list.php
    header("Location: product_list.php");
    exit;
} else {
    die('Failed to update the product: ' . mysqli_error($conn));
}
?>

<?php
include "config.php";    // Database connection settings
include "util.php";      // Utility functions

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$manufacturer_id = $_POST['manufacturer_id'];
$product_name = $_POST['product_name'];
$product_desc = $_POST['product_desc'];
$price = $_POST['price'];
$date = $_POST['date'];

// Upload image
$image_path = handleImageUpload($_FILES['image']);

// Prepare the SQL statement with placeholders for product insertion
$query_product = "INSERT INTO product (manufacturer_id, product_name, product_desc, price, image_path, date) VALUES (?, ?, ?, ?, ?, ?)";

// Create a prepared statement for product insertion
$stmt_product = mysqli_prepare($conn, $query_product);

// Bind the values to the product statement
mysqli_stmt_bind_param($stmt_product, "isssss", $manufacturer_id, $product_name, $product_desc, $price, $image_path, $date);

// Execute the product insertion statement
$result_product = mysqli_stmt_execute($stmt_product);

// Check if product insertion was successful
if ($result_product) {
    // Retrieve the auto-generated product ID
    // Retrieve the last inserted product_id
$product_id = mysqli_insert_id($conn);

// Define the seat combinations
$seat_combinations = array(
    array('A', 'A'),
    array('A', 'B'),
    array('A', 'C'),
    array('A', 'D')
);

// Prepare the SQL statement for seat insertion
$query_seat = "INSERT INTO seat (seat_row, seat_col, product_id) VALUES (?, ?, ?)";

// Create a prepared statement for seat insertion
$stmt_seat = mysqli_prepare($conn, $query_seat);

// Bind the values to the seat statement
mysqli_stmt_bind_param($stmt_seat, "ssi", $seat_row, $seat_col, $product_id);

// Insert the seat combinations
foreach ($seat_combinations as $combination) {
    $seat_row = $combination[0];
    $seat_col = $combination[1];
    
    // Execute the seat statement
    $result_seat = mysqli_stmt_execute($stmt_seat);
    
    if (!$result_seat) {
        die('Failed to add the seats: ' . mysqli_error($conn));
    }
}

// Redirect to product_list.php
header("Location: product_list.php");
exit;

} else {
    die('Failed to add the product: ' . mysqli_error($conn));
}

?>




<?php
include "config.php";    // 데이터베이스 연결 설정파일
include "util.php";      // 유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$product_id = $_GET['product_id'];

// Retrieve the image path from the database
$image_query = "SELECT image_path FROM product WHERE product_id = $product_id";
$image_result = mysqli_query($conn, $image_query);
$image_row = mysqli_fetch_assoc($image_result);
$image_path = $image_row['image_path'];

// Delete the associated seat records
$delete_seat_query = "DELETE FROM seat WHERE product_id = $product_id";
$delete_seat_result = mysqli_query($conn, $delete_seat_query);

if (!$delete_seat_result) {
    msg('Error deleting associated seat records: ' . mysqli_error($conn));
    echo "<meta http-equiv='refresh' content='0;url=product_list.php'>";
    exit;
}

// Delete the product from the product table
$delete_product_query = "DELETE FROM product WHERE product_id = $product_id";
$delete_product_result = mysqli_query($conn, $delete_product_query);

if (!$delete_product_result) {
    msg('Error deleting the product: ' . mysqli_error($conn));
    echo "<meta http-equiv='refresh' content='0;url=product_list.php'>";
    exit;
}

// Delete the image file from the server
if (!empty($image_path) && file_exists($image_path)) {
    unlink($image_path);
}

s_msg('The product has been successfully deleted.');
echo "<meta http-equiv='refresh' content='0;url=product_list.php'>";

?>




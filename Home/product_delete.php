<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

$product_id = $_GET['product_id'];

$check_query = "SELECT product_id FROM buy_item WHERE product_id = $product_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    s_msg('The product cannot be deleted as it has already been ordered.');
    echo "<meta http-equiv='refresh' content='0;url=product_list.php'>";
} else {
    $delete_query = "DELETE FROM product WHERE product_id = $product_id";
    $result = mysqli_query($conn, $delete_query);

    if (!$result) {
        msg('Query Error: ' . mysqli_error($conn));
    } else {
        s_msg('The product has been successfully deleted.');
        echo "<meta http-equiv='refresh' content='0;url=product_list.php'>";
    }
}
?>



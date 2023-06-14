<?php
include "config.php";    // Database connection settings file
include "util.php";

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user information from the user_form table
    $userQuery = "SELECT id, name FROM user_form LIMIT 1";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult) {
        die('Query Error: ' . mysqli_error($conn));
    }

    $userRow = mysqli_fetch_assoc($userResult);
    $userID = $userRow['id'];
    $userName = $userRow['name'];

    mysqli_free_result($userResult);

    // Get the selected product IDs from the form
    $selectedProductIDs = $_POST['product_id'];

    // Store purchase information in the purchase_history table
    $insertQuery = "INSERT INTO purchase_history (user_id, user_name, product_id, product_name) VALUES (?, ?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertQuery);

    foreach ($selectedProductIDs as $productID) {
        // Retrieve the product name and remove "dsfsdf" characters
        $productNameQuery = "SELECT product_id, REPLACE(product_name, 'dsfsdf', '') AS product_name FROM product WHERE product_id = ?";
        $productNameStmt = mysqli_prepare($conn, $productNameQuery);
        mysqli_stmt_bind_param($productNameStmt, "i", $productID);
        mysqli_stmt_execute($productNameStmt);
        mysqli_stmt_bind_result($productNameStmt, $productID, $productName);
        mysqli_stmt_fetch($productNameStmt);
        mysqli_stmt_close($productNameStmt);

        // Insert purchase information into purchase_history table
        mysqli_stmt_bind_param($insertStmt, "isis", $userID, $userName, $productID, $productName);
        mysqli_stmt_execute($insertStmt);

        // Delete the selected seat from the seat table
        $seatID = $_POST["seat_$productID"];
        $deleteQuery = "DELETE FROM seat WHERE seat_id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, "i", $seatID);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);

        // Check if there are any remaining seats for the product
        $remainingSeatsQuery = "SELECT COUNT(*) AS num_seats FROM seat WHERE product_id = ?";
        $remainingSeatsStmt = mysqli_prepare($conn, $remainingSeatsQuery);
        mysqli_stmt_bind_param($remainingSeatsStmt, "i", $productID);
        mysqli_stmt_execute($remainingSeatsStmt);
        mysqli_stmt_bind_result($remainingSeatsStmt, $remainingSeats);
        mysqli_stmt_fetch($remainingSeatsStmt);
        mysqli_stmt_close($remainingSeatsStmt);

        if ($remainingSeats == 0) {
            // If there are no more seats remaining, delete the event
            $deleteEventQuery = "DELETE FROM product WHERE product_id = ?";
            $deleteEventStmt = mysqli_prepare($conn, $deleteEventQuery);
            mysqli_stmt_bind_param($deleteEventStmt, "i", $productID);
            mysqli_stmt_execute($deleteEventStmt);
            mysqli_stmt_close($deleteEventStmt);
        }
    }

    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);

    // Redirect to the purchase_list page
    header("Location: purchase_list.php");
    exit();
}
?>

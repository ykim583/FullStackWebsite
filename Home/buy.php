<?php
include "config.php";    // Database connection settings file
include "util.php";

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected product IDs and corresponding seat IDs from the form
    $selectedProductIDs = $_POST['product_id'];
    $seatData = array();

    foreach ($selectedProductIDs as $productID) {
        $seatID = $_POST["seat_$productID"];
        $seatData[] = $seatID;
    }

    // Delete the selected seats from the seat table
    $seatIDs = implode(",", $seatData);
    $deleteQuery = "DELETE FROM seat WHERE seat_id IN ($seatIDs)";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if (!$deleteResult) {
        die('Delete Error: ' . mysqli_error($conn));
    }

    // Check if there are any remaining seats for each product
    foreach ($selectedProductIDs as $productID) {
        $remainingSeatsQuery = "SELECT COUNT(*) AS num_seats FROM seat WHERE product_id = $productID";
        $remainingSeatsResult = mysqli_query($conn, $remainingSeatsQuery);

        if (!$remainingSeatsResult) {
            die('Query Error: ' . mysqli_error($conn));
        }

        $remainingSeatsRow = mysqli_fetch_assoc($remainingSeatsResult);
        $remainingSeats = $remainingSeatsRow['num_seats'];

        if ($remainingSeats == 0) {
            // If there are no more seats remaining, delete the event
            $deleteEventQuery = "DELETE FROM product WHERE product_id = $productID";
            $deleteEventResult = mysqli_query($conn, $deleteEventQuery);

            if (!$deleteEventResult) {
                die('Delete Event Error: ' . mysqli_error($conn));
            }
        }
    }

    // Redirect to the confirmation page
    header("Location: confirmation.php");
    exit();
}

mysqli_close($conn);
?>



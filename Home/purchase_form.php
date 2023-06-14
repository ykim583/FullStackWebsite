<?php
include "header.php";
include "config.php";    // Database connection settings file
include "util.php";

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$query = "SELECT * FROM product
          JOIN manufacturer ON product.manufacturer_id = manufacturer.manufacturer_id
          LIMIT 10"; // Limit up to 10 events for now
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}
?>
<div class="container">
    <form name="buy" action="buy.php" method="POST">
        <table class="table table-striped table-bordered">
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Event Date</th>
                <th>Seats</th>
                <th>Select</th>
            </tr>
            <?php
            $row_index = 1;
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>{$row_index}</td>";
                echo "<td><a href='product_view.php?product_id={$row['product_id']}'>{$row['product_name']}</a></td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>{$row['date']}</td>";
                echo "<td>";

                // Fetch four different seat combinations for the current product_id from the seat table
                $product_id = $row['product_id'];
                $seatQuery = "SELECT * FROM seat WHERE product_id = $product_id LIMIT 4";
                $seatResult = mysqli_query($conn, $seatQuery);

                echo "<select name='seat_{$row['product_id']}'>";
                while ($seatRow = mysqli_fetch_array($seatResult)) {
                    $seatId = $seatRow['seat_id'];
                    $seatRowNum = $seatRow['seat_row'];
                    $seatColNum = $seatRow['seat_col'];
                    echo "<option value='{$seatId}'>Seat: Row {$seatRowNum}, Col {$seatColNum}</option>";
                }
                echo "</select>";

                echo "</td>";

                echo "<td width='17%'>
                        <input type='checkbox' name='product_id[]' value='{$row['product_id']}'>
                      </td>";
                echo "</tr>";
                $row_index++;
            }
            ?>

        </table>
        <div align='center'>
            <input type='submit' class='button primary small' value='Purchase'>
        </div>
    </form>
</div>
<?php include("footer.php"); ?>



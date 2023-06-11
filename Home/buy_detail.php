<?php
include "header.php";
include "config.php";    // Database connection settings file
include "util.php";
?>
<div class="container">
    <?php
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    $query = "SELECT * FROM product NATURAL JOIN manufacturer";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Query Error: ' . mysqli_error());
    }
    ?>
    <form name='buy' action='buy.php' method='POST'>
        <table class="table table-striped table-bordered">
            <tr>
                <th>No.</th> 
                <th>Product Name</th>
                <th>Price</th>
                <th>Event Date</th>
                <th>Seat Selection</th>
            </tr>
            <?php
            $row_index = 1;
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>{$row_index}</td>";
                echo "<td><a href='product_view.php?product_id={$row['product_id']}'>{$row['product_name']}</a></td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>{$row['date']}</td>";
                echo "<td width='17%'>";
                
                // Fetch available seats for the current product_id from the seat table
                $product_id = $row['product_id'];
                $seatQuery = "SELECT * FROM seat WHERE product_id = $product_id";
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
<?php include("footer.php") ?>


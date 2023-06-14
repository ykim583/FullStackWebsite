<?php
include "header.php";
include "config.php";    // Database connection settings file
include "util.php";      // Utility functions

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

// Retrieve the first user from the user_form table
$userQuery = "SELECT id, name FROM user_form LIMIT 1";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die('Query Error: ' . mysqli_error($conn));
}

$userRow = mysqli_fetch_assoc($userResult);
$userID = $userRow['id'];

mysqli_free_result($userResult);

// Retrieve the purchase history for the current user
$query = "SELECT event_id, user_id, user_name, product_id, product_name FROM purchase_history WHERE user_id = $userID";
$result = mysqli_query($conn, $query);

?>

<div class="container">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <tr>
                <th>Event ID</th>
                <th>User ID</th>
                <th>User Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>{$row['event_id']}</td>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['user_name']}</td>";
                echo "<td>{$row['product_id']}</td>";
                echo "<td>{$row['product_name']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php else: ?>
        <p>No purchase history available for the current user.</p>
    <?php endif; ?>
</div>

<?php
mysqli_close($conn);
include "footer.php";
?>

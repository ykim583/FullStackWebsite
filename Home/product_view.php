<?
include "header.php";
include "config.php";    // Database connection settings
include "util.php";      // Utility functions

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if (array_key_exists("product_id", $_GET)) {
    $product_id = $_GET["product_id"];
    $query = "select * from product natural join manufacturer where product_id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    if (!$product) {
        msg("No item exists.");
    }
}
?>
    <div class="container fullwidth">

        <h3>Event Description</h3>

        <p>
            <label for="product_id">Product ID</label>
            <input readonly type="text" id="product_id" name="product_id" value="<?= $product['product_id'] ?>"/>
        </p>

        <p>
            <label for="manufacturer_id">Manufacturer ID</label>
            <input readonly type="text" id="manufacturer_id" name="manufacturer_id" value="<?= $product['manufacturer_id'] ?>"/>
        </p>

        <p>
            <label for="manufacturer_name">Manufacturer</label>
            <input readonly type="text" id="manufacturer_name" name="manufacturer_name" value="<?= $product['manufacturer_name'] ?>"/>
        </p>

        <p>
            <label for="product_name">Event Name</label>
            <input readonly type="text" id="product_name" name="product_name" value="<?= $product['product_name'] ?>"/>
        </p>

        <p>
            <label for="product_desc">Event Description</label>
            <textarea readonly id="product_desc" name="product_desc" rows="10"><?= $product['product_desc'] ?></textarea>
        </p>

        <p>
            <label for="price">Price</label>
            <input readonly type="number" id="price" name="price" value="<?= $product['price'] ?>"/>
        </p>
    </div>
<? include "footer.php" ?>
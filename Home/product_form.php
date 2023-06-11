<?php
include "header.php";
include "config.php";  
include "util.php";      

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$mode = "Submit";
$action = "product_insert.php";

if (array_key_exists("product_id", $_GET)) {
    $product_id = $_GET["product_id"];
    $query =  "select * from product where product_id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_array($result);
    if (!$product) {
        msg("Ticket no longer exists");
    }
    $mode = "Edit";
    $action = "product_modify.php";
}

$manufacturers = array();

$query = "select * from manufacturer";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $manufacturers[$row['manufacturer_id']] = $row['manufacturer_name'];
}

// Error variables
$manufacturerError = $productNameError = $productDescError = $priceError = $imageError = $dateError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manufacturer_id = $_POST["manufacturer_id"];
    $product_name = $_POST["product_name"];
    $product_desc = $_POST["product_desc"];
    $price = $_POST["price"];
    $image = $_FILES["image"]["name"];
    $date = $_POST["date"];
    
    // Validation
    $isValid = true;
    
    if ($manufacturer_id == -1) {
        $manufacturerError = "Please select a manufacturer.";
        $isValid = false;
    }
    
    if (empty($product_name)) {
        $productNameError = "Please enter the product name.";
        $isValid = false;
    }
    
    if (empty($product_desc)) {
        $productDescError = "Please enter the event description.";
        $isValid = false;
    }
    
    if (empty($price)) {
        $priceError = "Please enter the price.";
        $isValid = false;
    }
    
    if (empty($date)) {
        $dateError = "Please enter the date.";
        $isValid = false;
    }
    
    if (empty($image) || $_FILES["image"]["error"] == 4) {
        $imageError = "Please choose an image file.";
        $isValid = false;
    } else {
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        $fileExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $imageError = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $isValid = false;
        }
    }
    
    if ($isValid) {
        // Process the form submission and perform database operations
        // ...
        // Redirect to another page if needed
    }
}
?>

<div class="container">
    <form name="product_form" action="<?=$action?>" method="post" class="fullwidth" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?=$product['product_id']?>"/>
        <h3>Product Form <?=$mode?></h3>
        <p>
            <label for="manufacturer_id">Supplier</label>
            <select name="manufacturer_id" id="manufacturer_id">
                <option value="-1">Please Select a Manufacturer</option>
                <?php
                foreach ($manufacturers as $id => $name) {
                    if ($id == $product['manufacturer_id']) {
                        echo "<option value='{$id}' selected>{$name}</option>";
                    } else {
                        echo "<option value='{$id}'>{$name}</option>";
                    }
                }
                ?>
            </select>
            <span class="error"><?=$manufacturerError?></span>
        </p>
        <p>
            <label for="product_name">Product Name</label>
            <input type="text" placeholder="Product Name" id="product_name" name="product_name" value="<?=$product['product_name']?>"/>
            <span class="error"><?=$productNameError?></span>
        </p>
        <p>
            <label for="product_desc">Description of the Event</label>
            <textarea placeholder="Event Description" id="product_desc" name="product_desc" rows="10"><?=$product['product_desc']?></textarea>
            <span class="error"><?=$productDescError?></span>
        </p>
        <p>
            <label for="price">Price</label>
            <input type="number" placeholder="Korean Won" id="price" name="price" value="<?=$product['price']?>" />
            <span class="error"><?=$priceError?></span>
        </p>
        <p>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="<?=$product['date']?>" />
            <span class="error"><?=$dateError?></span>
        </p>
        <p>
            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <span class="error"><?=$imageError?></span>
        </p>

        <p align="center">
            <button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button>
        </p>

        <script>
            function validate() {
                var manufacturerId = document.getElementById("manufacturer_id").value;
                var productName = document.getElementById("product_name").value;
                var productDesc = document.getElementById("product_desc").value;
                var price = document.getElementById("price").value;
                var date = document.getElementById("date").value;
                var image = document.getElementById("image").value;

                if (manufacturerId == "-1") {
                    alert("Please select a manufacturer.");
                    return false;
                }
                else if (productName.trim() == "") {
                    alert("Please enter the product name.");
                    return false;
                }
                else if (productDesc.trim() == "") {
                    alert("Please enter the event description.");
                    return false;
                }
                else if (price.trim() == "") {
                    alert("Please enter the price.");
                    return false;
                }
                else if (date.trim() == "") {
                    alert("Please enter the date.");
                    return false;
                }
                else if (image.trim() == "" || document.getElementById("image").files.length === 0) {
                    alert("Please choose an image file.");
                    return false;
                }
                return true;
            }
        </script>
    </form>
</div>

<?php include("footer.php"); ?>




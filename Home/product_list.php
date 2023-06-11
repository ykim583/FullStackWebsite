<?
include "header.php";
include "config.php";    
include "util.php";     
?>
<div class="container">
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    $query = "select * from product natural join manufacturer";
    if (array_key_exists("search_keyword", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_keyword = $_POST["search_keyword"];
        $query .= " where product_name like '%$search_keyword%' or manufacturer_name like '%$search_keyword%'";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
         die('Query Error : ' . mysqli_error());
    }
    ?>

    <table class="table table-striped table-bordered">
        <tr>
            <th>No.</th>
            <th>Manufacturer</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Uploaded Date</th>
            <th>Functionality</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td>{$row['manufacturer_name']}</td>";
            echo "<td><a href='product_view.php?product_id={$row['product_id']}'>{$row['product_name']}</a></td>";
            echo "<td>{$row['price']}</td>";
            echo "<td><img src='{$row['image_path']}' alt='Product Image' style='width: 100px;'></td>"; // Display the image
            echo "<td>{$row['added_datetime']}</td>";
            echo "<td width='17%'>
                <a href='product_form.php?product_id={$row['product_id']}'><button class='button primary small'>Edit</button></a>
                 <button onclick='javascript:deleteConfirm({$row['product_id']})' class='button danger small'>Delete</button>
                </td>";
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
    <script>
        function deleteConfirm(product_id) {
            if (confirm("Are you sure for deletion?") == true){    
                window.location = "product_delete.php?product_id=" + product_id;
            }else{   
                return;
            }
        }
    </script>
</div>
<? include("footer.php") ?>

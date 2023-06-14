<!DOCTYPE html>
<html lang='ko'>
<head>
    <title>K-Ticket</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<form action="product_list.php" method="post">
    <div class='navbar fixed'>
        <div class='container'>
            <a class='pull-left title' href="index.php">K-Ticket</a>
            <ul class='pull-right'>
                <li>
                    <input type="text" name="search_keyword" placeholder="K-Ticket Search Engine">
                </li>
                <li><a href='product_list.php'>Event List</a></li>
                <li><a href='product_form.php'>Event Registration</a></li>
                <li><a href='purchase_form.php'>Ticket Purchase</a></li>
                <li><a href='purchase_list.php'>Purchase List</a></li>
                <li><a href="../login_form.php">Logout</a></li>
            </ul>
        </div>
    </div>
</form>

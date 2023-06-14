<?php
include "config.php"; // Include the database connection settings
include "util.php";
session_start();

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);

    $select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        // Get the minimum 'no' value from the user_form table
        $minNoQuery = "SELECT MIN(no) FROM user_form";
        $minNoResult = mysqli_query($conn, $minNoQuery);
        $minNoRow = mysqli_fetch_array($minNoResult);
        $minNo = $minNoRow[0];

        // Set the logged-in user's 'no' value to be the minimum 'no' value minus 1
        $user_no = $row['no'];
        $newUserNo = $minNo - 1;
        $updateUserNo = "UPDATE user_form SET no = '$newUserNo' WHERE email = '$email'";
        mysqli_query($conn, $updateUserNo);

        // Increment 'no' values for all other users by 1
        $incrementNos = "UPDATE user_form SET no = no + 1 WHERE email != '$email'";
        mysqli_query($conn, $incrementNos);

        // Increment the current user's 'no' value by 1
        $incrementCurrentUser = "UPDATE user_form SET no = no + 1 WHERE email = '$email'";
        mysqli_query($conn, $incrementCurrentUser);

        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            header('Location: admin_page.php');
            exit();
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            header('Location: user_page.php');
            exit();
        }
    } else {
        $error = 'Incorrect email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" value="login now" class="form-btn">
      <p>don't have an account? <a href="register_form.php">register now</a></p>
   </form>

</div>

</body>
</html>

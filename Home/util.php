<?
function dbconnect($host, $id, $pass, $db)  //데이터베이스 연결
{
    $conn = mysqli_connect($host, $id, $pass, $db);
	
    if ($conn == false) {
        die('Not connected : ' . mysqli_error());
    }

    return $conn;
}

function msg($msg) // 경고 메시지 출력 후 이전 페이지로 이동
{
    echo "
        <script>
             window.alert('$msg');
             history.go(-1);
        </script>";
    exit;
}

function s_msg($msg) //일반 메시지 출력
{
    echo "
        <script>
            window.alert('$msg');
        </script>";
}

function check_id($conn, $id)
{
	$query = "select customer_id from customer where customer_id='$id'";
	$result = mysqli_query($conn, $query);
	
	$result = mysqli_fetch_array($result);
	if ($result){
		return true;
	}
	else{
		return false;
	}
}

function handleImageUpload($file)
{
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
  
    // Check if the file is a valid image
    $validExtensions = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $validExtensions)) {
        die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
    }
  
    // Generate a unique filename for the uploaded image
    $targetFile = uniqid() . '.' . $imageFileType;
	
    // Move the uploaded file to the current directory
    if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
        die("Error uploading the image.");
    }
  
    return $targetFile;
}

function deleteImage($image_path) {
    // Implement the logic to delete the image file
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

?>  

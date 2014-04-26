<?php
require 'script_db_handler.php';
require_once 'script_utility.php';

session_start();

openConnDB();

function died($error) {
	// your error code can go here
	$_SESSION['uploadImageError']=$error;
	echo $error;
	header('Location: '.redirect('viewtwitch.php'));
	die();
}

/* 
	Adapted from http://www.php-mysql-tutorial.com/wikis/mysql-tutorials/uploading-files-to-mysql-database.aspx
	Accessed on 20/10/2013
*/

if(isset($_POST['submit_upload_image']) && $_FILES['Image']['size'] > 0){
	if(!isset($_POST['Caption']) || !isset($_POST['Caption']) ){
		died("<span class=\"error\">Images require a caption.</span>");
	} else {
		if (strlen($caption) > 30 ){
			died("<span class=\"error\">Caption must be up to 30 characters.</span>");
		}

		$caption = htmlentities($_POST['Caption'], ENT_QUOTES); 		
		
		$fileName = $_FILES['Image']['name'];
		$tmpName  = $_FILES['Image']['tmp_name'];
		$fileSize = $_FILES['Image']['size'];
		$fileType = $_FILES['Image']['type'];

		$fp      = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);

		if(!get_magic_quotes_gpc()){
			$fileName = addslashes($fileName);
		}

		$query = "INSERT INTO image (post_id, image, image_type, alt_text) VALUES (".$_GET['post_id'].", '".$content."',  '".$fileType."', '".$caption."');";

		$result = mysqli_query($conn, $query);
		if (!$result){
			died('<span class="error">Upload of image failed.</span>');
		} else {
			died('Upload image success!');
		}
	}
} else {
	died('<span class="error">Please select an image.</span>');
}
?>
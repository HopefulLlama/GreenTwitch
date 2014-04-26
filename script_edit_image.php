<?php
require 'script_db_handler.php';
require_once 'script_utility.php';

session_start();

openConnDB();

function died($returnMessage) {
	// your error code can go here
	$_SESSION['imageReturnMessage']=$error;
	echo $error;
	header( 'Location: '.redirect('viewtwitch.php'));
	die();
}

if(isset($_POST['submit_edit_image'])){
	$anyErrors = false;
	$image_ids=preg_split("/,/", $_GET['image_ids'], -1, PREG_SPLIT_NO_EMPTY);
	foreach($image_ids as $image_id){
		if(isset($_POST['Delete'.$image_id])){
			$query="DELETE FROM image WHERE image_id=".$image_id.";";

			$result = mysqli_query($conn, $query);
			if (!$result){
				$anyErrors = true;
			} 
		} else {
			if(!isset($_POST['Caption'.$image_id])){
				$anyErrors = true;
			} else {	
				$alt_text=htmlentities($_POST['Caption'.$image_id]);
				// Times four to allow for encoding
				if (strlen($alt_text) > 120 ){
					died("Caption must be up to 30 characters");
				} 	

				$query="UPDATE image SET alt_text='".$alt_text."' WHERE image_id=".$image_id.";";
				$result = mysqli_query($conn, $query);
				if (!$result){
					$anyErrors = true;
				}
			}
		}
	}
	if ($anyErrors){
		died("There were some errors in the update process. Please review images and amend if necessary.");
	} else {
		died("Update executed successfully.");
	}
} else {
	died("Error in submitting details. Please try again.");
}
?>
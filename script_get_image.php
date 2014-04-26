<?php
/*
Adapted http://stackoverflow.com/questions/7793009/how-to-retrieve-images-from-mysql-database-and-display-in-an-html-tag accessed 22/10/2013
*/
session_start();
require 'script_db_handler.php';

openConnDB();
$query = "SELECT * FROM image WHERE image_id=".$_GET['image_id'];
$result = mysqli_query($conn, $query);

if($result){
	while ($obj = mysqli_fetch_object($result)) {
		header("Content-type: ".$obj->image_type);
		echo $obj->image;
	}
} else {
	echo "lol";
}
?>
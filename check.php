<?php
require 'script_db_handler.php';
if(isset($_POST['usernameAjax'])){
	$query = "SELECT username FROM member WHERE username = '".$_POST['usernameAjax']."' LIMIT 1;";
	openConnDB();

	$result = mysqli_query($conn, $query);

	echo $result->num_rows;
} else if (isset($_POST['emailAjax'])) {
	$query = "SELECT email FROM member WHERE email = '".$_POST['emailAjax']."' LIMIT 1;";
	openConnDB();

	$result = mysqli_query($conn, $query);

	echo $result->num_rows;
}
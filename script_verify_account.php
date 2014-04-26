<?php
require 'script_db_handler.php';
require_once 'script_utility.php';

session_start();

openConnDB();

function died($error) {
	// your error code can go here
	$_SESSION['verifyAccountError']=$error;
	echo $error;
	header( 'Location: '.redirect('verifyaccount.php'));
	die();
}
     
if(isset($_POST['submit_verifyAccount'])) {

    //Check expected data exists
    if(!isset($_POST['Code'])){
        died('All fields are required. Please fill in all fields and resubmit.');
    } elseif ( !preg_match('/^[A-Za-z0-9]{5,5}$/', $_POST['Code']) ) {
			died('Code must contain a-z, A-Z and 0-9 characters only, and be 5 characters long.');
	} else {
	
		// Check DB
		$query = "SELECT username FROM member WHERE username='".$_SESSION['currentUser']."' AND confirmation_code='".$_POST['Code']."';";

		$result = mysqli_query($conn, $query);

		if($result){
			/* fetch associative array */
			while ($obj = mysqli_fetch_object($result)) {
				printf ($obj->username);
				$_SESSION['currentUser']=$obj->username;
			}
			
			$query = "UPDATE member SET confirmation_code='0', active=1 WHERE username='".$_SESSION['currentUser']."'";
			$result = mysqli_query($conn, $query);
			$_SESSION['activeAccount']=true;
			header( 'Location: '.redirect('index.php')) ;
		} else {
			died("Incorrect details provided. Check details and try again.");
		}
	}
} else {
	died("Error submitting details. Please try again.");
}

?>
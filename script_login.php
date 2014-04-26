<?php  
require_once 'script_utility.php';

if(isset($_POST['submit_login'])) {

    // Check expected data exists
    if(!isset($_POST['UsernameLogIn']) || 
	   !isset($_POST['PasswordLogIn'])){
        $_SESSION['loginError'] = 'All fields are required. Please fill in all fields and resubmit.';

	// Adapted from: http://stackoverflow.com/questions/13392842/using-php-regex-to-validate-username [Accessed 15 November 2013]
    } else if ( !preg_match('/^[A-Za-z0-9-_]{5,29}$/', $_POST['UsernameLogIn']) ) {
		$_SESSION['loginError'] = 'Username must contain a-z, A-Z, 0-9, - or _ characters only, and be between 6-30 characters long.';
	} else if ( strlen($_POST['PasswordLogIn']) < 8 || strlen($_POST['PasswordLogIn']) > 30) {
		$_SESSION['loginError'] = 'Password must be between 8-30 characters long.';
	} else {
		// Check DB for details
		openConnDB();
		
		$query = "SELECT mem_id, username, active FROM member WHERE username='".$_POST['UsernameLogIn']."' AND password=PASSWORD('".$_POST['PasswordLogIn']."');";
		$result = mysqli_query($conn, $query);
		
		if($result->num_rows==1){
			/* fetch associative array */
			while ($obj = mysqli_fetch_object($result)) {
				printf ($obj->username);
				$_SESSION['currentUser']=$obj->username;
				$_SESSION['currentUserID']=$obj->mem_id;	
				
				if(isset($_POST['Remember'])){
					// Set a cookie to remember the username.
					setcookie("username", $obj->username, time()+60*60*24*30);
				} else {
					setcookie("username", "", time()-10);
				}

				// Check and set session depending on account state.
				if($obj->active == 0){
					$_SESSION['activeAccount']=false;
					header('Location: '.redirect('verifyaccount.php'));
				} else {
					$_SESSION['activeAccount']=true;
					header('Location: '.'index.php');
				}
			}
		} else {
			$_SESSION['loginError'] = "Incorrect details provided. Check details and try again.";
		}
	}
}
?>
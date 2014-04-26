<?php
require_once 'script_utility.php';

if(isset($_POST['submit_accountDetails'])){
    // Check expected data exists
    if(!isset($_POST['UsernameCreateAccount']) ||
        !isset($_POST['PasswordCreateAccount']) ||
		!isset($_POST['ConfirmPasswordCreateAccount']) ||
        !isset($_POST['E-mail']) ||
        !isset($_POST['Captcha'])) {
        $_SESSION['createAccountError'] = 'All fields are required. Please fill in all fields and resubmit.';
    } else {
		// Further validate field for content
		// Adapted from: http://stackoverflow.com/questions/13392842/using-php-regex-to-validate-username [Accessed 15 November 2013]
		if ( !preg_match('/^[A-Za-z0-9-_]{0,30}$/', $_POST['UsernameCreateAccount']) ) {
			$_SESSION['createAccountError'] = 'Username must contain a-z, A-Z, 0-9, - or _ characters only, and be up to 30 characters long.';
		} else if ( strlen($_POST['PasswordCreateAccount']) < 8 || strlen($_POST['PasswordCreateAccount']) > 30 ) {
			$_SESSION['createAccountError'] = 'Password must be between 8-30 characters long.';
		} else if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/', $_POST['E-mail']) || strlen($_POST['E-mail']) > 30 ) {
			$_SESSION['createAccountError'] = 'E-mail must be a valid e-mail address, and up to 30 characters long.';
		} else if(!isset($_SESSION['captcha']) || $_SESSION['captcha'] != $_POST['Captcha']) {
			$_SESSION['createAccountError'] = 'Captcha mismatch. Please try again.';
		} else if ($_POST['PasswordCreateAccount'] != $_POST['ConfirmPasswordCreateAccount']){
			$_SESSION['createAccountError'] = 'Password mismatch. Please try again.';
		} else {
			$email_subject = "GreenTwitch account verification"; 
			$email_message = "Hello! Thank you taking an interest in GreenTwitch. To verify your account for usage, please make note or copy the 5 character code below. You will be prompted to enter this as you log in for the first time.\n\nUsername: ".$_POST['UsernameCreateAccount']."\nConfirmation Code: ";
			/*Code for generation of 5 random characters obtained from Stack Overflow answers at: http://stackoverflow.com/questions/5438760/generate-random-5-characters-string [Accessed 15 November 2013] */
			$code = substr(md5(microtime()),rand(0,26),5);
			$email_message .= $code;

			openConnDB();

			$query = "INSERT INTO member(username, password, email, confirmation_code, active) VALUES ('".$_POST['UsernameCreateAccount']."', PASSWORD('".$_POST['PasswordCreateAccount']."'), '".$_POST['E-mail']."', '".$code."', 0);";

			$result = mysqli_query($conn, $query);
			if (!$result){
				$_SESSION['createAccountError'] = 'Creation of account failed. Perhaps username or e-mail address is not unique. Please try again with a new username or e-mail address.';
			} else {
				'X-Mailer: PHP/'.phpversion();
				mail($_POST['E-mail'], $email_subject, $email_message);
				$_SESSION['currentUser']=$_POST['UsernameCreateAccount'];
				$_SESSION['currentUserID']=mysqli_insert_id($conn);
				$_SESSION['activeAccount']=false;
				// Set a cookie to remember the username.
				setcookie("username", $obj->username, time()+60*60*24*30);
				header('Location: '.redirect('verifyaccount.php')) ;
			}
		}
	}
}
?>
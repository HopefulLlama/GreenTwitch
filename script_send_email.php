<?php
	require_once 'script_utility.php';
	require_once 'script_db_handler.php';
	session_start();

	if(isset($_SESSION['currentUser'])){
		if($_SESSION['activeAccount'] == false){
			openConnDB();

			$query = "SELECT confirmation_code, email FROM member WHERE mem_id = ".$_SESSION['currentUserID'].";";

			$result = mysqli_query($conn, $query);
			if ($result->num_rows==1){
				$email_subject = "GreenTwitch account verification"; 
				$email_message = "Hello! Thank you taking an interest in GreenTwitch. To verify your account for usage, please make note or copy the 5 character code below. You will be prompted to enter this as you log in for the first time.\n\nUsername: ".$_POST['UsernameCreateAccount']."\nConfirmation Code: ";
				/*Code for generation of 5 random characters obtained from Stack Overflow answers at: http://stackoverflow.com/questions/5438760/generate-random-5-characters-string [Accessed 15 November 2013] */
				while ($obj = mysqli_fetch_object($result)) {
					$code = $obj->confirmation_code;
					$email = $obj->email;
				}
				$email_message .= $code;
				'X-Mailer: PHP/'.phpversion();
				mail($email, $email_subject, $email_message);
				$_SESSION['sendEmailMessage'] = 'E-mail sent!';
			} else {
				$_SESSION['sendEmailMessage'] = 'Error retrieving details. Please try again.';
			}

		} else {
			$_SESSION['sendEmailMessage']='This account has already been activated.';
		}
	} else {
		$_SESSION['sendEmailMessage']='You are not logged in! Please log in.';
	}
	header('Location: '.redirect('verifyaccount.php')) ;
?>
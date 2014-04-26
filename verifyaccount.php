<?php 
	require 'head.php';
	require_once 'script_utility.php';
?>
</head>
<?php
	https(true);
	require 'banner.php';
	
	if( (isset($_SESSION['activeAccount']) && $_SESSION['activeAccount']) || !isset($_SESSION['currentUser']) ){ 
		$_GET['current_page']=1;
		header('Location: '.redirect('index.php'));
	}
?>

<!-- Main content; stuff displayed beneath the banner.-->
<div class="content center">
	<form method="post" action="script_verify_account.php" onsubmit="return validateVerifyAccountForm(this);">
		<p>Verify your account before authenticating log in. You should have received a 5 character long code in an e-mail sent to you when your account was created.</p>
		<fieldset>
			<label for="Code">Code:</label><input type="text" name="Code" id="Code" maxlength="5"/><br />
			<?php 
				if(isset($_SESSION['verifyAccountError'])){
					echo '<br/><span class=\"error\">'.$_SESSION['verifyAccountError'].'</span>'; 
					unset($_SESSION['verifyAccountError']); 
				}
			?>
			<br />
			<input name="submit_verifyAccount" type="submit" value="Submit!" />
		</fieldset>
	</form><br/>
	<p>Lost your e-mail? Forgotten the confirmation code? Click <a href="script_send_email.php">here</a> to request another e-mail.</p><?php
		if(isset($_SESSION['sendEmailMessage'])){
			echo '<span>'.$_SESSION['sendEmailMessage'].'</span>'; 
			unset($_SESSION['sendEmailMessage']); 
		}
	?>
	<br class="clear" />
</div>

<?php
	require 'foot.php';
?>
<?php 
	require 'head.php';
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#UsernameCreateAccount').keyup(username_check);
	$('#E-mail').keyup(email_check);
	$('#PasswordCreateAccount').keyup(password_check);
	$('#ConfirmPasswordCreateAccount').keyup(password_check);
});
     
function username_check(){   
	var username = $('#UsernameCreateAccount').val();
	 
	if(username == ""){
		$('#UsernameCreateAccount').css('border', '3px #CCC solid');
		$('#tickUsername').hide();
		$('#crossUsername').hide();
	} else {
		jQuery.ajax({
			type: "POST",
			url: "check.php",
			data: 'usernameAjax='+ username,
			cache: false,
			success: function(response){
				if(response == 1){
					$('#UsernameCreateAccount').css('border', '3px #C33 solid');
					$('#tickUsername').hide();
					$('#crossUsername').fadeIn();
				} else {
					$('#UsernameCreateAccount').css('border', '3px #090 solid');
					$('#crossUsername').hide();
					$('#tickUsername').fadeIn();
				}
				 
			}
		});
	}
}

function email_check(){   
	var email = $('#E-mail').val();
	 
	if(email == ""){
		$('#E-mail').css('border', '3px #CCC solid');
		$('#tickEmail').hide();
		$('#crossEmail').hide();
	} else {
		jQuery.ajax({
			type: "POST",
			url: "check.php",
			data: 'emailAjax='+ email,
			cache: false,
			success: function(response){
				if(response == 1){
					$('#E-mail').css('border', '3px #C33 solid');
					$('#tickEmail').hide();
					$('#crossEmail').fadeIn();
				} else {
					$('#E-mail').css('border', '3px #090 solid');
					$('#crossEmail').hide();
					$('#tickEmail').fadeIn();
				}
				 
			}
		});
	}
}

function password_check(){
	var password=$('#PasswordCreateAccount').val();
	var confirmPassword=$('#ConfirmPasswordCreateAccount').val();
	if (password == ""){
		$('#PasswordCreateAccount').css('border', '3px #CCC solid');
		$('#ConfirmPasswordCreateAccount').css('border', '3px #CCC solid');
		$('#tickPassword').hide();
		$('#crossPassword').hide();
	} else {
		if(password != confirmPassword){
			$('#PasswordCreateAccount').css('border', '3px #C33 solid');
			$('#ConfirmPasswordCreateAccount').css('border', '3px #C33 solid');
			$('#tickPassword').hide();
			$('#crossPassword').fadeIn();
		} else {
			$('#PasswordCreateAccount').css('border', '3px #090 solid');
			$('#ConfirmPasswordCreateAccount').css('border', '3px #090 solid');
			$('#crossPassword').hide();
			$('#tickPassword').fadeIn();
		}			 
	}
}
</script>

</head>
<?php
	https(true);
	require 'banner.php';

	require 'script_create_account.php';
	require 'script_login.php';
?>
		<!-- Main content; stuff displayed beneath the banner.-->
		<div class="content">
			<!-- Right half stuff-->
			<div class="halfDiv right">
				<div class="textLeft">
					<p>If you already have an account, enter your details and press log in to log in in the form below.</p>
					<form class="styled" method="post" action="login.php" onsubmit="return validateLoginForm(this)">
						<fieldset>
							<legend>Log In</legend>
							<label for="UsernameLogIn">Username:</label><input type="text" name="UsernameLogIn" id="UsernameLogIn" value="<?php 
								if(isset($_POST['UsernameLogIn'])){
									echo $_POST['UsernameLogIn'];
								} else if (isset($_COOKIE['username'])){ 
									echo $_COOKIE['username'];
								}
								?>" maxlength="30"/><br/>
							<label for="PasswordLogIn">Password:</label><input type="password" name="PasswordLogIn" id="PasswordLogIn" maxlength="30"/><br/>
							<label for="Remember">Remember:</label><input type="checkbox" id="Remember" name="Remember" value="remember"/><br/>
							<input name="submit_login" type="submit" value="Log In!"/><br/>
							<?php 
								if(isset($_SESSION['loginError'])){
									echo '<br/><span class=\"error\">'.$_SESSION['loginError'].'</span>'; 
									unset($_SESSION['loginError']); 
								}
							?>
						</fieldset>
					</form>
				</div>
				<div class="textLeft">
					<form class="styled" method="post" action="login.php" onsubmit="return validateCreateAccountForm(this)">
						<p>If you do not have an account to log in to GreenTwitch with, use the form below to create an account. Enter the requested details and press submit. You'll receive an e-mail with further instructions.</p>
						<fieldset>
							<legend class="formTitle">Create an Account</legend>
							<label for="UsernameCreateAccount">Username:</label><input type="text" name="UsernameCreateAccount" id="UsernameCreateAccount" maxlength="30" value="<?php if(isset($_POST['UsernameCreateAccount'])){echo $_POST['UsernameCreateAccount']; }?>"/><img id="tickUsername" class="tick" src="./images/tick.png" alt="A tick to indicate an available username" /><img id="crossUsername" class="cross" src="./images/cross.png" alt="A cross to indicate a taken username" /><br/>
							<label for="PasswordCreateAccount">Password:</label><input type="password" name="PasswordCreateAccount" id="PasswordCreateAccount" maxlength="30"/><img id="tickPassword" class="tick" src="./images/tick.png" alt="A tick to indicate matching passwords." /><img id="crossPassword" class="cross" src="./images/cross.png" alt="A cross to indicate a mismatch between passwords." /><br/>
							<label for="ConfirmPasswordCreateAccount">Confirm Password:</label><input type="password" name="ConfirmPasswordCreateAccount" id="ConfirmPasswordCreateAccount" maxlength="30"/><br/>
							<label for="E-mail">E-mail:</label><input type="text" name="E-mail" id="E-mail" maxlength="30" value="<?php if(isset($_POST['E-mail'])){echo $_POST['E-mail']; }?>"/><img id="tickEmail" class="tick" src="./images/tick.png" alt="A tick to indicate an available email" /><img id="crossEmail" class="cross" src="./images/cross.png" alt="A cross to indicate a taken email" /><br/><br/>
							<label for="Captcha">Captcha:</label><input type="text" name="Captcha" id="Captcha" maxlength="8"/><br/>
							<br/>Enter the text you see in the image below into the Captcha field.<br/>
							<img src="./script_captcha.php" alt="CAPTCHA Text."/><br/>
							<span class="smallFont">Note: If you are unable to complete the CAPTCHA due to accessibility reasons, please e-mail lj048@greenwich.ac.uk and provide details of the desired username and e-mail address. Manual account creation will be performed by an administrator.</span><br/>
							<input name="submit_accountDetails" type="submit" value="Submit!"/>	
							<?php 
								if(isset($_SESSION['createAccountError'])){
									echo '<br/><span class=\"error\">'.$_SESSION['createAccountError'].'</span>'; 
									unset($_SESSION['createAccountError']); 
								}
							?>
						</fieldset>
					</form>
				</div>
			</div>
			<!-- Left half stuff. -->
			<div class="halfDiv left">
				<p>Use the form to register to GreenTwitch; the world's leading twitching website based in Greenwich. Services we offer include account creation, account verification, authentication, twitch posting, image uploading, bird searching, and many more. Just read the testimonials from some of our dedicated Twitchers!</p>
				<div>
					<img class="left" src="./images/MaxMorris.png" alt="Image of Max Morris" width="100px" height="100px"/><p class="largeFont">Max Morris - Games Designer and Seriously Undiagnosed Twitcher</p><p class="small"><em>"I've been a long time twitcher, ever since I saw my first Caspian Tern. Before twitching I was single, living with my mother, and jobless. Now that I'm twitching, I'm all those things <strong>AND</strong> a twitcher. I hope to meet some avid twitchers on this website and a potential mate. My ideal wife would look something like a Golden-cheeked Warbler; especially since I hear that women natter a lot!"</em></p>
				</div>
				<div>
					<img class="left" src="./images/PeterSoar.png" alt="Image of Peter Soar" width="100px" height="100px"/><p class="largeFont">Peter Soar - Mathematician and Potential Serial Twitcher</p><p class="small"><em>"Never twitched before I visited this website but now that I have I've been twitching a lot. It's getting somewhat out of control but I'm sure it's nothing of concern. I try to have a twitching session at least once or twice a day; maybe during my lunch breaks or while I'm transit travelling to places. Now that I'm twitching all the time, I've been promoted seven times at work and obtained double digits of sexual partners!"</em></p>
				</div>
			</div>
			<br class="clear"/>
		</div>
	</div>
</body>
</html>
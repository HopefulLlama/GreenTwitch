<body>
	<div id="main">
		<div id="banner">
			<div class="left textLeft"><a class="logo" href="./index.php">GreenTwitch</a></div><div id="login" class="right textRight">
			<?php
				if(isset($_SESSION['currentUser'])){
					?> <p class="greyLight">Welcome <?php echo $_SESSION['currentUser'] ?> <a href="./script_logout.php" class="greyLight">(Log out)</a></p> <?php
					if ($_SESSION['activeAccount']==false){
						?> <p><a href="./verifyaccount.php" class="greyLight">(Verify account)</a></p> <?php
					}			
				}	else {
					?> <p><a href="./login.php" class="greyLight">Log in or create an account</a></p> <?php  
				}
			?>
			</div>
		</div>
		<div>
			<div class="nav"><a href="./index.php">Home</a></div>
			<div class="nav"><a href="./twitch.php">Twitch</a></div>
			<div class="nav"><a href="./search.php">Search</a></div>
		</div>
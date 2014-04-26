<?php
require_once 'script_utility.php';
session_start();


function died($error) {
	// your error code can go here
	$_SESSION['deletingTwitchError']=$error;
	echo $error;
	header( 'Location: '.redirect('viewtwitch.php'));
	die();
}


if(isset($_POST['submit_delete_twitch'])){
	// Adapted from http://stackoverflow.com/questions/4554758/how-to-read-if-a-checkbox-is-checked-in-php accessed 23/10/2013
	if(isset($_POST['Delete'])){
		$conn = mysqli_connect("mysql.cms.gre.ac.uk","lj048","naaffe5K","mdb_lj048");
		if (mysqli_connect_errno($conn)) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}

		$query="DELETE FROM post WHERE post_id=".$_GET['post_id'].";";

		$result = mysqli_query($conn, $query);
		if (!$result){
			echo $query;
			died("Error in deleting Twitch. Please try again.");
		} else {
			require 'head.php';
			?></head>
			<?php
			require 'banner.php';
			?><div class="content">
				<p>Deletion has been successful. Please <a href="./index.php?current_page=1">click here</a> to return home.</p>	
				<br class="clear"/>
			</div>
			<?php
			require 'foot.php';
		}
	} else {
		died('Checkbox not selected. Deletion aborted.');
	}
}
?>
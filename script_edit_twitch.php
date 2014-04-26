<?php
require_once 'script_utility.php';

if(isset($_POST['submit_editTwitch'])){
	if(!isset($_POST['Longitude']) || 
	   !isset($_POST['Latitude']) || 
	   !isset($_POST['Species']) ||
	   !isset($_POST['Age']) ||
	   !isset($_POST['Description'])){
		$_SESSION['twitchingError'] = 'Some required fields are missing. Please modify and resubmit.';
	} else if(!is_numeric($_POST['Longitude']) || !is_numeric($_POST['Latitude'])){
		$_SESSION['twitchingError'] = 'Longitude and latitude fields should be numbers. Please modify and resubmit.';
	} else if($_POST['Latitude'] < 51.47 || $_POST['Latitude'] > 51.51){
		$_SESSION['twitchingError'] = 'Latitude should be between 51.47 and 51.51. Please modify and resubmit.';
	} else if($_POST['Longitude'] < -0.02 || $_POST['Longitude'] > 0.02){
		$_SESSION['twitchingError'] = 'Longitude should be between -0.02 and 0.02. Please modify and resubmit.';
	} else {
		if($_POST['Description'] > 250){
			$_SESSION['twitchingError'] = 'Description should be up to 250 characters long.';
		} else if ($_POST['Species'] > 30 ){
			$_SESSION['twitchingError'] = 'Species should be up to 30 characters long.';
		}  else if ($_POST['Age'] > 30 ){
			$_SESSION['twitchingError'] = 'Age should be up to 30 characters long.';
		} else {
			$datetime = $_POST['Year']."-".$_POST['Month']."-".$_POST['Day']." ".$_POST['Hour'].":".$_POST['Minute'].":".$_POST['Second'];
			$now=date('Y-m-d H:i:s');
			if ($datetime > $now ){
				$_SESSION['twitchingError'] = 'The date specified should be before the time on server. The time on server is: '.$now.'.';
			} else {
				$freetext=htmlentities($_POST['Description'], ENT_QUOTES);
				$species=htmlentities($_POST['Species'], ENT_QUOTES);
				$age=htmlentities($_POST['Age'], ENT_QUOTES);

				$datetime = $_POST['Year']."-".$_POST['Month']."-".$_POST['Day']." ".$_POST['Hour'].":".$_POST['Minute'].":".$_POST['Second'];
				if($_POST['Gender'] == "Female"){
					$bird_sex = "0";
				} else {
					$bird_sex = "1";
				}

				$query="UPDATE post SET date_time='".$datetime."', latitude=".$_POST['Latitude'].", longitude=".$_POST['Longitude'].", freetext='".$freetext."', bird_species='".$species."', bird_age='".$age."', bird_sex=".$bird_sex." WHERE post_id=".$_POST['Post_ID'].";";

				openConnDB();
				$result = mysqli_query($conn, $query);
				if (!$result){
					$_SESSION['twitchingError'] = 'Error in editing Twitch. Please try again.';
				} else {
					header('Location: '.redirect('viewtwitch.php'));
				}
			}
		}
	}
}
?>
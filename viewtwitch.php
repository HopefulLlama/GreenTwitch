<?php 
	require 'head.php';
?>
</head>
<?php
	https(false);
	require 'banner.php';

	if(!isset($_GET['post_id']) || is_nan($_GET['post_id']) || $_GET['post_id'] < 1 ){
		echo '<span class=\"error\">Twitch could not be found. Twitch may have been deleted since last view.</span>';
	}
?>
<div class="content">
	<?php
	$query = "SELECT * FROM post, member WHERE post_id=".$_GET['post_id']." AND post.mem_id = member.mem_id";

	$conn = mysqli_connect("mysql.cms.gre.ac.uk","lj048","naaffe5K","mdb_lj048");
	if (mysqli_connect_errno($conn)) {
		pageDied('Network error. Please try again.');
	}
	$result = mysqli_query($conn, $query);	
	$prev_date="";
	if($result->num_rows==1){
		while ($obj = mysqli_fetch_object($result)) {
			if(isset($_SESSION['currentUserID']) && $obj->mem_id == $_SESSION['currentUserID']){
				$owner = true;
			} else {
				$owner = false;
			}
			
			$day = substr($obj->date_time, 8, 2);
			$month = substr($obj->date_time, 5, 2);
			$year = substr($obj->date_time, 0, 4);
			$date = $day."/".$month."/".$year;
			$php_datetime = strtotime($obj->date_time);
			$time = date("h:i A.", $php_datetime);
			if ($date != $prev_date){
				echo "<div class=\"postHeading\">".$date."</div>";
				$prev_date = $date;
			}
			echo "<div class=\"postSubHeading\"";
			if($owner){
				echo " style=\"background-color:#DEEFFA\"";
			}
			echo ">A <strong>".$obj->bird_species."</strong> has been spotted by <strong>".$obj->username."</strong> at approximately <strong>".$time."</strong></div>";
			echo "<div class=\"postContent\"";
			if($owner){
				echo " style=\"background-color:#DEEFFA\"";
			} 
			echo ">";
			echo "<span>Latitude</span>".(float)$obj->latitude."<br/>";
			echo "<span>Longitude</span>".(float)$obj->longitude."<br/>";
			echo "<span>Age of Bird</span>".$obj->bird_age."<br/>";
			if ($obj->bird_sex == 0){
				$gender = "Female";
			} else {
				$gender = "Male";
			}
			echo "<span>Gender</span>".$gender."<br/>";
			echo "<span>Added Notes</span>".$obj->freetext."<br/><hr/>";
			echo "</div>";
			if($owner){
				echo "<br/><div class=\"smallNav\"><a href=\"./twitch.php?post_id=".$obj->post_id."\">Edit</a></div><br/><br/>";
				echo "<form class=\"small\" name=\"delete_twitch\" method=\"post\" action=\"script_delete_twitch.php?post_id=".$obj->post_id."\" onsubmit=\"\">";
				?>
					<fieldset>
						<legend>Deleting this Twitch</legend>
						<p>Deleting a Twitch is irreversible and cannot be undone. Any associated images with the Twitch will also be erased. If you do wish to delete a Twitch however, please select the checkbox as confirmation, and press delete.</p>
						<input type="checkbox" name="Delete" value="Delete"/>Check to delete Twitch<br/>
						<br/><input name="submit_delete_twitch" type="submit" value="Delete this Twitch!"/>
					</fieldset>
				</form>
				<?php
				if(isset($_SESSION['deletingTwitchError'])){
					echo '<span class=\"error\">'.$_SESSION['deletingTwitchError'].'</span>'; 
					unset($_SESSION['deletingTwitchError']); 
				}
			}
		}
		?><div class="postHeading">Image Gallery</div>
		<?php 
		if($owner) { 
			?><div class="postContent">You can upload, edit and remove images here. Any images without captions will not be shown to other users.<br/><?php 
		} ?>
		<?php 
		$query = "SELECT * FROM image WHERE post_id=".$_GET['post_id']." AND alt_text!='';";

		$conn = mysqli_connect("mysql.cms.gre.ac.uk","lj048","naaffe5K","mdb_lj048");
		$result = mysqli_query($conn, $query);	

		/*
			Adapted http://stackoverflow.com/questions/6439230/how-to-go-through-mysql-result-twice to iterate through my result set twice. Accessed 23/10/2013
		*/

		if($result->num_rows>0){
			if($owner){ 
				$image_ids="";
				while ($obj = mysqli_fetch_object($result)) { 
					if ($image_ids==""){
						$image_ids=$obj->image_id;
					} else {
						$image_ids=$image_ids.",".$obj->image_id;
					}
				} 
				mysqli_data_seek($result, 0);
				echo "<form class=\"small\" name=\"edit_image\" method=\"post\" action=\"script_edit_image.php?post_id=".$_GET['post_id']."&amp;image_ids=".$image_ids."\" onsubmit=\"\">";
			} 
			while ($obj = mysqli_fetch_object($result)) { 
				echo "<div class=\"imageBox\"><a href=\"script_get_image.php?image_id=".$obj->image_id."\"><img src=\"script_get_image.php?image_id=".$obj->image_id."\" alt=\"".$obj->alt_text."\" /></a>"; 
				if($owner) { 
					echo "<fieldset>";
					echo "<br/><input type=\"checkbox\" name=\"Delete".$obj->image_id."\" value=\"Delete\"/>Check to delete<br/>";
					echo "<br/><label>Caption:</label><input class=\"restrictWidth\" type=\"text\" name=\"Caption".$obj->image_id."\" value=\"".$obj->alt_text."\" maxlength=\"30\"/>";
					echo "</fieldset>";
				} 
				echo "</div>";
			} ?>
			
			<?php
			if($owner) { ?>
					<div>
						<br/><input name="submit_edit_image" type="submit" value="Save changes!"/>
						<?php
						if(isset($_SESSION['imageReturnMessage'])){
							echo '<br/><span class=\"error\">'.$_SESSION['imageReturnMessage'].'</span.'; 
							unset($_SESSION['imageReturnMessage']); 
						}
						?>
					</div>
				</form>
			<?php 
			} ?>
		<?php 
		} else { ?> 
			<div>No images yet :(</div>
		<?php }
		if($owner) { 
		// http://www.w3schools.com/php/php_file_upload.asp -->
		echo "<br/><form class=\"styled\" enctype=\"multipart/form-data\" name=\"upload_image\" method=\"post\" action=\"script_upload_image.php?post_id=".$_GET['post_id']."\" onsubmit=\"\">"; ?>
			<fieldset>
				<legend>Upload Images</legend>
				<label for="Image">Image:</label><input type="file" id="Image" name="Image"/><br/>
				<label for="Caption">Caption:</label><input type="text" id="Caption" name="Caption" maxlength="30"/><br/>
				<br/>
				<input name="submit_upload_image" type="submit" value="Submit!"/>
			</fieldset>
		</form>
		<?php 
		}
		
		if(isset($_SESSION['uploadImageError'])){
			echo '<span class=\"error\">'.$_SESSION['uploadImageError'].'</span>'; 
			unset($_SESSION['uploadImageError']); 
		}
	} else {
		echo 'Twitch could not be found. Twitch may have been deleted since last view.';
	} ?>
	
	<br class="clear"/>
	</div>
</div>
<?php
	require 'foot.php';
?>
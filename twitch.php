<?php 
	require 'head.php';

	if (isset($_GET['post_id'])){
		openConnDB();

		$query = "SELECT * FROM post, member WHERE post_id=".$_GET['post_id']." AND post.mem_id = member.mem_id";
		$result = mysqli_query($conn, $query);	
		if ($result->num_rows==1){
			while ($obj = mysqli_fetch_object($result)) {
				if(isset($_SESSION['currentUserID']) && $obj->mem_id == $_SESSION['currentUserID']){
					$owner = true;
				} else {
					$owner = false;
				}
				if(!$owner){
					$_GET['current_page']=1;
					header('Location: '.redirect('index.php'));
				} else {
					$editMode=true;

					$post_id = $obj->post_id;
					$day = substr($obj->date_time, 8, 2);
					$month = substr($obj->date_time, 5, 2);
					$year = substr($obj->date_time, 0, 4);

					$hour = substr($obj->date_time, 12, 2);
					$minute = substr($obj->date_time, 14, 2);
					$seconds = substr($obj->date_time, 17, 2);

					$longitude = $obj->longitude;
					$latitude = $obj->latitude;
					
					$species = $obj->bird_species;
					$age = $obj->bird_age;
					if($obj->bird_sex == 0){
						$male = true;
					} else {
						$male = false;
					}
					$desc = $obj->freetext;
				}
			}
		}	
	} else {
		$editMode=false;
		$day = date('j');
		$month = date('n');
		$year = date('Y');

		$hour = date('G');
		$minute = date('i');
		$seconds = date('s');

		$latitude = "51.49";
		$longitude = "0";
			
		$species = "";
		$age = "";
		
		$desc = "";

		$male = true;
	}

	if(isset($_POST['submit_createTwitch']) || isset($_POST['submit_editTwitch'])){
		$day=$_POST['Day'];
		$month=$_POST['Month'];
		$year=$_POST['Year'];

		$hour=$_POST['Hour'];
		$minute=$_POST['Minute'];
		$seconds=$_POST['Second'];

		$longitude = $_POST['Longitude'];
		$latitude = $_POST['Latitude'];
					
		$species = $_POST['Species'];
		$age = $_POST['Age'];
		if($_POST['Gender'] == "Female"){
			$male=false;
		} else {
			$male=true;
		}
		$desc = $_POST['Description'];
	}
?>
	<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB1HRKDzyfA1twNTA-YuUlSu3rk9YO7PgI&amp;sensor=true">
    </script>
    <script type="text/javascript">
		var lat = "<?php Print($latitude); ?>";
		var lng = "<?php Print($longitude); ?>"
		function initialize() {
			var sighting = new google.maps.LatLng(lat, lng);
			var mapOptions = {
				center: sighting,
				zoom: 13
			};
			var map = new google.maps.Map(document.getElementById("map-canvas"),
				mapOptions);
			// Draw rectangle around accepted area
			var rectangle = new google.maps.Rectangle({
				strokeColor: '#00FF00',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#00FF00',
				fillOpacity: 0.1,
				map: map,
				bounds: new google.maps.LatLngBounds(
				  new google.maps.LatLng(51.47, -0.02),
				  new google.maps.LatLng(51.51, 0.02))
			  });

			// Place a draggable marker on the map
			var marker = new google.maps.Marker({
				position: sighting,
				map: map,
				draggable:true,
				title:"Sighting location."
			});
			google.maps.event.addListener(marker, 'position_changed', function() {
				document.getElementsByName("Latitude")[0].value=marker.getPosition().lat().toString().substr(0,10);		
				document.getElementsByName("Longitude")[0].value=marker.getPosition().lng().toString().substr(0,10);
				map.setCenter(marker.getPosition());
			});
		}
		google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<?php
	https(false);
	require 'banner.php';

	require 'script_create_twitch.php';
	require 'script_edit_twitch.php';
?>
<div class="content">
	<?php
		if(!isset($_SESSION['activeAccount']) || !$_SESSION['activeAccount']){ ?>
			<p>Creating a post is reserved for those with an active account. Please create an account or verify your account.</p>
	<?php
		} else { 
			if (!$editMode){
				?><p>Saw some crazy bird and ready to start twitching? Fill in the form below and share your twitching experience with other avid and chronic twitchers.</p>
				<?php
			} else {
				?><p>Make the changes as needed, and hit the save button to keep your changes.
				<?php
			} ?>
			<form class="styled" method="post" action=<?php if(isset($_GET['post_id'])){echo '"twitch.php?post_id='.$_GET['post_id'].'"'; } else { echo '"twitch.php"'; }?> onsubmit="return validateTwitch(this)">
				<fieldset>
					<legend>Twitch</legend><br/>
					<legend>Date/Time and Location</legend>
					<label>Date</label><br/>
					<!-- A messy bit of stuff to create selection menus for Date/Time-->
					<label>DD/MM/YYYY</label>
					<select name="Day"><?php 
						for ($x=1; $x<=31; $x++){
							if($x==$day){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							}else{
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
					<select name="Month"><?php 
						for ($x=1; $x<=12; $x++){
							if($x==$month){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							} else {
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
						<select name="Year"><?php 
						for ($x=2010; $x<=date('Y'); $x++){
							if($x==$year){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							} else {
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
					<br/><br/>
					<label>Time</label><br/>
					<label>HH:MM:SS</label>
					<select name="Hour"><?php 
						for ($x=0; $x<=23; $x++){
							if($x==$hour){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							} else {
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
					<select name="Minute"><?php 
						for ($x=0; $x<=59; $x++){
							if(sprintf("%02s", $x)==$minute){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							} else {
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
						<select name="Second"><?php 
						for ($x=0; $x<=59; $x++){
							if(sprintf("%02s", $x)==$seconds){
								echo "<option value=\"".sprintf("%02s", $x)."\" selected=\"selected\">".sprintf("%02s", $x)."</option>";
							} else {
								echo "<option value=\"".sprintf("%02s", $x)."\">".sprintf("%02s", $x)."</option>";
							}
						} ?>
					</select>
					<br/><br/>
					<label>Latitude:</label><input type="text" name="Latitude" maxlength="10" onkeyup="updatePosition()" <?php echo "value=\"".(float)$latitude."\""; ?>/> (Between 51.47 and 51.51)<br/>
					<label>Longitude:</label><input type="text" name="Longitude" maxlength="10" onkeyup="updatePosition()" <?php echo "value=\"".(float)$longitude."\""; ?>/> (Between -0.02 and 0.02) <br/>
					<div id="map-canvas">
						<noscript>
							<p><br/>JavaScript has been disabled! Re-enable JavaScript within your browser to allow for a greater Twitching experience. GreenTwitch uses JavaScript to provide you with Google Maps to enter location data.</p>
						</noscript>
					</div>
					<br/>

					<legend>Bird Details</legend>
					<label>Species:</label><input type="text" name="Species" maxlength="30" <?php echo "value=\"".$species."\""; ?>/><br/>
					<label>Age:</label><input type="text" name="Age" maxlength="30" <?php echo "value=\"".$age."\""; ?>/><br/>
					<label>Gender:</label><input type="radio" name="Gender" value="Male" <?php if($male){ echo "checked=\"checked\""; } ?>/>Male<input type="radio" name="Gender" value="Female" <?php if(!$male){ echo "checked=\"checked\""; } ?>/>Female<br/>
					<br/>
					
					<legend>Post Information</legend>
					<label>Description:</label><textarea rows="6" cols="50" name="Description"><?php echo $desc; ?></textarea><br/>
					<?php if(!$editMode){ ?><br/><p>You may add images to this twitch once it has been submitted. You will be taken to view the twitch, where you can view the image gallery too!</p><?php } else { echo "<input type=\"hidden\" name=\"Post_ID\" value=\"".$post_id."\"></input>"; } ?>
					<input name="<?php if ($editMode){ echo "submit_editTwitch"; } else { echo "submit_createTwitch"; } ?>" type="submit" value="Submit!"/>	
					<?php 
						if(isset($_SESSION['twitchingError'])){
							echo '<br/><br/><span class=\"error\">'.$_SESSION['twitchingError'].'</span>'; 
							unset($_SESSION['twitchingError']); 
						}
					?>

				</fieldset>
			</form>
	<?php
		}
	?>
</div>
<?php
	require 'foot.php';
?>
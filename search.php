<?php 
	require 'head.php';
?>
	<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB1HRKDzyfA1twNTA-YuUlSu3rk9YO7PgI&amp;sensor=true">
    </script>
    <script type="text/javascript">
		function initialize() {
			var sighting = new google.maps.LatLng(51.49, 0);
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

			// Define a rectangle and set its editable property to true.
			var search = new google.maps.Rectangle({
				strokeColor: '#000000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#000000',
				fillOpacity: 0.1,
				editable: true,
				draggable: true,
				map: map,
				bounds: new google.maps.LatLngBounds(
				  new google.maps.LatLng(51.47, -0.020000001),
				  new google.maps.LatLng(51.51, 0.020000001))
			});

			google.maps.event.addListener(search, 'bounds_changed', function(){updateLngLat();});
			google.maps.event.addListener(search, 'drag', function(){updateLngLat();});

			function updateLngLat(){
				document.getElementsByName("LatitudeLower")[0].value=search.getBounds().getSouthWest().lat().toString().substr(0,10);		
				document.getElementsByName("LongitudeLower")[0].value=search.getBounds().getSouthWest().lng().toString().substr(0,10);		
				document.getElementsByName("LatitudeUpper")[0].value=search.getBounds().getNorthEast().lat().toString().substr(0,10);		
				document.getElementsByName("LongitudeUpper")[0].value=search.getBounds().getNorthEast().lng().toString().substr(0,10);		
			}
		}
		google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<?php
	https(false);
	require 'banner.php';

	if(isset($_GET['submit_search'])){
		$proceed = true;
		if(!empty($_GET['LatitudeLower']) && !empty($_GET['LatitudeUpper'])){
			if ( $_GET['LatitudeLower'] < 51.47 || $_GET['LatitudeLower'] > 51.51 || $_GET['LatitudeUpper'] < 51.47 || $_GET['LatitudeUpper'] > 51.51){
				$_SESSION['searchError']="Both longitude fields must be between 51.47 and 51.51.";
				$proceed=false;
			}
		}  else if (!empty($_GET['LatitudeLower']) xor !empty($_GET['LatitudeUpper'])){
			$_SESSION['searchError']="Both longitude fields must be between 51.47 and 51.51.";
			$proceed=false;
		}
		if (!empty($_GET['LongitudeLower']) && !empty($_GET['LongitudeUpper'])){
			if( $_GET['LongitudeLower'] < -0.02 || $_GET['LongitudeLower'] > 0.02 || $_GET['LongitudeUpper'] < -0.02 || $_GET['LongitudeUpper'] > 0.02){
				$_SESSION['searchError']="Both latitude fields must be between -0.02 and 0.02.";
				$proceed=false;
			}
		} else if (!empty($_GET['LongitudeLower']) xor !empty($_GET['LongitudeUpper'])){
			$_SESSION['searchError']="Both latitude fields must be between -0.02 and 0.02.";
			$proceed=false;
		}

		if($proceed){
			$life = time()+60*60*24*30;
			// Set cookies to remember the search terms.
			setcookie("species", $_GET['Species'], $life);
			setcookie("lowerlat", $_GET['LatitudeLower'], $life);
			setcookie("upperlat", $_GET['LatitudeUpper'], $life);
			setcookie("lowerlong", $_GET['LongitudeLower'], $life);
			setcookie("upperlong", $_GET['LongitudeUpper'], $life);
			setcookie("image", $_GET['Image'], $life);

			header('Location: searchresults.php?Species='.$_GET['Species'].'&LatitudeLower='.$_GET['LatitudeLower'].'&LatitudeUpper='.$_GET['LatitudeUpper'].'&LongitudeLower='.$_GET['LongitudeLower'].'&LongitudeUpper='.$_GET['LongitudeUpper'].'&Image='.$_GET['Image'].'&current_page=1');
		}
	}
?>
<!-- Main content; stuff displayed beneath the banner.-->
<div class="content">
	<p>Use the form below to add some filter criteria to the twitches you view!</p>
	<form class="styled" action="search.php" onsubmit="return validateSearch(this)">
		<fieldset>
			<legend>Search</legend>
			<label for="Species">Bird Species:</label><input type="text" maxlength="30" name="Species" id="Species" value="<?php
				if(isset($_GET['Species'])){ 
					echo $_GET['Species']; 
				} else {
					if(isset($_COOKIE['species'])){
						echo $_COOKIE['species'];
					}
				}
			?>"/><br/>
			<br/>
			<label>Latitude:</label><input type="text" maxlength="10" name="LatitudeLower" id="LatitudeLower" value="<?php 
				if(isset($_GET['LatitudeLower'])){
					echo $_GET['LatitudeLower'];
				} else {
					if(isset($_COOKIE['lowerlat'])){
						echo $_COOKIE['lowerlat']; 
					} else {
						echo "51.47"; 
					}
				}
			?>"/> to <input type="text" maxlength="10" name="LatitudeUpper" id="LatitudeUpper" value="<?php 
				if(isset($_GET['LatitudeUpper'])){ 
					echo $_GET['LatitudeUpper']; 
				} else { 
					if(isset($_COOKIE['upperlat'])){
						echo $_COOKIE['upperlat'];
					} else {
						echo "51.51"; 
					}
				} 
			?>"/> (Between 51.47 and 51.51)<br/>
			<label>Longitude:</label><input type="text" maxlength="10" name="LongitudeLower" id="LongitudeLower" value="<?php 
			if(isset($_GET['LongitudeLower'])){ 
				echo $_GET['LongitudeLower']; 
			} else {
				if(isset($_COOKIE['lowerlong'])){
					echo $_COOKIE['lowerlong'];
				} else {
					echo "-0.02";
				}
			} ?>"/> to <input type="text" maxlength="10" name="LongitudeUpper" id="LongitudeUpper" value="<?php if(isset($_GET['LongitudeUpper'])) { echo $_GET['LongitudeUpper']; } else { echo "0.02"; } ?>"/> (Between -0.02 and 0.02) <br/>
			<div id="map-canvas">
				<noscript>
					<p><br/>JavaScript has been disabled! Re-enable JavaScript within your browser to allow for a greater Twitching experience. GreenTwitch uses JavaScript to provide you with Google Maps to enter location data.</p>
				</noscript>
			</div>
			<br/>
			<?php
				if(isset($_GET['Image'])){
					$image = $_GET['Image'];
				} else if (isset($_COOKIE['Image'])){
					$image = $_GET['Image'];
				} else {
					$image = 'Ignore';
				}
			?>
			<input type="radio" name="Image" value="Ignore" <?php if($image=='Ignore'){ echo "checked=\"checked\""; } ?>/>Do not filter by images<br/>
			<input type="radio" name="Image" value="No Image" <?php if($image=='No Image'){ echo "checked=\"checked\""; } ?>/>Only twitches with no images<br/>
			<input type="radio" name="Image" value="Image" <?php if($image=='Image'){ echo "checked=\"checked\""; } ?>/>Only twitches with images<br/>
			<br/>
			<input name="submit_search" type="submit" value="Go!"/><br/>
			<input type="hidden" name="current_page" value="1"/>
			<?php 
				if(isset($_SESSION['searchError'])){
					echo '<br/><span class=\"error\">'.$_SESSION['searchError'].'</span>'; 
					unset($_SESSION['searchError']); 
				}
			?>
		</fieldset>
	</form>
	<br class="clear" />
</div>

<?php 
	require 'foot.php';
?>
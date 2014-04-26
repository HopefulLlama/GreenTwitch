<?php 
	require 'head.php';
?>
</head>
<?php
	https(false);
	require 'banner.php';

	require_once 'class_paginator_search.php';
?>

<!-- Main content; stuff displayed beneath the banner.-->
<div class="content">
	<?php
		$clause = "";
		if(!empty($_GET['Species'])){
			$clause = $clause."post.bird_species LIKE '%".$_GET['Species']."%' AND ";
		}
		if(!empty($_GET['LatitudeLower']) && !empty($_GET['LatitudeUpper'])){
			 if ( $_GET['LatitudeLower'] < 51.47 || $_GET['LatitudeLower'] > 51.51 || $_GET['LatitudeUpper'] < 51.47 || $_GET['LatitudeUpper'] > 51.51){
				$_SESSION['searchError']="Both longitude fields must be between 51.47 and 51.51.";
				header('Location: ./search.php');
			} else {
				$clause = $clause."post.latitude >=".$_GET['LatitudeLower']." AND post.latitude <= ".$_GET['LatitudeUpper']." AND ";
			}
		} else if (!empty($_GET['LatitudeLower']) xor !empty($_GET['LatitudeUpper'])){
			$_SESSION['searchError']="Both longitude fields must be between 51.47 and 51.51.";
			header('Location: ./search.php');
		}
		if (!empty($_GET['LongitudeLower']) && !empty($_GET['LongitudeUpper'])){
			if( $_GET['LongitudeLower'] < -0.02 || $_GET['LongitudeLower'] > 0.02 || $_GET['LongitudeUpper'] < -0.02 || $_GET['LongitudeUpper'] > 0.02){
				$_SESSION['searchError']="Both latitude fields must be between -0.02 and 0.02.";
				header('Location: ./search.php');
			} else {
				$clause = $clause."post.longitude >=".$_GET['LongitudeLower']." AND post.longitude <= ".$_GET['LongitudeUpper']." AND ";
			}
		} else if (!empty($_GET['LongitudeLower']) xor !empty($_GET['LongitudeUpper'])){
			$_SESSION['searchError']="Both latitude fields must be between -0.02 and 0.02.";
			header('Location: ./search.php');
		}
		
		if($_GET['Image'] == "No Image"){
			$clause = $clause." NOT EXISTS (SELECT * FROM image WHERE post.post_id = image.post_id) AND ";
		} else if ($_GET['Image'] == "Image") {
			$clause = $clause." EXISTS (SELECT * FROM image WHERE post.post_id = image.post_id) AND ";
		}
		$criteria = "WHERE ".$clause." post.mem_id = member.mem_id";
		$paginator = new PaginatorSearch($criteria, "searchresults.php");
		$paginator->display_data($criteria);
	?>
<br class="clear" />
</div>

<?php 
	require 'foot.php'
?>
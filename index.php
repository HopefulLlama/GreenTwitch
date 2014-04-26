<?php 
	require 'head.php';
?>
</head>
<?php
	https(false);
	require 'banner.php';

	require_once 'class_paginator.php';
?>

<!-- Main content; stuff displayed beneath the banner.-->
<div class="content">
	<?php
		if(isset($_SESSION['indexError'])){
			echo '<span class=\"error\">'.$_SESSION['indexError'].'</span><br/><br/>'; 
			unset($_SESSION['indexError']); 
		}

		$criteria = "WHERE post.mem_id = member.mem_id";
		$paginator = new Paginator($criteria, "index.php");
		$paginator->display_data($criteria);
	?>
<br class="clear" />
</div>

<?php 
	require 'foot.php'
?>
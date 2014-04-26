<?php
/* VERY adapted from http://net.tutsplus.com/tutorials/php/how-to-paginate-data-with-php/ */
require_once 'script_utility.php';

class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $page_total;
	var $page_range;
	var $source;
	
	function Paginator($criteria, $source){
		$this->source = $source;
		$this->items_per_page=5;
		$this->page_range=3;	

		$conn = mysqli_connect("mysql.cms.gre.ac.uk","lj048","naaffe5K","mdb_lj048");
		if (mysqli_connect_errno($conn)) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}

		$query = "SELECT COUNT(*) AS total FROM post, member ".$criteria;

		$result = mysqli_query($conn, $query);
		if($result){
			while ($row = $result->fetch_assoc()){
				$this->items_total = $row['total'];
			}
		} else {
			echo "<p>No results found :(</p>";
		}
			
		if (ceil($this->items_total / $this->items_per_page) == 0){
			$this->page_total = 1;
		} else {
			$this->page_total =	ceil($this->items_total / $this->items_per_page);
		}

		if($_GET['current_page'] < 1){
			$this->current_page=1;
			$_GET['current_page']=$this->current_page;
			header("Location: ".redirect($this->source));
		} else if ($_GET['current_page'] > $this->page_total) {
			$this->current_page=$this->page_total;
			$_GET['current_page']=$this->current_page;
			header("Location: ".redirect($this->source));
		} else {
			$this->current_page=$_GET['current_page'];
		}
	}

	function display_data($criteria){

		$query = "SELECT * FROM post, member ".$criteria." ORDER BY date_time DESC LIMIT ".$this->items_per_page*($this->current_page-1).",".$this->items_per_page;

		$conn = mysqli_connect("mysql.cms.gre.ac.uk","lj048","naaffe5K","mdb_lj048");
		if (mysqli_connect_errno($conn)) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}
		$result = mysqli_query($conn, $query);	
		$prev_date="";
		if($result->num_rows>0){
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
				echo ">";
				
				echo "<a href=\"./viewtwitch.php?post_id=".$obj->post_id."\">A <strong>".$obj->bird_species."</strong> has been spotted by <strong>".$obj->username."</strong> at approximately <strong>".$time."</strong></a></div>";
				echo "<div class=\"postContent\"";
				if($owner){
					echo " style=\"background-color:#DEEFFA\"";
				} 
				echo ">";
			
				$imageQuery="SELECT * FROM image WHERE post_id=".$obj->post_id." LIMIT 0,1;";
				$imageResult=mysqli_query($conn, $imageQuery);
				if($imageResult->num_rows == 1){
					while($imageObj = mysqli_fetch_object($imageResult)) {
						echo "<div class=\"left margin\"><img src=\"script_get_image.php?image_id=".$imageObj->image_id."\" alt=\"".$imageObj->alt_text."\" /></div>";
					}
				}

				echo "<div class=\"left margin\">";
				echo "<span>Latitude</span>".(float)$obj->latitude."<br/>";
				echo "<span>Longitude</span>".(float)$obj->longitude."<br/>";
				echo "<span>Age of Bird</span>".$obj->bird_age."<br/>";
				if ($obj->bird_sex == 0){
					$gender = "Female";
				} else {
					$gender = "Male";
				}
				echo "<span>Gender</span>".$gender."<br/>";
				echo "<span>Added Notes</span>".$obj->freetext."<br/>";
				echo "</div>";

				echo "<br class=\"clear\"/><hr/></div>";
			}
			$this->display_page_menu();
		} else {
			echo "No results found :(";
			echo "<br/>";
		}
	}

	function display_page_menu(){
		echo "<div class=\"pageMenu\">";
		if ($this->current_page > 1){
			$prevpage=$this->current_page-1;
			$_GET['current_page']=$prevpage;
			$url = redirect($this->source);
			echo "<span><a href=\"./".$url."\">Prev</a></span>";
		}
		for($x = $this->current_page - $this->page_range; $x <= $this->current_page + $this->page_range; $x++){
			if($x > 0 && $x <= $this->page_total){
				if ($x == $this->current_page){
					echo "<span>".$x."</span>";
				} else {
					$_GET['current_page']=$x;
					$url = redirect($this->source);
					echo "<span><a href=\"./".$url."\">".$x."</a></span>";
				}
			}
		}
		if ($this->current_page < $this->page_total){
			$nextpage=$this->current_page+1;
			$_GET['current_page']=$nextpage;
			$url = redirect($this->source);
			echo "<span><a href=\"./".$url."\">Next</a></span>";
		}
		echo "</div>";
	}
}

?>
<?php
/*
	Code adapted from http://labs.cms.gre.ac.uk/db/mysqlphp.asp (accessed 14/10/2013)
*/
$conn = mysqli_connect("mysql.cms.gre.ac.uk",$username,$password,"mdb_lj048");

function openConnDB(){
	$conn = mysqli_connect("mysql.cms.gre.ac.uk",$username,$password,"mdb_lj048");
	if (mysqli_connect_errno($conn)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
}

function closeConnDB(){
	$conn = mysqli_connect("mysql.cms.gre.ac.uk",$username,$password,"mdb_lj048");

	mysqli_close($conn);
}

?>

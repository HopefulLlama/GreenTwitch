<?php
	require_once 'script_utility.php';

	session_start();
	unset($_SESSION['currentUser']);
	unset($_SESSION['currentUserID']);
	$_SESSION['activeAccount']=false;
	header('Location: '.redirect('index.php'));
?>
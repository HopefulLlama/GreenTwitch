<?xml version="1.0" encoding="UTF-8"?>
<?php
require_once 'script_utility.php';

	/*
		http://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started [Accessed 25 November 2013]
	*/
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	require 'script_db_handler.php';
	function https($bool){
		if ($bool){
			//-------------------------------------------------- 
			//Security check for HTTPS to secure transit layer 
			//-------------------------------------------------- 
			 
			//Code from http://stackoverflow.com/questions/5106313/redirecting-from-http-to-https-with-php [Accessed 25 November 2013]
			 
			if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){ 
				$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
				header('Location: '.$redirect); 
			} 
		} else {
			if(isset($_SERVER['HTTPS'])){ 
				$redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
				header('Location: '.$redirect); 
			} 
		}
	}

	function pageDied($message){
		$_SESSION['indexError'] = $message;
		$_GET['current_page']=1;
		header('Location: '.redirect('index.php'));
		die();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">

<head>
	<title>GreenTwitch</title>
	<meta name="Author" content="Jonathan Law" />
	<meta name="Keywords" content="Jonathan Law, University of Greenwich, GreenTwitch"/>
	<meta name="Description" content="GreenTwitching" />
	<link rel="shortcut icon" href="favicon.png"/>
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	<style type="text/css"></style>
	<script src="javascript.js" type="text/javascript"></script>

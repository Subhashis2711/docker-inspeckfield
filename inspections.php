<?php
session_start();

require "autoload.php";

// check if there's an active login
if(!Auth::checkInAuth()){
	$redirect_url = $_SERVER['REQUEST_URI'];
	session_start();
	$_SESSION['REFERER'] = $redirect_url;
	header("location: index.php");
	exit;
}

Inspection::drawMainApp();
?>
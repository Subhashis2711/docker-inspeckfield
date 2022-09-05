<?php
session_start();

require "autoload.php";

// check if there's an active login
if(!Auth::checkInAuth()){
	header("location: index.php");
	exit;
}

User::drawMainApp();
?>
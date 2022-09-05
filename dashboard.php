<?php
require "autoload.php";

// check if there's an active login
if(!Auth::checkInAuth()){
	header("location: index.php");
	exit;
}

if(Auth::checkAdmin() || Auth::checkSuperAdmin()){
	DashBoard::drawMainApp();
}else{
	header("location: inspections.php");
}

?>

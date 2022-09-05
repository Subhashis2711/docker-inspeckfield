<?php
require "autoload.php";
// check if there's an active login
if(Auth::checkInAuth()){
	Ui::logError('Found an active login. Continue to Dashboard.');
	
	header("location: inspections.php");
	
	exit;
}

$res = Auth::attemptLogin();

if(!$res['login_attempt']){
	// Initial draw
	Auth::drawMainApp();
}else if(isset($res['error']) && $res['error']){
	// Invalid login
	Auth::drawMainApp(array('login_message'=>$res['message']));
}else{
	if(isset($_SESSION['REFERER']) && !empty($_SESSION['REFERER'])){
		header("location: ".$_SESSION['REFERER']);
	}else{
	// Success
		header("location: inspections.php");
	}
	
}
?>
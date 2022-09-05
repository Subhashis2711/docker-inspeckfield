<?php
header('Content-Type: text/html; charset=UTF-8');

require "autoload.php";

if(isset($_POST['packet'])){
  	$POST = Ui::parseStrToQuery(base64_decode($_POST['packet']));
}else if($_GET['packet'] !=''){
	$POST = Ui::parseStrToQuery(base64_decode($_GET["packet"]));
}else if(isset($_GET['action']) && isset($_GET['class'])){
	$POST = $_GET;
}else{
	error_log('ERROR: No Action Found Action:'.$_POST['action'].' Class:'.$_POST['class']);
	error_log('Class:'.$_GET['class']);
	error_log('Action:'.$_GET['action']);
	die('No Action Found!');
}

// Ui::logArray($POST);

if(PHP_DEBUG_POST){
	error_log('=== POST PARAMS ===');
	foreach($POST as $k=>$v){
		error_log('Post var: '.$k.' === '.$v);
	}
	error_log('==================='); 
}

if(isset($POST['className'])){
	$POST['class']=$POST['className'];
	unset($POST['className']);
}

if(isset($POST['action']) && isset($POST['class'])){
	$action 	= $POST['action'];
	$class 		= $POST['class'];
	$a 			= new $class;
	$a->$action($POST);
}else{
	error_log('No Action Found '.$POST['action'].' '.$POST['class']);
	error_log(base64_decode($_GET['packet']));
	error_log(base64_decode($_POST['packet']));
	error_log($POST['action'].'----');
	die('No Action Found!');
}
?>
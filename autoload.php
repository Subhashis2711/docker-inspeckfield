<?php
// // server should keep session data for AT LEAST 3 hour
// ini_set('session.gc_maxlifetime', 10800);

// // each client should remember their session id for EXACTLY 3 hour
// session_set_cookie_params(10800); 


// error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',0);
require 'vendor/autoload.php';
require "config.php";
require "database.php";

// generate jwt token
// if(isset($_SESSION['current_user']) && !empty($_SESSION['current_user'])){
// 	$url = API_URL.'generateToken.php';
// 	try{
// 		$client = new GuzzleHttp\Client([
// 			'headers' => ['Content-Type' => 'application/json']
// 		]);
// 		$response = $client->get($url,
// 			['body' => json_encode(
// 				array(
// 					'secret' => JWT_SECRET,
// 					'user_id' => $_SESSION['current_user']['user_id'],
// 					'username' => $_SESSION['current_user']['username']
// 				)
// 			)]
// 		);
// 		$res = json_decode($response->getBody()->getContents(),true);
// 		if($res['status'] == 200){
// 			$token = $res['token']['jwt'];
// 			$_SESSION['current_user']['token'] = $token;
// 		}
// 	}catch(Exception $e){
		
// 	}
// }

/**
 * @uses include the called class's MVC files, if present (using php autoload functions)
 * @return void
 */
function autoloadMVC($class_name){
	$model_file 		= 'model/'.$class_name.'.Model.php';
	$controller_file 	= 'controller/'.$class_name.'.Controller.php';
	$view_file 			= 'view/'.$class_name.'.View.php';

	// Check in model
	if(file_exists($model_file)) {
		require_once $model_file;
	}

	// Check in controller
	if(file_exists($controller_file)) {
		require_once $controller_file;
	}

	// Check in view
	if(file_exists($view_file)) {
		require_once $view_file;
	}
}
spl_autoload_register('autoloadMVC');
?>
<?php
// use PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
/**
 * Contains all utility functions used in the application
 * Generic core methods to be added here
 **/

class UiController{
	
	static function loadCssLib($lib,$path='assets/css/custom/'){
		$full_path		= $path.$lib.'.css';

		if(file_exists($full_path)){
			?>
			<link rel="stylesheet" type="text/css" media="all" href="<?=$full_path?>" />
			<?
		}
	}
    
	static function loadJsLib($lib,$path='assets/js/custom/'){
		$full_path		= $path.$lib.'.js';

		if(file_exists($full_path)){
			?>
			<script type="text/javascript" src="<?=$full_path?>"></script>
			<?
		}
	}

	static function loadImage($params=array()){
        $asset_name    	= (isset($params['asset_name']) && $params['asset_name'])?$params['asset_name']:'';
        $extension      = (isset($params['ext']) && $params['ext'])?$params['ext']:'png';
        $css_class 		= (isset($params['css_class']) && $params['css_class'])?$params['css_class']:'';
        $alt_text 		= (isset($params['alt_text']) && $params['alt_text'])?$params['alt_text']:'';
        $attr 			= (isset($params['attr']) && $params['attr'])?$params['attr']:'';
        $path 			= (isset($params['path']) && $params['path'])?$params['path']:'assets/images/';

        if(!$asset_name){
            return false;
        }

        $full_path       = $path.$asset_name.'.'.$extension;

        if(file_exists($full_path)){
            ?>
            <img src="<?=$full_path?>" class="<?=$css_class?>" alt="<?=$alt_text?>" <?=$attr?>>
            <?
        }
    }

	static function logArray($arr){
		if(PHP_LOG){
			if(is_array($arr)){
				ksort($arr);
				$output = print_r($arr, true);

				error_log('==== BEGIN logArray ====');
				error_log($output);
				error_log('==== FINISH logArray ====');
			}else{
				error_log('INFO: Ui::logArray found no elements to display');
			}
		}
	}

	static function logError($txt){
		if(PHP_LOG){
			error_log('===============================');
			error_log('   '.$txt);
			error_log('===============================');
		}
	}

	static function parseStrToQuery($str){
		// filter nulls to accommodate for trailing &'s in params
		$pairs  	= array_filter(explode('&', $str));
		$arr    	= array();

		foreach($pairs as $pair) {
			$key_val_pair 			= explode('=', $pair, 2);
			$POST[$key_val_pair[0]] = isset($key_val_pair[1]) ? urldecode(rawurldecode(rawurldecode($key_val_pair[1]))) : null;
		}
		return $POST;
	}

	static function htmlToObj($html){

		$dom = new DOMDocument();
		$dom->loadHTML($html);
		return self::elementToObj($dom->documentElement);
	}

	static function elementToObj($element){

		$obj = array( "tag" => $element->tagName );
		foreach ($element->attributes as $attribute) {
			$obj[$attribute->name] = $attribute->value;
		}
		foreach ($element->childNodes as $subElement) {
			if ($subElement->nodeType == XML_TEXT_NODE) {
				$obj["html"] = $subElement->wholeText;
			}
			else {
				$obj["children"][] = self::elementToObj($subElement);
			}
		}
		return $obj;
	}

	static function sendEmail($params=array()){
		
		$res 			= array('status' => 'Failed', 'message' => 'Unable to send email. Try again!');
		$user_ids 		= (!empty($params['users'])) ? explode(',', $params['users']) : [];
		$inspection_id 	= $params['inspection_id'];
		$inspection_link = "http://www.loc.inspekfield.com/v1/request-inspection.php?inspection_id=".$inspection_id;
		$type 			= $params['type'];
		$base = __DIR__ . '/../';

		print_r($user_ids);

		if(is_array($user_ids) && !empty($user_ids)){
			
			$users		= array();
			$message 	= file_get_contents($base.'/EmailTemplates/'.$type.'.html');
			$message 	= str_replace('{{inspection-id}}', $inspection_id, $message);
			$message 	= str_replace('{{inspection-link}}', $inspection_link, $message);

			foreach($user_ids as $id){
				array_push($users, User::fetchUser($id));
			}

			$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

			try {
				//Server settings
				$mail->SMTPOptions = array(
					'ssl' => array(
						'ciphers' => 'DEFAULT:!DH'
					)
				);
				$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      
				$mail->isSMTP();                                            
				$mail->Host       = 'mail1.inspektech.com';                     
				$mail->SMTPAuth   = true;                                   
				$mail->Username   = SMTP_USERNAME;                     
				$mail->Password   = SMTP_PASSWORD;                               
				$mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;            
				$mail->Port       = 587;   

				//Recipients
				$mail->setFrom('info@inspekfield.com', 'Inspektech');
				foreach($users as $user){
					$email = $user['email'];
					$name  = $user['full_name'];
					$mail->addAddress($email, $name);     //Add a recipient

				}
				$mail->addReplyTo('info@inspektech.com', 'Inspektech');
				// Set the subject
				$mail->Subject = "Inspekfield | File approved for workflow | #".$inspection_id;;

				//Set the message
				$mail->MsgHTML($message);

				// Send the email
				if($mail->send()){
					$res['status']  = "Success";
					$res['message'] = "Email sent successfully";
				}
			}catch (PHPMailer\PHPMailer\Exception $e) {
				$res['error'] = $mail->ErrorInfo;
			}
		}
		echo  json_encode($res);
	}
}
?>
<?php
/**
 * Controller class containing all the Tor related functionalities.
 *
 * @since 1.0
 */
class TorController extends TorModel{

	static function save($params=array()){
		
		$inspection = RequestInspection::actionGetRow($params);
		$status 	= $inspection['status'];

		if(($status == 'itvawip' || $status == 'fileclosed' || $status == 'approved') && $inspection['itva_id'] == $_SESSION['current_user']['id']) {
			self::saveItvaData($params);
		} else {
			self::actionSave($params);
		}
	}

	static function saveItvaData($params=array()){
		$fi_data 			= self::getData($params);
		
		if(strpos($params['key'], '_comments') !== false) {
			$params['value'] = $params['value'].'_itva';
		}else{
			$old_value_array 	= json_decode($fi_data[$params['key']], true);
			$new_value_array	= json_decode($params['value'], true);

			$new_value_keys		= array_keys($new_value_array[0]);

			foreach($new_value_keys as $key){
				$old_value_array[0][$key] = $new_value_array[0][$key];
			}

			$params['value'] = json_encode($old_value_array);
		}

		

		self::actionSave($params);
	}

	static function getData($params=array()){
		
		return self::actionGetData($params);
	}

	static function createTorInspection($params=array()){
		$parent_id = trim($params['parent_id']);
		$current_user_id = $_SESSION['current_user']['id'];

		// default response
		$resp = array(
					'error'		=> true,
					'message'	=> 'Inspection Parent ID can\'t be empty.'
				);

		if($parent_id){
			// check if inspection is already created in the system
			$inspection = RequestInspection::actionGetRow(array('inspection_id'=>$parent_id));

			if(isset($inspection) && !empty($inspection)){
				$tor_number         = self::actionGetTorNumber(array('parent_id'=>$parent_id, 'tor_type'=>$params['tor_type']));
				// create inspection
				$inspection_id 		= self::actionCreateTorInspection(array('parent_id'=>$parent_id,'user_id'=>$current_user_id, 'tor_number' => $tor_number, 'tor_type' => $params['tor_type']));

				if($inspection_id){
					$resp['error']		= false;
					$resp['id']		= $inspection_id;
					$resp['message']	= 'Successfully created TOR with ID: '.$inspection_id;
				}else{
					$resp['error']		= true;
					$resp['message']	= 'There is some error. Please try later.';
				}
				
				
			}else{
				$resp['error']		= true;
				$resp['message']	= 'Parent Inspection ID does not exist. Please try a different one.';
			}
		}
		
		echo json_encode($resp);
	}
}
?>

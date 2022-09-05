<?php
/**
 * Controller class containing all the Interior tab related functionalities.
 *
 * @since 1.0
 */
class InteriorController extends InteriorModel{

	/**
	 * Variable to store the optional tab categories
	 *
	 * @var array $association_mapping Array containing map for associations.
	 */
	public static $association_mapping = array(
		'home_systems' => array(
			'central_burglar_alarm_system' => array(
				'tab_id' => 'security_safety_details',
				'section_id' => 'security_alarm_system',
				'section_name' => 'Security Alarm System',
				'input' => array(
								array('key' => '1', 'label' => 'Monitored Alarm', 'type' => 'checkbox'),
								array('key' => '2', 'label' => 'Monitored Alarm, wireless', 'type' => 'checkbox'),
								array('key' => '3', 'label' => 'Local Alarm only', 'type' => 'checkbox'),
								array('key' => '4', 'label' => 'Alarm not in use, but hard wired', 'type' => 'checkbox')
							),
			),
			
			'security_system_wireless' => array(
				'tab_id' => 'security_safety_details',
				'section_id' => 'security_alarm_system',
				'section_name' => 'Security Alarm System',
				'input' => array(
								array('key' => '2', 'label' => 'Monitored Alarm, wireless', 'type' => 'checkbox'),
							),
			),

			'surveillance_system_camera' => array(
				'tab_id' => 'security_safety_details',
				'section_id' => 'surveillance_systems',
				'section_name' => 'Surveillance Systems',
				'input' => array(
					array('key' => '2', 'label' => 'Camera(s) at front', 'type' => 'checkbox'),
					array('key' => '3', 'label' => 'Cameras all corners', 'type' => 'checkbox'),
					array('key' => '4', 'label' => 'Front gate camera', 'type' => 'checkbox'),
					array('key' => '5', 'label' => 'Multiple Cameras - outside', 'type' => 'checkbox'),
					array('key' => '6', 'label' => 'Multiple Cameras - inside + outside', 'type' => 'checkbox'),
					array('key' => '7', 'label' => 'Cameras, Smarthouse w/internet view', 'type' => 'checkbox')
				),
			),

			'interior_sprinkler_system' => array(
				'tab_id' => 'security_safety_details',
				'section_id' => 'fire_sprinkler_system',
				'section_name' => 'Fire Sprinkler System',
				'input' => array(
					array('key' => '2', 'label' => 'Full Coverage - All floors/rooms', 'type' => 'checkbox'),
					array('key' => '3', 'label' => 'Partial Coverage (see Comments)', 'type' => 'checkbox'),
					array('key' => '4', 'label' => 'Furnace/Boiler room Coverage only', 'type' => 'checkbox'),
					array('key' => '5', 'label' => 'Sprinklers installed, not operational', 'type' => 'checkbox'),
					array('key' => '7', 'label' => 'Bylaw required, see Comments', 'type' => 'checkbox')
				),
				
			),

			'sump_pump_system' => array(
				'tab_id' => 'utilities_details',
				'section_id' => 'sewer_service_type',
				'section_name' => 'Sewer Service Type',
				'input' => array(
					array('key' => '5', 'label' => 'Septic Tank + Sump Pump in basement', 'type' => 'checkbox'),
					array('key' => '6', 'label' => 'Sewer Line + Sump Pump in basement', 'type' => 'checkbox'),
					
				),
			),
		),

		'fireplaces_wood_stoves' => array(
			'wood_stove_free_standing' => array(
				'tab_id' => 'utilities_details',
				'section_id' => 'solid_fuel_appliances',
				'section_name' => 'Solid Fuel Appliances',
				'input' => array(array('key' => '2', 'label' => 'Wood Stove', 'type' => 'checkbox')),
			), 
		),

		'heating_system' => array(
			'furnace_wood_outdoor' => array(
				'tab_id' => 'utilities_details',
				'section_id' => 'solid_fuel_appliances',
				'section_name' => 'Solid Fuel Appliances',
				'input' => array(array('key' => '3', 'label' => 'Wood Fired Boiler (outside)', 'type' => 'checkbox')),
			), 
		)
		
	);

	/**
     *
     * Function used to save data to the database.
     *
     * @param array $params inspection details.
	 *
     */
	static function save($params=array()){
		
		$inspection = RequestInspection::actionGetRow($params);
		$status 	= $inspection['status'];

		if(($status == 'itvawip' || $status == 'fileclosed' || $status == 'approved') && $inspection['itva_id'] == $_SESSION['current_user']['id']) {
			self::saveItvaData($params);
		} else {
			self::actionSave($params);
		}
	}

	/**
     *
     * Function used to ITVA save data to the database.
     *
     * @param array $params inspection details.
     */
	static function saveItvaData($params=array()){
		$fi_data 			= self::getData($params);
		
		if(strpos($params['key'], '_comments') !== false) {
			$params['value'] = $params['value'].'_itva';
		}else{
			// $old_value_array 	= json_decode($fi_data[$params['key']], true);
			// $new_value_array	= json_decode($params['value'], true);

			// $new_value_keys		= array_keys($new_value_array[0]);

			// foreach($new_value_keys as $key){
			// 	$old_value_array[0][$key] = $new_value_array[0][$key];
			// }

			// $params['value'] = json_encode($old_value_array);
			$new_value_array = json_decode($params['value'], true);
            $fi_array = $new_value_array[0];
            $itva_array = $new_value_array[1];

            $new_merge_array = array_merge($fi_array, $itva_array);
            

            $params['value'] = json_encode(array($new_merge_array));
		}

		

		self::actionSave($params);
	}

	/**
     *
     * Function used to get data from the database.
     *
     * @param array $params inspection details.
	 * 
	 * @return array
     */
	static function getData($params=array()){
		
		return self::actionGetData($params);
	}

	/**
     *
     * Function used to save associated data between tabs.
     *
     * @param array $params inspection details.
     */
	static function saveAssociations($params=array()){
		$data = self::actionGetData($params);
		$section_details = $data[$params['section_id']];
		$section_details_array = [];
		$checkbox = false;
		$checked_values = [];
		$present = 0;
		$user_type = (RequestInspection::isITVAWIP($params))? 'ITVA' : 'FI';

		$section_details_array[0][$user_type] = [];


		if(empty($params['input_id']) && empty($params['input_label'])){
			$checkbox = true;
			$checked_values = json_decode($params['value']);
		}

		$input_id = ($checkbox)? $params['section_id'] : $params['input_id'];
		$input_label = ($checkbox)? '' : $params['input_label'];

		if(!empty($section_details)){
			$section_details_array = json_decode($section_details, true);

			foreach($section_details_array[0][$user_type] as &$data){
				if($data['key'] == $input_id && is_array($data['value']) && $checkbox){
					$present++;
					foreach($checked_values as $checked_value){
						if(!in_array($checked_value, $data['value'])){
							array_push($data['value'], $checked_value);
						}
					}
				}else{
					if($data['key'] == $input_id){
						$present++;
						$data['value'] = $params['value'];
					}
				}
			}
		}
		if($present == 0){
			if(empty($section_details_array[0][$user_type])){
				$section_details_array[0][$user_type] = [];
			}
			$value = ($checkbox)? $checked_values : $params['value'];

			array_push($section_details_array[0][$user_type], [
				'key' =>  $input_id,
				'label' =>  $input_label,
				'value' => $value
			]);
		}

		$params['key'] = $params['section_id'];
		$params['value'] = json_encode($section_details_array);
		self::save($params);
	}

	/**
     *
     * Function used to get associated data between tabs.
     *
     * @param array $params inspection details.
     */
	static function getAssociationValues($params){
		$result_array = [];
		$section_id = $params['section_id'];
		$data = self::actionGetData($params);
		$section_values = $data[$section_id];
		$user_type = (RequestInspection::isITVAWIP($params))? 'ITVA' : 'FI';
		$section_values_array = json_decode($section_values, true)[0][$user_type];

		foreach($section_values_array as $section_value){
			if(!is_array($section_value['value'])){
				foreach($params['input'] as $map){
					if($section_value['label'] == $map['label']){
						$result_array[$section_value['key']] = $section_value['value'];
					}
				}
			}else{
				$result_array[$section_value['key']] = $section_value['value'];
			}	
		}

		return($result_array);
	}

	/**
     *
     * Function used to save duplicate data(modified if required) between tabs.
     *
     * @param array $params inspection details.
     */
	static function saveDuplicates($params=array()){
		$present = 0;

		$data = self::actionGetData($params);
		$section_id = $params['section_id'];
		$section_label = $params['section_label'];
		$section_key = $params['section_key'];

		$user_type = (RequestInspection::isITVAWIP($params))? 'ITVA' : 'FI';

		$section_details = $data[$section_key];
		$section_details_array = [];
		$section_details_array[0][$user_type] = [];
		
		if(!empty($section_details)){
			$section_details_array = json_decode($section_details, true);

			foreach($section_details_array[0][$user_type] as &$data){
				if($data['key'] == $section_id){
					$present++;
					$data['value'] = $params['value'];
				}
			}
		}

		if($present == 0){
			array_push($section_details_array[0][$user_type], [
				'key' => $section_id,
				'label' => $section_label,
				'value' => $params['value']
			]);
		}

		$params['key'] = $section_key;
		$params['value'] = json_encode($section_details_array);
		self::save($params);
	}

	
}
?>
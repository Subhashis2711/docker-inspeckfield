<?php
/**
 * Controller class containing all the Building tab related functionalities.
 *
 * @since 1.0
 */
class UtilitiesController extends UtilitiesModel{

	/**
	 * Variable to store the optional tab categories
	 *
	 * @var array $association_mapping Array containing map for associations.
	 */
	public static $association_mapping = array(
		'sewer_service_type' => array(
			'*' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'home_systems',
				'section_name' => 'Home Systems',
				'input' => array(array('id' => 'sump_pump_system', 'label' => 'Sump Pump System', 'type' => 'Count')),
			),
			
		),

		'hot_water_tank' => array(
			'electric_fired_tanks' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'bathroom_build_up',
				'section_name' => 'Bathroom Build-up',
				'input' => array(array('id' => 'hot_water_heater_extra','label' => 'HW Heater, Extra (Gas or Electric)', 'type' => 'Count')),
			),
			'gas_fired_tanks' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'bathroom_build_up',
				'section_name' => 'Bathroom Build-up',
				'input' => array(array('id' => 'hot_water_heater_extra','label' => 'HW Heater, Extra (Gas or Electric)', 'type' => 'Count')),
			),
			'oil_fired_tanks' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'bathroom_build_up',
				'section_name' => 'Bathroom Build-up',
				'input' => array(array('id' => 'hot_water_heater_extra','label' => 'HW Heater, Extra (Gas or Electric)', 'type' => 'Count')),
			),
			'hw_heater_tankless_gas_on_demand' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'bathroom_build_up',
				'section_name' => 'Bathroom Build-up',
				'input' => array(array('id' => 'hot_water_heater_extra','label' => 'HW Heater, Extra (Gas or Electric)', 'type' => 'Count')),
			),
			'storage_tank_with_bolier_mix_valve' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'bathroom_build_up',
				'section_name' => 'Bathroom Build-up',
				'input' => array(array('id' => 'hot_water_heater_tankless_gas','label' => 'HW Heater,Tankless (Gas – OnDemand)', 'type' => 'Count')),
			),
		),

		'solid_fuel_appliances' => array(
			'2' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'fireplaces_wood_stoves',
				'section_name' => 'Fireplaces & Wood Stoves',
				'input' => array(array('id' => 'wood_stove_free_standing','label' => 'Wood Stove, Free Standing', 'type' => 'Count')),
			),

			'3' => array(
				'tab_id' => 'interior_details',
				'section_id' => 'heating_system',
				'section_name' => 'HEATING SYSTEM',
				'input' => array(array('id' => 'furnace_wood_outdoor','label' => 'Furnace, Wood, Outdoor', 'type' => 'Count')),
			),
		),
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
}
?>
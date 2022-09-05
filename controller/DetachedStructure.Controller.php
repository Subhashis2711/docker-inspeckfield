<?php
/**
 * Controller class containing all the Detached structure tab related functionalities.
 *
 * @since 1.0
 */
class DetachedStructureController extends DetachedStructureModel{

	/**
	 * Variable to store the optional tab categories
	 *
	 * @var array $association_mapping Array containing map for associations.
	 */
	public static $association_mapping = array(
		'pools_and_sports' => array(
			'hot_tub_jacuzzi' => array(
				'tab_id' => 'security_safety_details',
				'section_id' => 'pools_hot_tubs',
				'section_name' => 'Pools & Hot Tubs',
				'input' => array(
								array('key' => '3', 'label' => 'Hot tub on deck, w/cover', 'type' => 'checkbox'),
								array('key' => '9', 'label' => 'Hot tub in deck w/cover', 'type' => 'checkbox'),
								array('key' => '10', 'label' => 'Hot tub in deck, no cover', 'type' => 'checkbox'),
								array('key' => '11', 'label' => 'Hot tub on deck, no cover', 'type' => 'checkbox')
							),
			)
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
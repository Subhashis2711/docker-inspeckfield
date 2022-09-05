<?php
class TorView extends TorController{
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();

	/**
	 *
	 * Function used for drawing form with fields.
	 *
	 * @param array $params array containing necessary parameters.
	 *
	 */
	static function drawForm($params=array()){
		if(!empty($params)){
			$tab_id = $params['tab_id'];
			
			$form_details = array(
								'id' => $tab_id.'_form',
								'tab_id' => $tab_id,
								'css_class' => '',
								'inspection_id' => $params['inspection_id']

							);

			if(isset($params['inspection_id']) && $params['inspection_id']){
				$inspection_data = self::getData($params);

				if(is_array($inspection_data) && count($inspection_data) > 0){
					$form_details['values']	= $inspection_data;
				}
			}
		}

		$field_sets = array();
		$field_sets[] = array(
							'id' => 'principle_use_buildings',
							'label' => 'Principle Use of Building',
							'form_items' => array(
												array('name' => 'garage_down_suite_up', 'label' => 'Garage down, Suite up'),
												array('name' => 'garage_suite_beside', 'label' => 'Garage, Suite beside'),
												array('name' => 'guesthouse', 'label' => 'Guesthouse'),
												array('name' => 'cottage', 'label' => 'Cottage'),
												array('name' => 'workshop', 'label' => 'Workshop'),
												array('name' => 'storage', 'label' => 'Storage'),
												array('name' => 'not_in_use', 'label' => 'Empty, not in use'),
												array('name' => 'principle_use_buildings_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);

		$field_sets[] = array(
							'id' => 'equipment_list_building',
							'label' => 'Equipment List in Building',
							'form_items' => array(
												array('name' => 'table_workshop_equip', 'label' => 'Table Saw and/or Workshop Equipment'),
												array('name' => 'vintage_automobile', 'label' => 'Vintage Automobile'),
												array('name' => 'exotic_automobile', 'label' => 'Exotic Automobile'),
												array('name' => 'automobile', 'label' => 'Automobile'),
												array('name' => 'sports_equip', 'label' => 'Sports Equipment'),
												array('name' => 'snowmobile', 'label' => 'Snowmobile'),
												array('name' => 'motorcycle', 'label' => 'Motorcycle'),
												array('name' => 'atv', 'label' => 'ATV'),
												array('name' => 'go_cart', 'label' => 'High Performance Go-Cart'),
												array('name' => 'motorboat', 'label' => 'Motorboat'),
												array('name' => 'sailboat', 'label' => 'Sailboat'),
												array('name' => 'boat', 'label' => 'Boat'),
												array('name' => 'boating_equip', 'label' => 'Boating Equipment'),
												array('name' => 'fish_hunt_equip', 'label' => 'Fishing or Hunting Equip.'),
												array('name' => 'engine_parts', 'label' => 'Engine parts'),
												array('name' => 'motorhome', 'label' => 'Motorhome'),
												array('name' => '5th_wheel', 'label' => '5th Wheel'),
												array('name' => 'garden_tractor', 'label' => 'Garden tractor'),
												array('name' => 'farm_tractor', 'label' => 'Farm Tractor'),
												array('name' => 'airplane', 'label' => 'Airplane'),
												array('name' => 'airplane_parts', 'label' => 'Airplane parts'),
												array('name' => 'storage_items', 'label' => 'Storage Items - Unable to determine'),
												array('name' => 'significant', 'label' => 'No signficant equipment observed'),
												array('name' => 'equipment_list_building_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		if(empty($params) || count($params) == 1) {
			foreach($field_sets as $field_set) {
				array_push(self::$fieldset_infos, array('label' => $field_set['label'], 'id' => $field_set['id']));
				self::$fieldset_ids[] .= $field_set['id'];

			}
			return;
		}
		$form_details['field_sets']		= $field_sets;
		Ui::drawFieldSetFormContainer($form_details);
	}

	/**
	 *
	 * Function used to get infomation about the fieldset.
	 *
	 * @param string $inspection_id Inspection ID.
	 * 
	 * @return array $fieldset_infos
	 *
	 */
	static function getFieldsetInfos($inspection_id) {
		$params = array();
		$params['inspection_id'] = $inspection_id;

		self::$fieldset_infos = [];
		self::drawForm($params);
		return self::$fieldset_infos;
	}

	//return fieldset ID as JSON response.
	static function getFieldsetIds() {
		self::$fieldset_ids = [];
		self::drawForm();
		echo json_encode(self::$fieldset_ids);
	}
}

class Tor extends TorView{
    function __construct(){}
}
?>
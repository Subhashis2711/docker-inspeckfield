<?php
class UtilitiesView extends UtilitiesController{
	
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();
	public static $electrical_service_type_options, $electrical_service_panel_type_options, $clearance_concerns_options, 
					$wiring_type_options, $electrical_services_conditions_options, $hvac_equipment_conditions_options, 
					$water_source_options, $water_supply_piping_options, $waste_line_piping_options, $sewer_service_type_options, 
					$plumbing_conditions_options, $hot_water_tank_options, $hot_water_tank_condition_options, 
					$clothes_washer_hoses_options, $clothes_dryer_venting_options, $solid_fuel_appliances_options, $hvac_conditions_options;
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
		self::$electrical_service_type_options = array(
												'1' => 'Overhead',
												'2' => 'Underground'
											);
		self::$electrical_service_panel_type_options = array(
													'1' => 'Automatic Circuit Breakers',
													'2' => 'Unable to locate or blocked from viewing',
													'3' => 'Fuse Block Panel',
													'4' => 'Mixed Panel Types',
													'5' => 'No protection'
												);
		self::$clearance_concerns_options = array(
											'1' => 'None observed',
											'2' => 'Minor tree/ shrub blockage - Must have photo(s)!!',
											'3' => 'Majority tree blockage  - Must have photo(s)!!',
											'4' => 'Low hanging wires -  Must have photo(s)!!'
										);
		self::$wiring_type_options = array(
									'1' => 'Insulated Copper',
									'2' => 'Mixed: Copper/Conduit',
									'3' => 'Mixed: Copper/BX Cable',
									'4' => 'Mixed: Copper/Knob Tube (minor)',
									'5' => 'Flexible conduit',
									'6' => 'Knob Tube',
									'7' => 'Knob Tube with GFI&apos;s',
									'8' => 'Plastic Conduit',
									'9' => 'BX Cable',
									'10' => 'Mixed: BX Conduit',
									'11' => 'Aluminum wire',
									'12' => 'Metal Conduit',
									'13' => 'Unable to Determine'
								);
		self::$electrical_services_conditions_options = array(
													'1' => 'Good conditions, no problems observed',
													'2' => 'Average, no problems observed',
													'3' => 'Good, upgrades evident',
													'4' => 'High quality, custom equipment',
													'5' => 'Missing Panel Cover(s)',
													'6' => 'Average, some wiring non-conforming',
													'7' => 'Fair-Poor (knob and Tube present)',
													'8' => 'Fair, but unprofessional installation',
													'9' => 'Poor: sub-standard installation',
													'10' => 'Poor: messy DIY wiring',
													'11' => 'Older equipment (> 40 yrs)',
													'12' => 'Poor: Damaged wires observed',
													'13' => 'Poor: Broken/damaged equipment',
													'14' => 'Unable to determine, Add Note'
												);
		self::$hvac_equipment_conditions_options = array(
												'1' => 'Equipment in Good condition',
												'2' => 'Equipment in Average condition',
												'3' => 'New furnace and/or boiler system',
												'4' => 'Age of Furnace',
												'5' => 'YYYY installed Boiler',
												'6' => 'Equipment is original and in Good condition',
												'7' => 'Equipment requires maintenance',
												'8' => 'Equipment is damaged/leaking at time of site visit',
												'9' => 'Equipment installation is sub-standard',
												'10' => 'New equipment',
												'11' => 'Equipment in Poor condition',
												'12' => 'Unable to confirm maintenance'
											);
		self::$water_source_options = array(
									'1' => 'Public water supply',
									'2' => 'Private well',
									'3' => 'Private shared water well(s)',
									'4' => 'Community water well(s)',
									'5' => 'Unable to confirm',
									'6' => 'Lake water pumped',
									'7' => 'Stream/River source',
									'8' => 'Private Reservoir',
									'9' => 'Tanker delivered'
								);
		self::$water_supply_piping_options = array(
											'1' => 'Copper pipe',
											'2' => 'Plastic pipe (PEX)',
											'3' => 'Mixed: Copper + Plastic (PEX)',
											'4' => 'Mixed: Poly B, PEX & Copper Piping',
											'5' => 'Mixed: Poly B + PEX',
											'6' => 'Plastic pipe (CPVC, PVC, LLDPE)',
											'7' => 'Mixed: Poly B  & Copper Piping',
											'8' => 'Unable to determine',
											'9' => 'Mixed: Copper + Galvanized Iron',
											'10' => 'Galvinized iron'
										);
		self::$waste_line_piping_options = array(
										'1' => 'ABS Plastic',
										'2' => 'Mixed: ABS Plastic + Cast Iron',
										'3' => 'Cast Iron',
										'4' => 'Copper',
										'5' => 'Mixed: ABS Plastic + Copper',
										'6' => 'Unable to determine'
									);
		self::$sewer_service_type_options = array(
										'1' => 'Public Sewers',
										'2' => 'Septic Tank Field',
										'3' => 'Collection Tank pumped to Sewer',
										'4' => 'Treatment Plant to Field',
										'5' => 'Septic Tank + Sump Pump in basement',
										'6' => 'Sewer Line + Sump Pump in basement',
										'7' => 'Unable to determine'
									);
		self::$plumbing_conditions_options = array(
										'1' => 'Good, no concerns observed',
										'2' => 'Average, no problems observed',
										'3' => 'Good, upgrades evident',
										'4' => 'Average, with non-conforming piping',
										'5' => 'Fair, but unprofessional installation',
										'6' => 'Poor, leakage and/or damaged piping'
									);
		self::$hot_water_tank_options = array(
									'1' => '1 Electric Fired Tank',
									'2' => '1 Gas Fired Tank',
									'3' => '2 Electric Fired Tanks',
									'4' => '2 Gas Fired Tanks',
									'5' => '3 Electric Fired Tanks',
									'6' => '3 Gas Fired Tanks',
									'7' => '1 Oil Fired Tank',
									'8' => '1 HW Heater, Tankless, Gas (On Demand)',
									'9' => '2 HW Heater, Tankless, Gas (On Demand)',
									'10' => 'Storage Tank with Boiler Mix Valve'
								);
		self::$hot_water_tank_condition_options = array(
												'1' => 'Good condition',
												'2' => 'Average condition',
												'3' => 'Tank not seen, or unable to be seen',
												'4' => 'Poor condition (wetness, leaking or rust)',
												'5' => 'Damaged casing'
											);
		self::$clothes_washer_hoses_options = array(
											'1' => 'Rubber Hose, Good Condition',
											'2' => 'Metal Flex, Good condition',
											'3' => 'Unable to confirm',
											'4' => 'Rubber Hose, Average condition',
											'5' => 'Metal Flex, Average condition',
											'6' => 'Damaged/Repaired Hose/Piping',
											'7' => 'Rubber Hose, Poor condition',
											'8' => 'Metal Flex, Poor condition'
										);
		self::$clothes_dryer_venting_options = array(
											'1' => 'Metal flex hose, Good condition',
											'2' => 'Metal flex hose, Average condition',
											'3' => 'Unable to confirm',
											'4' => 'Rigid metal piping, Good condition',
											'5' => 'Rigid metal piping, Average condition',
											'6' => 'Plastic vent hose, Good condition',
											'7' => 'Plastic vent hose, Average condition',
											'8' => 'Metal flex hose, Poor condition and/or damaged',
											'9' => 'Rigid metal piping, Poor condition and/or damaged',
											'10' => 'Plastic vent hose, Poor condition and/or damaged',
											'11' => 'Large lint build-up, inside',
											'12' => 'Large lint build-up, outside',
											'13' => 'No outside venting observed'
										);
		self::$solid_fuel_appliances_options = array(
											'1' => 'No Solid Fuel units',
											'2' => 'Wood Stove',
											'3' => 'Wood Fired Boiler (outside)'
										);
		self::$hvac_conditions_options = array(
											'1' => 'Good Conditions',
											'2' => 'Average Conditions',
											'3' => 'Poor Conditions, see Notes'
										);
		$field_sets[] = array(
			'id' => 'commom_services_subheading',
			'sub_label' => 'COMMON SERVICES',
			'type' => 'subheading'
		);
		$field_sets[] = array(
			'id' => 'electrical_services_subheading',
			'sub_label' => 'Electrical Services ',
			'type' => 'subheading2'
		);
		$field_sets[] = array(
							'id' => 'electrical_service_type',
							'label' => 'Electrical Service Type',
							'form_items' => array(
												array('name' => 'electrical_service_type','label' => 'Electrical Service Type','type' => 'multiselect','datasets' => self::$electrical_service_type_options),
												array('name' => 'electrical_service_type_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'electrical_service_panel_type',
							'label' => 'Electrical Service Panel(s) Type',
							'form_items' => array(
												array('name' => 'electrical_service_panel_type','label' => 'Electrical Service Panel(s) Type','type' => 'multiselect','datasets' => self::$electrical_service_panel_type_options),
												array('name' => 'electrical_service_panel_type_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		
		$field_sets[] = array(
							'id' => 'clearance_concerns',
							'label' => 'Clearance Concerns',
							'form_items' => array(
												array('name' => 'clearance_concerns','label' => 'Clearance Concerns','type' => 'multiselect','datasets' => self::$clearance_concerns_options),
												array('name' => 'clearance_concerns_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'wiring_type',
							'label' => 'Wiring Type',
							'form_items' => array(
												array('name' => 'wiring_type','label' => 'Wiring Type','type' => 'multiselect','datasets' => self::$wiring_type_options),
												array('name' => 'wiring_type_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'electrical_services_conditions',
							'label' => 'Electrical Services Conditions',
							'form_items' => array(
												array('name' => 'electrical_services_conditions','label' => 'Electrical Services Conditions','type' => 'multiselect','datasets' => self::$electrical_services_conditions_options),
												array('name' => 'electrical_services_conditions_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'hvac_services_subheading',
							'sub_label' => 'HVAC Services',
							'type' => 'subheading2'
						);
		$field_sets[] = array(
							'id' => 'hvac_equipment_age_conditions',
							'label' => 'HVAC Equipment Age + Conditions',
							
							'form_items' => array(
								array('name' => 'heating_system', 'label' => 'HVAC Systems', 'link_tab' => 'interior_details', 'type' => 'hyperlink'),
								array('name' => 'furnace_age','label' => 'Furnace Age'),
								array('name' => 'furnace_condition','label' => 'Furnace Conditions','type' => 'multiselect','datasets' => self::$hvac_conditions_options),
								array('name' => 'boiler_age','label' => 'Boiler Age'),
								array('name' => 'boiler_condition','label' => 'Boiler Conditions','type' => 'multiselect','datasets' => self::$hvac_conditions_options),
								array('name' => 'other_general_hvac_conditions','label' => 'OTHER General HVAC Conditions','type' => 'multiselect','datasets' => self::$hvac_conditions_options),
								
								array('name' => 'hvac_equipment_age_conditions_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							)
						);
		$field_sets[] = array(
							'id' => 'plumbing_subheading',
							'sub_label' => 'PLUMBING',
							'type' => 'subheading2'
						);
		$field_sets[] = array(
							'id' => 'water_source',
							'label' => 'Water Source',
							'form_items' => array(
												array('name' => 'water_source','label' => 'Water Source','type' => 'multiselect','datasets' => self::$water_source_options),
												array('name' => 'water_source_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'water_supply_piping',
							'label' => 'Water Supply Piping',
							'form_items' => array(
												array('name' => 'water_supply_piping','label' => 'Water Supply Piping','type' => 'multiselect','datasets' => self::$water_supply_piping_options),
												array('name' => 'water_supply_piping_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'waste_line_piping',
							'label' => 'Waste Line Piping',
							'form_items' => array(
												array('name' => 'waste_line_piping','label' => 'Waste Line Piping','type' => 'multiselect','datasets' => self::$waste_line_piping_options),
												array('name' => 'waste_line_piping_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'sewer_service_type',
							'label' => 'Sewer Service Type',
							'form_items' => array(
												array('name' => 'sewer_service_type','label' => 'Sewer Service Type','type' => 'multiselect','datasets' => self::$sewer_service_type_options),
												array('name' => 'sewer_service_type_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'associations' => array('5', '6'),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'plumbing_conditions',
							'label' => 'Plumbing Conditions',
							'form_items' => array(
												array('name' => 'plumbing_conditions','label' => 'Plumbing Conditions','type' => 'multiselect','datasets' => self::$plumbing_conditions_options),
												array('name' => 'plumbing_conditions_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'hot_water_tank',
							'label' => 'Hot Water Tank',
							'form_items' => array(
								array('name' => 'electric_fired_tanks','label' => 'Electric Fired Tanks','multi_field' => array('count')),
								array('name' => 'gas_fired_tanks','label' => 'Gas Fired Tanks','multi_field' => array('count')),
								array('name' => 'oil_fired_tanks','label' => 'Oil Fired Tanks','multi_field' => array('count')),
								array('name' => 'hw_heater_tankless_gas_on_demand','label' => 'HW Heater, Tankless, Gas (On Demand)','multi_field' => array('count')),
								array('name' => 'storage_tank_with_bolier_mix_valve','label' => 'Storage Tank with Boiler Mix Valve','multi_field' => array('count')),
							),
							'associations' => array('electric_fired_tanks', 'gas_fired_tanks', 'oil_fired_tanks', 'oil_fired_tanks',
													'hw_heater_tankless_gas_on_demand', 'storage_tank_with_bolier_mix_valve'),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'hot_water_tank_age',
							'label' => 'Hot Water Tank Age (Year)',
							'form_items' => array(
												array('name' => 'hot_water_tank_age','label' => 'Enter Hot Water Tank Age (Year)'),
												array('name' => 'hot_water_tank_age_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'hot_water_tank_condition',
							'label' => 'Hot Water Tank Condition',
							'form_items' => array(
												array('name' => 'hot_water_tank_condition','label' => 'Hot Water Tank Condition','type' => 'multiselect','datasets' => self::$hot_water_tank_condition_options),
												array('name' => 'hot_water_tank_condition_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'clothes_washer_hoses',
							'label' => 'Clothes Washer Hoses',
							'form_items' => array(
												array('name' => 'clothes_washer_hoses','label' => 'Clothes Washer Hoses','type' => 'multiselect','datasets' => self::$clothes_washer_hoses_options),
												array('name' => 'clothes_washer_hoses_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'clothes_dryer_venting',
							'label' => 'Clothes Dryer Venting',
							'form_items' => array(
												array('name' => 'clothes_dryer_venting','label' => 'Clothes Dryer Venting','type' => 'multiselect','datasets' => self::$clothes_dryer_venting_options),
												array('name' => 'clothes_dryer_venting_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
						);
		$field_sets[] = array(
							'id' => 'solid_fuel_appliances',
							'label' => 'Solid Fuel Appliances',
							'form_items' => array(
												array('name' => 'fireplaces_wood_stoves', 'label' => 'Fireplaces & Wood Stoves', 'link_tab' => 'interior_details', 'type' => 'hyperlink'),
												array('name' => 'solid_fuel_appliances','label' => 'Solid Fuel Appliances','type' => 'multiselect','datasets' => self::$solid_fuel_appliances_options),
												array('name' => 'solid_fuel_appliances_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'associations' => array('2', '3')
						);
		$field_sets[] = array(
							'id' => 'commom_services_utility_comments_subheading',
							'sub_label' => 'COMMON SERVICES & UTILITY COMMENTS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
							'id' => 'common_services_tility_public_comments',
							'label' => 'Common Services & Utility: Public Comments',
							'form_items' => array(
												array('name' => 'common_services_tility_public_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		if(empty($params) || count($params) == 1) {
			foreach($field_sets as $field_set) {
					if(!isset($field_set['type'])){
					$form_items = [];
					foreach($field_set['form_items'] as $form_item){
						if(isset($form_item['type']) && $form_item['type'] == "multiselect"){
							$datasets = $form_item['datasets'];
							foreach($datasets as $key=>$value){
								array_push($form_items, array(
									'id' => $form_item['name'].'_'.$key,
									'label' => $value
								));
							}
						}else{
							array_push($form_items, array(
								'id' => $form_item['name'],
								'label' => $form_item['label']
							));
						}
					}
					array_push(self::$fieldset_infos, array('label' => $field_set['label'], 'id' => $field_set['id'], 'form_items' => $form_items));
					self::$fieldset_ids[] .= $field_set['id'];
				}
				
			}

			return;
		}

		$form_details['field_sets'] = $field_sets;
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

	//return form-items from a field set
	static function getFieldsetFormItems($params){
		$fieldset_info_array = self::getFieldsetInfos($params['inspection_id']);
		foreach($fieldset_info_array as $info){
			if($info['id'] == $params['category_id']){
				return($info['form_items']);
			}
		}
		
	}

	//return fieldset ID as JSON response.
	static function getFieldsetIds() {
		self::$fieldset_ids = [];
		self::drawForm();
		echo json_encode(self::$fieldset_ids);
	}

	static function getSelectFields($label) {
		self::drawForm();
		return self::$$label;
	}
}

class Utilities extends UtilitiesView{
    function __construct(){}
}
?>
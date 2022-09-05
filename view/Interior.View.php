<?php
class InteriorView extends InteriorController{
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();
	public static $cwfc_options;

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

		self::$cwfc_options = array(
							'1' => 'Good Maint. w/high value finishes',
							'2' => 'Good Maint. w/Std finishes',
							'3' => 'Excellent Maint. w/high value finishes',
							'4' => 'Excellent Maint. w/Std finishes',
							'5' => 'Average Maint. w/high value finishes',
							'6' => 'Average Maint. w/Std finishes',
							'7' => 'Recent Updating',
							'8' => 'Renovations in Progress',
							'9' => 'Damaged surfaces and/or materials',
							'10' => 'Poor conditions'
						);
		$field_sets = array();
		$field_sets[] = array(
			'id' => 'interior_baseline_subheading',
			'sub_label' => 'INTERIOR BASELINE',
			'type' => 'subheading'
		);
		$field_sets[] = array(
				        'id' => 'kitchen',
				        'label' => 'Kitchen',
				        'form_items' => array(
				                array('name' => 'semi_custom','label' => 'Semi-Custom','multi_field' => array('count')),
								array('name' => 'builders_grade','label' => 'Builders Grade','multi_field' => array('count')),
								array('name' => 'custom','label' => 'Custom','multi_field' => array('count')),
								array('name' => 'designer','label' => 'Designer','multi_field' => array('count')),
								array('name' => 'luxury','label' => 'Luxury','multi_field' => array('count')),
								array('name' => 'basic','label' => 'Basic','multi_field' => array('count')),
								array('name' => 'commercial','label' => 'Commercial','multi_field' => array('count')),
				                array('name' => 'kitchen_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'full_bathroom',
				        'label' => 'Full Bathroom',
				        'form_items' => array(
				                array('name' => 'full_bath_semi_custom','label' => 'Full Bath, Semi-Custom','multi_field' => array('count')),
								array('name' => 'full_bath_custom','label' => 'Full Bath, Custom','multi_field' => array('count')),
								array('name' => 'full_bath_designer','label' => 'Full Bath, Designer','multi_field' => array('count')),
								array('name' => 'full_bath_luxury','label' => 'Full Bath, Luxury','multi_field' => array('count')),
								array('name' => 'full_bath_builders_grade','label' => 'Full Bath, Builders Grade','multi_field' => array('count')),
								array('name' => 'full_bath_basic','label' => 'Full Bath, Basic','multi_field' => array('count')),
								array('name' => 'full_bath_commercial','label' => 'Full Bath, Commercial','multi_field' => array('count')),
				                array('name' => 'full_bathroom_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'half_bathroom',
				        'label' => 'Half Bath',
				        'form_items' => array(
								array('name' => 'half_bath_semi_custom','label' => '1/2 Bath, Semi-Custom','multi_field' => array('count')),
								array('name' => 'half_bath_custom','label' => '1/2 Bath, Custom','multi_field' => array('count')),
								array('name' => 'half_bath_designer','label' => '1/2 Bath, Designer','multi_field' => array('count')),
								array('name' => 'half_bath_luxury','label' => '1/2 Bath, Luxury','multi_field' => array('count')),
								array('name' => 'half_bath_builders_grade','label' => '1/2 Bath, Builders Grade','multi_field' => array('count')),
								array('name' => 'half_bath_basic','label' => '1/2 Bath, Basic','multi_field' => array('count')),
								array('name' => 'half_bath_commercial','label' => '1/2 Bath, Commercial','multi_field' => array('count')),
				                array('name' => 'half_bathroom_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'three_quarter_bath',
				        'label' => 'Three-Quarter Bath',
				        'form_items' => array(
								array('name' => 'three_quarter_bath_semi_custom','label' => '3/4 Bath, Semi-Custom','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_custom','label' => '3/4 Bath, Custom','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_designer','label' => '3/4 Bath, Designer','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_luxury','label' => '3/4 Bath, Luxury','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_builders_grade','label' => '3/4 Bath, Builders Grade','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_basic','label' => '3/4 Bath, Basic','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_commercial','label' => '3/4 Bath, Commercial','multi_field' => array('count')),
								array('name' => 'three_quarter_bath_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
			'id' => 'hvac_systems_subheading',
			'sub_label' => 'HVAC Systems',
			'type' => 'subheading2'
		);
		$field_sets[] = array(
				        'id' => 'heating_system',
				        'label' => 'HEATING SYSTEM',
				        'form_items' => array(
								array('name' => 'heating_gas_forced_air_furnace','label' => 'Heating, Gas Forced Air (Furnace)','multi_field' => array('percentage')),
								array('name' => 'heating_forced_air_multi_zoned','label' => 'Heating, Forced Air, Multi-Zoned','multi_field' => array('percentage')),
								array('name' => 'additional_furnace','label' => 'Additional Furnace','multi_field' => array('count')),
								array('name' => 'heating_gas_hot_water_boiler','label' => 'Heating, Gas Hot Water (Boiler)','multi_field' => array('percentage')),
								array('name' => 'heating_system_radiant_flr_other_hybrid','label' => 'Heating System, Radiant Flr (Other/Hybrid)','multi_field' => array('percentage')),
								array('name' => 'heating_system_radiant_flr_gas','label' => 'Heating System, Radiant Flr (Gas)','multi_field' => array('percentage')),
								array('name' => 'heating_system_radiant_flr_electric','label' => 'Heating System, Radiant Flr (Electric)','multi_field' => array('percentage')),
								array('name' => 'air_exchanger_unit_hrv','label' => 'Air Exchanger Unit (HRV)','multi_field' => array('count')),
								array('name' => 'heat_exchanger','label' => 'Heat Exchanger','multi_field' => array('count')),
								array('name' => 'heating_electric_baseboard','label' => 'Heating, Electric (Baseboard)','multi_field' => array('percentage')),
								array('name' => 'heating_electric_boiler','label' => 'Heating, Electric (Boiler)','multi_field' => array('percentage')),
								array('name' => 'heating_oil_forced_air_furnace','label' => 'Heating, Oil Forced Air (Furnace)','multi_field' => array('percentage')),
								array('name' => 'hydronic_heat_boiler_multi_zoned','label' => 'Hydronic Heat (Boiler), Multi-Zoned','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),
								array('name' => 'additional_furnace_high_efficiency','label' => 'Additional Furnace, High Efficiency','multi_field' => array('count')),
								array('name' => 'heating_propane_gas_forced_air','label' => 'Heating, Propane Gas Forced Air','multi_field' => array('percentage')),
								array('name' => 'heating_propane_gas_hot_water','label' => 'Heating, Propane Gas Hot Water','multi_field' => array('percentage')),
								array('name' => 'furnace_wood_outdoor','label' => 'Furnace, Wood, Outdoor','multi_field' => array('count')),
								array('name' => 'geothermal_system_closed_loop','label' => 'Geothermal System, Closed Loop','multi_field' => array('percentage')),
								array('name' => 'solar_panels','label' => 'Solar Panels','multi_field' => array('count')),
								array('name' => 'heating_electric','label' => 'Heating, Electric','multi_field' => array('percentage')),
								array('name' => 'heating_gas','label' => 'Heating, Gas','multi_field' => array('percentage')),
								array('name' => 'heating_oil_boiler_hot_water','label' => 'Heating, Oil (Boiler) Hot Water','multi_field' => array('percentage')),
								array('name' => 'heating_system_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						),
						'associations' => array('furnace_wood_outdoor'),
						'priority' => true
		);
		$field_sets[] = array(
				        'id' => 'cooling_system',
				        'label' => 'COOLING SYSTEM',
				        'form_items' => array(
								array('name' => 'heat_pump_regular','label' => 'Heat Pump (Regular)','multi_field' => array('percentage')),
								array('name' => 'heat_pump_high_efficiency_he','label' => 'Heat Pump (High Efficiency/HE)','multi_field' => array('percentage')),
								array('name' => 'heat_pump_mini_split_system','label' => 'Heat Pump, Mini-Split System','multi_field' => array('percentage')),
								array('name' => 'additional_heat_pump','label' => 'Additional Heat Pump','multi_field' => array('count')),
								array('name' => 'central_ac_same_ducts','label' => 'Central AC, Same Ducts','multi_field' => array('percentage')),
								array('name' => 'central_ac_separate_ducts','label' => 'Central AC, Separate Ducts','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'central_air_conditioning_he_same_ducts','label' => 'Central Air Conditioning, HE, Same Ducts','multi_field' => array('percentage')),
								array('name' => 'central_air_conditioning_he_separate_ducts','label' => 'Central Air Conditioning, HE, Separate Ducts','multi_field' => array('percentage')),
								array('name' => 'central_air_conditioning_multi_zoned','label' => 'Central Air Conditioning, Multi-Zoned','multi_field' => array('percentage')),
								array('name' => 'additional_central_air_conditioner_unit','label' => 'Additional Central Air Conditioner Unit','multi_field' => array('count')),
								array('name' => 'additional_high_efficiency_central_ac_unit','label' => 'Additional High Efficiency Central A/C Unit','multi_field' => array('count')),
								array('name' => 'cooling_system_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						),
						'priority' => true

		);
		$field_sets[] = array(
				        'id' => 'hvac_misc_equipment',
				        'label' => 'HVAC Misc Equipment',
				        'form_items' => array(
								array('name' => 'air_cleaner_electric','label' => 'Air Cleaner, Electric','multi_field' => array('count')),
								array('name' => 'whole_house_fan','label' => 'Whole House Fan','multi_field' => array('count')),
								array('name' => 'thru_wall_ac_units','label' => 'Thru-Wall AC Units','multi_field' => array('percentage')),
								array('name' => 'humidifier_furnace','label' => 'Humidifier, Furnace','multi_field' => array('count')),
								array('name' => 'patio_heater_fix_pedestal','label' => 'Patio Heater, Fix Pedestal','multi_field' => array('count')),
								array('name' => 'patio_heater_wall_or_ceiling','label' => 'Patio Heater, Wall or Ceiling','multi_field' => array('count')),
								array('name' => 'unit_gas_heater','label' => 'Unit Gas Heater','multi_field' => array('percentage')),
								array('name' => 'hvac_misc_equipment_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
			'id' => 'interior_extras_subheading',
			'sub_label' => 'INTERIOR Extras',
			'type' => 'subheading'
		);
		$field_sets[] = array(
				        'id' => 'ceiling_extras',
				        'label' => 'Ceiling Extras',
				        'form_items' => array(
								array('name' => 'ceiling_fan_custom','label' => 'Ceiling Fan, Custom','multi_field' => array('count')),
								array('name' => 'ceiling_fan_average','label' => 'Ceiling Fan, Average','multi_field' => array('count')),
								array('name' => 'recessed_lighting','label' => 'Recessed Lighting','multi_field' => array('percentage')),
								array('name' => 'beams_common','label' => 'Beams, Common','multi_field' => array('percentage')),
								array('name' => 'beams_wood_decorative','label' => 'Beams, Wood, Decorative','multi_field' => array('percentage')),
								array('name' => 'track_lighting_lf','label' => 'Track Lighting, LF','multi_field' => array('lf')),
								array('name' => 'medallions','label' => 'Medallions','multi_field' => array('count')),
								array('name' => 'chandelier, custom (common)','label' => 'Chandelier, Custom (above $30K/ea.)','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'recessed_lighting','label' => 'Recessed Lighting','multi_field' => array('count')),
								array('name' => 'winch_chandelier','label' => 'Winch, Chandelier','multi_field' => array('count')),
								array('name' => 'chandelier, designer (fancy)','label' => 'Chandelier, Designer (above $60K/ea.)','multi_field' => array('count')),
								array('name' => 'beams_wood_decorative, lf','label' => 'Beams, Wood, Decorative, LF','multi_field' => array('lf')),
								array('name' => 'wallpaper_custom_border','label' => 'Wallpaper, Custom Border','multi_field' => array('percentage')),
								array('name' => 'ceiling_extras_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						),
						'priority' => true

		);
		$field_sets[] = array(
				        'id' => 'fireplaces_wood_stoves',
				        'label' => 'Fireplaces & Wood Stoves',
				        'form_items' => array(
								array('name' => 'solid_fuel_appliances', 'label' => 'Solid Fuel Appliances', 'link_tab' => 'utilities_details', 'type' => 'hyperlink'),
								array('name' => 'fireplace_gas_chimney_flue','label' => 'Fireplace, Gas (Chimney Flue)','multi_field' => array('count')),
								array('name' => 'fireplace_direct_direct_vent_gas','label' => 'Fireplace, Direct (Direct Vent, Gas)','multi_field' => array('count')),
								array('name' => 'fireplace_single','label' => 'Fireplace, Single','multi_field' => array('count')),
								array('name' => 'fireplace_double','label' => 'Fireplace, Double','multi_field' => array('count')),
								array('name' => 'fireplace_electric','label' => 'Fireplace, Electric','multi_field' => array('count')),
								array('name' => 'wood_stove_free_standing','label' => 'Wood Stove, Free Standing','multi_field' => array('count')),
								array('name' => 'chimney_inside','label' => 'Chimney, Inside','multi_field' => array('count')),
								array('name' => 'chimney_multiple_opening_inside','label' => 'Chimney, Multiple Opening, Inside','multi_field' => array('count')),
								array('name' => 'chimney_multiple_opening_outside','label' => 'Chimney, Multiple Opening, Outside','multi_field' => array('count')),
								array('name' => 'pellet_stove_wood','label' => 'Pellet Stove, Wood','multi_field' => array('count')),
								array('name' => 'mantel_hardwood_ea','label' => 'Mantel, Hardwood, EA','multi_field' => array('count')),
								array('name' => 'kiva','label' => 'Kiva','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'chimney_outside_custom','label' => 'Chimney, Outside, Custom','multi_field' => array('count')),
								array('name' => 'mantel_hardwood_lf','label' => 'Mantel, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'fireplace_freestanding','label' => 'Fireplace, Freestanding','multi_field' => array('count')),
								array('name' => 'fireplace_large_over_8','label' => 'Fireplace, Large, Over 8&apos;t','multi_field' => array('count')),
								array('name' => 'fireplace_multiple_opening','label' => 'Fireplace, Multiple Opening','multi_field' => array('count')),
								array('name' => 'fireplace_small_under_8','label' => 'Fireplace, Small, Under 8&apos;t','multi_field' => array('count')),
								array('name' => 'fireplace_triple','label' => 'Fireplace, Triple','multi_field' => array('count')),
								array('name' => 'fireplace_zero_clearance_pre_fab','label' => 'Fireplace, Zero Clearance, Pre-Fab','multi_field' => array('count')),
								array('name' => 'mantel_carved_granite_sqft','label' => 'Mantel, Carved Granite, sq.ft','multi_field' => array('sqft')),
								array('name' => 'mantel_carved_granite_ea','label' => 'Mantel, Carved Granite, EA','multi_field' => array('count')),
								array('name' => 'mantel_carved_granite_lf','label' => 'Mantel, Carved Granite, LF','multi_field' => array('lf')),
								array('name' => 'mantel_carved_marble_sqft','label' => 'Mantel, Carved Marble, sq.ft','multi_field' => array('sqft')),
								array('name' => 'mantel_carved_marble_ea','label' => 'Mantel, Carved Marble, EA','multi_field' => array('count')),
								array('name' => 'mantel_carved_marble_lf','label' => 'Mantel, Carved Marble, LF','multi_field' => array('lf')),
								array('name' => 'mantel_carved_onyx_sqft','label' => 'Mantel, Carved Onyx, sq.ft','multi_field' => array('sqft')),
								array('name' => 'mantel_carved_onyx_ea','label' => 'Mantel, Carved Onyx, EA','multi_field' => array('count')),
								array('name' => 'mantel_carved_onyx_lf','label' => 'Mantel, Carved Onyx, LF','multi_field' => array('lf')),
								array('name' => 'mantel_precast_plaster_sqft','label' => 'Mantel, Cast Stone, sq.ft','multi_field' => array('sqft')),
								array('name' => 'mantel_cast_stone_ea','label' => 'Mantel, Cast Stone, EA','multi_field' => array('count')),
								array('name' => 'mantel_cast_stone_lf','label' => 'Mantel, Cast Stone, LF','multi_field' => array('lf')),
								array('name' => 'mantel_precast_plaster_sqft','label' => 'Mantel, Precast Plaster, sq.ft','multi_field' => array('sqft')),
								array('name' => 'mantel_precast_plaster_ea','label' => 'Mantel, Precast Plaster, EA','multi_field' => array('count')),
								array('name' => 'mantel_precast_plaster_lf','label' => 'Mantel, Precast Plaster, LF','multi_field' => array('lf')),
								array('name' => 'masonry_heater_soapstone','label' => 'Masonry Heater, Soapstone','multi_field' => array('count')),
								array('name' => 'masonry_heater_wood_burning','label' => 'Masonry Heater, Wood-Burning','multi_field' => array('count')),
								array('name' => 'fireplaces_wood_stoves_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1),
				        ),
						'associations' => array('wood_stove_free_standing')
		);
		$field_sets[] = array(
				        'id' => 'home_systems',
				        'label' => 'Home Systems',
				        'form_items' => array(
								array('name' => 'central_burglar_alarm_system','label' => 'Central Burglar Alarm System','multi_field' => array('percentage')),
								array('name' => 'central_vacuum_system','label' => 'Central Vacuum system ','multi_field' => array('percentage')),
								array('name' => 'central_stereo_system','label' => 'Central Stereo System','multi_field' => array('percentage')),
								array('name' => 'intercom_system','label' => 'Intercom System','multi_field' => array('percentage')),
								array('name' => 'surveillance_system_camera','label' => 'Surveillance System, Camera','multi_field' => array('percentage')),
								array('name' => 'home_automation_system_smart_wired','label' => 'Home Automation System (Smart Wired)','multi_field' => array('percentage')),
								array('name' => 'automation_custom_full_tech_control','label' => 'Automation, Custom (Full Tech Control)','multi_field' => array('percentage')),
								array('name' => 'generator_emergency_backup_small','label' => 'Generator, Emergency Backup, Small','multi_field' => array('count')),
								array('name' => 'generator_emergency_backup_medium','label' => 'Generator, Emergency Backup, Medium','multi_field' => array('count')),
								array('name' => 'generator_emergency_backup_large','label' => 'Generator, Emergency Backup, Large','multi_field' => array('count')),
								array('name' => 'generator_emergency_backup_extra_large','label' => 'Generator, Emergency Backup, Extra Large','multi_field' => array('count')),
								array('name' => 'interior_sprinkler_system','label' => 'Interior Sprinkler System','multi_field' => array('percentage')),
								array('name' => 'sump_pump_system','label' => 'Sump Pump System','multi_field' => array('count')),
								array('name' => 'lift_chair','label' => 'Lift, Chair','multi_field' => array('count')),
								array('name' => 'elevator','label' => 'Elevator','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'central_fire_alarm_system','label' => 'Central Fire Alarm System','multi_field' => array('percentage')),
								array('name' => 'dumbwaiter','label' => 'Dumbwaiter','multi_field' => array('count')),
								array('name' => 'home_automation_system_custom','label' => 'Home Automation System, Custom','multi_field' => array('percentage')),
								array('name' => 'lift_stair_incline_elevator','label' => 'Lift, Stair, Incline Elevator','multi_field' => array('count')),
								array('name' => 'lift_wheelchair','label' => 'Lift, Wheelchair','multi_field' => array('count')),
								array('name' => 'radon_mitigation_system_air','label' => 'Radon, Mitigation System, Air','multi_field' => array('count')),
								array('name' => 'radon_mitigation_system_water','label' => 'Radon, Mitigation System, Water','multi_field' => array('count')),
								array('name' => 'security_system_wireless','label' => 'Security System, Wireless','multi_field' => array('count')),
								array('name' => 'home_systems_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						),
						'associations' => array('central_burglar_alarm_system', 'surveillance_system_camera', 'interior_sprinkler_system', 'sump_pump_system', 'security_system_wireless'),
						'priority' => true


		);
		$field_sets[] = array(
				        'id' => 'wet_bars',
				        'label' => 'Wet Bars',
				        'form_items' => array(
								array('name' => 'wet_bar','label' => 'Wet Bar','multi_field' => array('count')),
								array('name' => 'wet_bar_custom','label' => 'Wet Bar, Custom','multi_field' => array('count')),
								array('name' => 'wet_bar_deluxe','label' => 'Wet Bar, Deluxe','multi_field' => array('count')),
								array('name' => 'wet_bar_luxury','label' => 'Wet Bar, Luxury','multi_field' => array('count')),
								array('name' => 'wet_bar_pub_style','label' => 'Wet Bar, Pub Style','multi_field' => array('count')),
								array('name' => 'wet_bar_pub_style_deluxe','label' => 'Wet Bar, Pub Style, Deluxe','multi_field' => array('count')),
								array('name' => 'wet_bars_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'interior_wall_material',
				        'label' => 'Interior Wall Material',
				        'form_items' => array(
								array('name' => 'drywall','label' => 'Drywall','multi_field' => array('percentage')),
								array('name' => 'drywall_textured','label' => 'Drywall, Textured','multi_field' => array('percentage')),
								array('name' => 'plaster','label' => 'Plaster','multi_field' => array('percentage')),
								array('name' => 'studs_only','label' => 'Studs Only','multi_field' => array('percentage')),
								array('name' => 'plywood_only','label' => 'Plywood Only','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'glass_block','label' => 'Glass Block','multi_field' => array('percentage')),
								array('name' => 'masonry_plastered','label' => 'Masonry, Plastered','multi_field' => array('percentage')),
								array('name' => 'plaster_horsehair','label' => 'Plaster, Horsehair','multi_field' => array('percentage')),
								array('name' => 'plaster_textured','label' => 'Plaster, Textured','multi_field' => array('percentage')),
								array('name' => 'stucco_on_frame','label' => 'Stucco on Frame','multi_field' => array('percentage')),
								array('name' => 'textured_drywall_partitions_ornate','label' => 'Textured Drywall Partitions, Ornate','multi_field' => array('percentage')),
								array('name' => 'interior_wall_material_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'interior_doors',
				        'label' => 'Doors',
				        'form_items' => array(
								array('name' => 'door_hollow_core_birch','label' => 'Door, Hollow Core, Birch','multi_field' => array('count')),
								array('name' => 'door_french','label' => 'Door, French','multi_field' => array('count')),
								array('name' => 'door_raised_panel_birch','label' => 'Door, Raised Panel, Birch','multi_field' => array('count')),
								array('name' => 'door_solid_core,_birch','label' => 'Door, Solid Core, Birch','multi_field' => array('count')),
								array('name' => 'door_glass_panel','label' => 'Door, Glass Panel','multi_field' => array('count')),
								array('name' => 'door_pocket_hollow_core, birch','label' => 'Door, Pocket, Hollow Core, Birch','multi_field' => array('count')),
								array('name' => 'door_solid_core_lauan','label' => 'Door, Solid Core Lauan','multi_field' => array('count')),
								array('name' => 'door_hollow_core_lauan','label' => 'Door, Hollow Core Lauan','multi_field' => array('count')),
								array('name' => 'door_panel_recessed','label' => 'Door, Panel, Recessed','multi_field' => array('count')),
								array('name' => 'door_bi-fold_hollow_core_softwd_dbl','label' => 'Door, Bi-Fold, Hollow Core, Softwd, Dbl','multi_field' => array('count')),
								array('name' => 'door_board_and_batten','label' => 'Door, Board & Batten','multi_field' => array('count')),
								array('name' => 'shutter_interior','label' => 'Shutter, Interior','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'door_bi_fold_solid_core_hrdwd_dbl','label' => 'Door, Bi-Fold, Solid core, Hrdwd, Dbl','multi_field' => array('count')),
								array('name' => 'door_carved_mahogany','label' => 'Door, Carved Mahogany','multi_field' => array('count')),
								array('name' => 'door_colonial_multi_panel','label' => 'Door, Colonial, Multi-Panel','multi_field' => array('count')),
								array('name' => 'door_double_swing','label' => 'Door, Double Swing','multi_field' => array('count')),
								array('name' => 'door_dutch','label' => 'Door, Dutch','multi_field' => array('count')),
								array('name' => 'door_fiberglass_interior','label' => 'Door, Fiberglass, Interior','multi_field' => array('count')),
								array('name' => 'door_hollow_core_oak','label' => 'Door, Hollow Core, Oak','multi_field' => array('count')),
								array('name' => 'door_hollow_core_walnut','label' => 'Door, Hollow Core, Walnut','multi_field' => array('count')),
								array('name' => 'door_louvered','label' => 'Door, Louvered','multi_field' => array('count')),
								array('name' => 'door_masonite','label' => 'Door, Masonite','multi_field' => array('count')),
								array('name' => 'door_mexican','label' => 'Door, Mexican','multi_field' => array('count')),
								array('name' => 'door_mirrored','label' => 'Door, Mirrored','multi_field' => array('count')),
								array('name' => 'door_panel_leaded_glass','label' => 'Door, Panel, Leaded Glass','multi_field' => array('count')),
								array('name' => 'door_pocket_solid_core_birch','label' => 'Door, Pocket, Solid Core, Birch','multi_field' => array('count')),
								array('name' => 'door_pocket_solid_core_lauan','label' => 'Door, Pocket, Solid Core, Lauan','multi_field' => array('count')),
								array('name' => 'door_raised_panel_oak','label' => 'Door, Raised Panel, Oak','multi_field' => array('count')),
								array('name' => 'door_raised_panel_pine','label' => 'Door, Raised Panel, Pine','multi_field' => array('count')),
								array('name' => 'door_raised_panel_walnut','label' => 'Door, Raised Panel, Walnut','multi_field' => array('count')),
								array('name' => 'door_solid_core_mahogany','label' => 'Door, Solid Core, Mahogany','multi_field' => array('count')),
								array('name' => 'door_solid_core_oak','label' => 'Door, Solid Core, Oak','multi_field' => array('count')),
								array('name' => 'door_solid_core_walnut','label' => 'Door, Solid Core, Walnut','multi_field' => array('count')),
								array('name' => 'french_doors_count_each_door','label' => 'French Doors (Count Each Door)','multi_field' => array('count')),
								array('name' => 'doors_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
			'id' => 'interior_specialities_subheading',
			'sub_label' => 'INTERIOR: Specialities',
			'type' => 'subheading'
		);
		$field_sets[] = array(
				        'id' => 'deluxe_interior_specialties',
				        'label' => 'Deluxe Interior Specialties',
				        'form_items' => array(
								array('name' => 'steam_shower_complete','label' => 'Steam Shower, Complete','multi_field' => array('count')),
								array('name' => 'entertainment_system_audio_video','label' => 'Entertainment System, Audio/Video','multi_field' => array('count')),
								array('name' => 'arch_wood','label' => 'Arch, Wood','multi_field' => array('count')),
								array('name' => 'arch_stone','label' => 'Arch, Stone','multi_field' => array('count')),
								array('name' => 'arch_plaster','label' => 'Arch, Plaster','multi_field' => array('count')),
								array('name' => 'wine_vault','label' => 'Wine Vault','multi_field' => array('count')),
								array('name' => 'sauna','label' => 'Sauna','multi_field' => array('count')),
								array('name' => 'hot_tub','label' => 'Hot Tub','multi_field' => array('count')),
								array('name' => 'home_theater_room_custom','label' => 'Home Theater Room ,Custom (Above $250K)','multi_field' => array('count')),
								array('name' => 'indoor_pool','label' => 'Indoor Pool','multi_field' => array('count')),
								array('name' => 'wall_or_floor_safe','label' => 'Wall or Floor Safe','multi_field' => array('count')),
								array('name' => 'linen_chute','label' => 'Linen Chute','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'aquarium_built_in','label' => 'Aquarium, Built-in','multi_field' => array('count')),
								array('name' => 'basketball_court_indoor','label' => 'Basketball Court, Indoor','multi_field' => array('count')),
								array('name' => 'bowling_alley_complete','label' => 'Bowling Alley, Complete','multi_field' => array('count')),
								array('name' => 'bridge_stone_lf','label' => 'Bridge, Stone, LF','multi_field' => array('lf')),
								array('name' => 'bridge_wood_lf','label' => 'Bridge, Wood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_closet_island_hdwd_w_granite_sqft','label' => 'Cabinet, Closet, Island, Hdwd w/Granite, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinet_closet_island_hdwd_w_marble_sqft','label' => 'Cabinet, Closet, Island, Hdwd w/Marble, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinet_closet_island_hdwd_w_tile_top_sqft','label' => 'Cabinet, Closet, Island, Hdwd w/Tile Top, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinet_closet_island_hdwd_w_wd_top_sqft','label' => 'Cabinet, Closet, Island, Hdwd w/Wd Top, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinet_closet_wall_hdwd_lf','label' => 'Cabinet, Closet, Wall, Hdwd, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_dry_cleaning','label' => 'Cabinet, Dry Cleaning','multi_field' => array('count')),
								array('name' => 'closet_carousels','label' => 'Closet, Carousels','multi_field' => array('count')),
								array('name' => 'closet_cedar_sqft','label' => 'Closet, Cedar, sq.ft','multi_field' => array('sqft')),
								array('name' => 'closet_shelving_per_closet','label' => 'Closet, Shelving Per Closet','multi_field' => array('count')),
								array('name' => 'clothes_dryer_built_in','label' => 'Clothes Dryer, Built-in','multi_field' => array('count')),
								array('name' => 'clothes_washer_built_in','label' => 'Clothes washer, Built-in','multi_field' => array('count')),
								array('name' => 'cooler_walk_in_sqft','label' => 'Cooler, Walk-in, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fountain_brick_sqft','label' => 'Fountain, Brick, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fountain_marble_sqft','label' => 'Fountain, Marble, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fountain_ornate_stone_sqft','label' => 'Fountain, Ornate Stone, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fountain_wall_marble_sqft','label' => 'Fountain, Wall, Marble, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fountain_wall_ornate_stone_sqft','label' => 'Fountain, Wall, Ornate Stone, sq.ft','multi_field' => array('sqft')),
								array('name' => 'home_entertainment_system','label' => 'Home Entertainment System','multi_field' => array('count')),
								array('name' => 'home_theater_room_pre_fab','label' => 'Home Theater Room, Pre-Fab','multi_field' => array('count')),
								array('name' => 'indoor_lap_pool','label' => 'Indoor Lap Pool','multi_field' => array('count')),
								array('name' => 'indoor_pool, sq.ft','label' => 'Indoor Pool, sq.ft','multi_field' => array('sqft')),
								array('name' => 'ironing_center','label' => 'Ironing Center','multi_field' => array('count')),
								array('name' => 'pond_brick_sqft','label' => 'Pond, Brick, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pond_marble_sqft','label' => 'Pond, Marble, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pond_stone_sqft','label' => 'Pond, Stone, sq.ft','multi_field' => array('sqft')),
								array('name' => 'racquetball_court_viewing_wall_sqft','label' => 'Racquetball Court Viewing Wall, sq.ft','multi_field' => array('sqft')),
								array('name' => 'racquetball_court_complete','label' => 'Racquetball Court, Complete','multi_field' => array('count')),
								array('name' => 'rink_ice_skating_complete_sqft','label' => 'Rink, Ice Skating, Complete, sq.ft','multi_field' => array('sqft')),
								array('name' => 'room_panic_safe','label' => 'Room, Panic/Safe','multi_field' => array('count')),
								array('name' => 'room_vault','label' => 'Room, Vault','multi_field' => array('count')),
								array('name' => 'shooting_range_indoor_complete','label' => 'Shooting Range, Indoor, Complete','multi_field' => array('count')),
								array('name' => 'simulator, golf course, complete','label' => 'Simulator, Golf Course, Complete','multi_field' => array('count')),
								array('name' => 'soda_fountain_lf','label' => 'Soda Fountain, LF','multi_field' => array('lf')),
								array('name' => 'stream_lf','label' => 'Stream, LF','multi_field' => array('lf')),
								array('name' => 'tennis_court_indoor','label' => 'Tennis Court, Indoor','multi_field' => array('count')),
								array('name' => 'waterfall_sqft','label' => 'Waterfall, sq.ft','multi_field' => array('sqft')),
								array('name' => 'deluxe_interior_specialties_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'moldings',
				        'label' => 'Moldings',
				        'form_items' => array(
								array('name' => 'molding_base_6','label' => 'Molding, Base, 6&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_8','label' => 'Molding, Base, 8&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_3','label' => 'Molding, Base, 3&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_4','label' => 'Molding, Base, 4&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_2','label' => 'Molding, Crown, 2&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_3','label' => 'Molding, Crown, 3&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_4','label' => 'Molding, Crown, 4&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_6','label' => 'Molding, Crown, 6&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_8','label' => 'Molding, Crown, 8&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_10','label' => 'Molding, Crown, 10&quot;','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'cornice_interior_lf','label' => 'Cornice, Interior, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_6_lf','label' => 'Molding, Base, 6&quot;, LF','multi_field' => array('lf')),
								array('name' => 'chair_rail_hdwd_lf','label' => 'Chair Rail, Hdwd, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_10_lf','label' => 'Molding, Base, 10&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_10','label' => 'Molding, Base, 10&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_12_lf','label' => 'Molding, Base, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_12','label' => 'Molding, Base, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_2','label' => 'Molding, Base, 2&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_3','label' => 'Molding, Base, 3&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_3_lf','label' => 'Molding, Base, 3&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_4_lf','label' => 'Molding, Base, 4&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_8_lf','label' => 'Molding, Base, 8&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_brick_12_lf','label' => 'Molding, Base, Brick, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_brick_12','label' => 'Molding, Base, Brick, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_brick_ornate_12','label' => 'Molding, Base, Brick, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_brick_ornate_12_lf','label' => 'Molding, Base, Brick, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_10_lf','label' => 'Molding, Base, Oak, 10&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_10','label' => 'Molding, Base, Oak, 10&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_oak_12_lf','label' => 'Molding, Base, Oak, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_12','label' => 'Molding, Base, Oak, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_oak_4','label' => 'Molding, Base, Oak, 4&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_oak_4_lf','label' => 'Molding, Base, Oak, 4&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_6','label' => 'Molding, Base, Oak, 6&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_oak_6_lf','label' => 'Molding, Base, Oak, 6&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_8_lf','label' => 'Molding, Base, Oak, 8&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_8','label' => 'Molding, Base, Oak, 8&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_oak_ornate_12_lf','label' => 'Molding, Base, Oak, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_oak_ornate_12','label' => 'Molding, Base, Oak, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_plastic_12','label' => 'Molding, Base, Plastic, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_plastic_12_lf','label' => 'Molding, Base, Plastic, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_shoe_lf','label' => 'Molding, Base, Shoe, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_shoe','label' => 'Molding, Base, Shoe','multi_field' => array('percentage')),
								array('name' => 'molding_base_stone_12','label' => 'Molding, Base, Stone, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_stone_12_lf','label' => 'Molding, Base, Stone, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_base_stone_ornate_12','label' => 'Molding, Base, Stone, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_base_stone_ornate_12_lf','label' => 'Molding, Base, Stone, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_10_lf','label' => 'Molding, Crown, 10&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_2_lf','label' => 'Molding, Crown, 2&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_3_lf','label' => 'Molding, Crown, 3&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_4_lf','label' => 'Molding, Crown, 4&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_6_lf','label' => 'Molding, Crown, 6&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_8_lf','label' => 'Molding, Crown, 8&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_brick_12_lf','label' => 'Molding, Crown, Brick, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_brick_12','label' => 'Molding, Crown, Brick, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_brick_ornate_12_lf','label' => 'Molding, Crown, Brick, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_brick_ornate_12','label' => 'Molding, Crown, Brick, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_oak_10_lf','label' => 'Molding, Crown, Oak, 10&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_oak_10','label' => 'Molding, Crown, Oak, 10&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_oak_12_lf','label' => 'Molding, Crown, Oak, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_oak_12','label' => 'Molding, Crown, Oak, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_oak_6_lf','label' => 'Molding, Crown, Oak, 6&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_oak_6','label' => 'Molding, Crown, Oak, 6&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_oak_8_lf','label' => 'Molding, Crown, Oak, 8&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_oak_8','label' => 'Molding, Crown, Oak, 8&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_oak_ornate_12_lf','label' => 'Molding, Crown, Oak, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_oak_ornate_12','label' => 'Molding, Crown, Oak, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_10_lf','label' => 'Molding, Crown, Plaster, 10&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_10','label' => 'Molding, Crown, Plaster, 10&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_12_lf','label' => 'Molding, Crown, Plaster, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_12','label' => 'Molding, Crown, Plaster, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_2_lf','label' => 'Molding, Crown, Plaster, 2&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_2','label' => 'Molding, Crown, Plaster, 2&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_4_lf','label' => 'Molding, Crown, Plaster, 4&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_4','label' => 'Molding, Crown, Plaster, 4&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_6_lf','label' => 'Molding, Crown, Plaster, 6&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_6','label' => 'Molding, Crown, Plaster, 6&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_8_lf','label' => 'Molding, Crown, Plaster, 8&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_8','label' => 'Molding, Crown, Plaster, 8&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plaster_ornate_12_lf','label' => 'Molding, Crown, Plaster, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plaster_ornate_12','label' => 'Molding, Crown, Plaster, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_plastic_12_lf','label' => 'Molding, Crown, Plastic, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_plastic_12','label' => 'Molding, Crown, Plastic, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_stone_12_lf','label' => 'Molding, Crown, Stone, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_stone_12','label' => 'Molding, Crown, Stone, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'molding_crown_stone_ornate_12_lf','label' => 'Molding, Crown, Stone, Ornate, 12&quot;, LF','multi_field' => array('lf')),
								array('name' => 'molding_crown_stone_ornate_12','label' => 'Molding, Crown, Stone, Ornate, 12&quot;','multi_field' => array('percentage')),
								array('name' => 'moldings_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'appliance_build_up',
				        'label' => 'Appliance Build-up',
				        'form_items' => array(
								array('name' => 'filtration_system_water_softener','label' => 'Filtration System, Water Softener','multi_field' => array('count')),
								array('name' => 'filter, water','label' => 'Filter, Water','multi_field' => array('count')),
								array('name' => 'refrigerator_sub-zero','label' => 'Refrigerator, Sub-Zero','multi_field' => array('count')),
								array('name' => 'wine_captain_freestanding','label' => 'Wine Captain, Freestanding','multi_field' => array('count')),
								array('name' => 'wine_captain_undercounter','label' => 'Wine Captain, Undercounter','multi_field' => array('count')),
								array('name' => 'range_hood_stainless_steel','label' => 'Range Hood, Stainless Steel','multi_field' => array('count')),
								array('name' => 'exhaust_fan_countertop','label' => 'Exhaust Fan, Countertop','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'barbeque_indoor_bbq','label' => 'Barbeque, Indoor (BBQ)','multi_field' => array('count')),
								array('name' => 'broiler','label' => 'Broiler','multi_field' => array('count')),
								array('name' => 'cook_top','label' => 'Cook Top','multi_field' => array('count')),
								array('name' => 'cook_top_induction','label' => 'Cook Top, Induction','multi_field' => array('count')),
								array('name' => 'cooler_beverage_bottle_large','label' => 'Cooler, Beverage/Bottle, Large','multi_field' => array('count')),
								array('name' => 'cooler_beverage_bottle_large','label' => 'Cooler, Beverage/Bottle, Large','multi_field' => array('count')),
								array('name' => 'cooler_beverage_bottle_medium','label' => 'Cooler, Beverage/Bottle, Medium','multi_field' => array('count')),
								array('name' => 'cooler_beverage_bottle_small','label' => 'Cooler, Beverage/Bottle, Small','multi_field' => array('count')),
								array('name' => 'cooler_walk_in','label' => 'Cooler, Walk-In','multi_field' => array('count')),
								array('name' => 'dishwasher','label' => 'Dishwasher','multi_field' => array('count')),
								array('name' => 'dishwasher_commercial','label' => 'Dishwasher, Commercial','multi_field' => array('count')),
								array('name' => 'dishwasher_deluxe','label' => 'Dishwasher, Deluxe','multi_field' => array('count')),
								array('name' => 'dishwasher_drawer_style','label' => 'Dishwasher, Drawer Style','multi_field' => array('count')),
								array('name' => 'exhaust_fan','label' => 'Exhaust Fan','multi_field' => array('count')),
								array('name' => 'food_processing_center','label' => 'Food Processing Center','multi_field' => array('count')),
								array('name' => 'freezer','label' => 'Freezer','multi_field' => array('count')),
								array('name' => 'freezer_drawer_style','label' => 'Freezer, Drawer Style','multi_field' => array('count')),
								array('name' => 'fryer_deep_fat','label' => 'Fryer, Deep Fat','multi_field' => array('count')),
								array('name' => 'garbage_disposal','label' => 'Garbage Disposal','multi_field' => array('count')),
								array('name' => 'garbage_disposal_heavy_duty','label' => 'Garbage Disposal, Heavy Duty','multi_field' => array('count')),
								array('name' => 'griddle','label' => 'Griddle','multi_field' => array('count')),
								array('name' => 'heat_lamp','label' => 'Heat Lamp','multi_field' => array('count')),
								array('name' => 'hot_beverage_system_built_in','label' => 'Hot Beverage System, Built-IN','multi_field' => array('count')),
								array('name' => 'ice_machine','label' => 'Ice Machine','multi_field' => array('count')),
								array('name' => 'ice_machin_heavy_duty','label' => 'Ice Machine, Heavy Duty','multi_field' => array('count')),
								array('name' => 'instant_cold_water_tap','label' => 'Instant Cold Water Tap','multi_field' => array('count')),
								array('name' => 'instant_hot_water_tap','label' => 'Instant Hot Water Tap','multi_field' => array('count')),
								array('name' => 'kegerator_built_in_double','label' => 'Kegerator, Built-In, Double','multi_field' => array('count')),
								array('name' => 'kegerator_built_in_single','label' => 'Kegerator, Built-In, Single','multi_field' => array('count')),
								array('name' => 'kegerator_built_in_triple','label' => 'Kegerator, Built-In, Triple','multi_field' => array('count')),
								array('name' => 'kegerator_double','label' => 'Kegerator, Double','multi_field' => array('count')),
								array('name' => 'kegerator_single','label' => 'Kegerator, Single','multi_field' => array('count')),
								array('name' => 'kegerator_triple','label' => 'Kegerator, Triple','multi_field' => array('count')),
								array('name' => 'microwave_or_noted_as_microwv_below','label' => 'Microwave (or noted as MicroWv below)','multi_field' => array('count')),
								array('name' => 'microwave_built_in_deluxe','label' => 'Microwave, Built-In, Deluxe','multi_field' => array('count')),
								array('name' => 'microwave_built_in_standard','label' => 'Microwave, Built-In, Standard','multi_field' => array('count')),
								array('name' => 'microwv_ovr_rnge_w_exhst_fan_delux','label' => 'MicroWv, Ovr Rnge W/Exhst Fan, Delux','multi_field' => array('count')),
								array('name' => 'microwv_ovr_rnge_w_exhst_fan_std','label' => 'MicroWv, Ovr Rnge W/Exhst Fan, Std','multi_field' => array('count')),
								array('name' => 'oven','label' => 'Oven','multi_field' => array('count')),
								array('name' => 'oven_convection','label' => 'Oven, Convection','multi_field' => array('count')),
								array('name' => 'oven_custom_refrigerated','label' => 'Oven, Custom, Refrigerated','multi_field' => array('count')),
								array('name' => 'oven_pizza_wood_burning','label' => 'Oven, Pizza, Wood-Burning','multi_field' => array('count')),
								array('name' => 'oven_wall','label' => 'Oven, Wall','multi_field' => array('count')),
								array('name' => 'oven_wall_custom_double','label' => 'Oven, Wall, Custom Double','multi_field' => array('count')),
								array('name' => 'oven_warming','label' => 'Oven, warming','multi_field' => array('count')),
								array('name' => 'pantry_motorized','label' => 'Pantry, Motorized','multi_field' => array('count')),
								array('name' => 'rack_cookware','label' => 'Rack, Cookware','multi_field' => array('count')),
								array('name' => 'range','label' => 'Range','multi_field' => array('count')),
								array('name' => 'range_oven','label' => 'Range & Oven','multi_field' => array('count')),
								array('name' => 'range_oven_commercial','label' => 'Range & Oven, Commercial','multi_field' => array('count')),
								array('name' => 'range_oven_double_wide','label' => 'Range & Oven, Double Wide','multi_field' => array('count')),
								array('name' => 'range_oven_with_microwave','label' => 'Range & Oven, With Microwave','multi_field' => array('count')),
								array('name' => 'range_fire_equipment','label' => 'Range Fire Equipment','multi_field' => array('count')),
								array('name' => 'range_hood','label' => 'Range Hood','multi_field' => array('count')),
								array('name' => 'range_hood_copper','label' => 'Range Hood, Copper','multi_field' => array('count')),
								array('name' => 'range_hood_residential','label' => 'Range Hood, Residential','multi_field' => array('count')),
								array('name' => 'range_commercial','label' => 'Range, Commercial','multi_field' => array('count')),
								array('name' => 'range_commercial_type','label' => 'Range, Commercial Type','multi_field' => array('count')),
								array('name' => 'range_commercial_upto_42','label' => 'Range, Commercial up to 42&quot;','multi_field' => array('count')),
								array('name' => 'range_commercial_upto_60','label' => 'Range, Commercial up to 60&quot;','multi_field' => array('count')),
								array('name' => 'range_commercial_upto_72','label' => 'Range, Commercial up to 72&quot;','multi_field' => array('count')),
								array('name' => 'refrigerator','label' => 'Refrigerator','multi_field' => array('count')),
								array('name' => 'refrigerator_built_in','label' => 'Refrigerator, Built-In','multi_field' => array('count')),
								array('name' => 'refrigerator_drawer_style','label' => 'Refrigerator, Drawer Style','multi_field' => array('count')),
								array('name' => 'refrigerator_under_counter_basic','label' => 'Refrigerator, Under Counter, Basic','multi_field' => array('count')),
								array('name' => 'refrigerator_under_counter_deluxe','label' => 'Refrigerator, Under Counter, Deluxe','multi_field' => array('count')),
								array('name' => 'refrigerator_under_counter_luxury','label' => 'Refrigerator, Under Counter, Luxury','multi_field' => array('count')),
								array('name' => 'soup_kettle_built_in','label' => 'Soup Kettle, Built-In','multi_field' => array('count')),
								array('name' => 'steamer','label' => 'Steamer','multi_field' => array('count')),
								array('name' => 'trash_compactor','label' => 'Trash Compactor','multi_field' => array('count')),
								array('name' => 'wok_deluxe','label' => 'Wok, Deluxe','multi_field' => array('count')),
								array('name' => 'appliance_build_up_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'kitchen_build_up',
				        'label' => 'Kitchen Build-Up',
				        'form_items' => array(
								array('name' => 'tub_laundry','label' => 'Tub, Laundry ','multi_field' => array('count')),
								array('name' => 'plumbing_rough_in_extra','label' => 'Plumbing Rough-In, Extra','multi_field' => array('count')),
								array('name' => 'sink_kitchen_custom_double','label' => 'Sink, Kitchen, Custom Double','multi_field' => array('count')),
								array('name' => 'sink_kitchen','label' => 'Sink, Kitchen','multi_field' => array('count')),

								array('name' => 'lighting_under_cabinet','label' => 'Lighting, Under Cabinet','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_custom','label' => 'Cabinet, Wall, Custom','multi_field' => array('lf')),
								array('name' => 'cabinet_base_w_counters_custom_lf ','label' => 'Cabinet, Base w/Counters, Custom, LF ','multi_field' => array('lf')),
								array('type' => 'divider/separator'),

								array('name' => 'cabinet_base_custom_painted_lf','label' => 'Cabinet, Base, Custom Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_with_counters_economy_lf','label' => 'Cabinet, Base with Counters, Economy, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_with_counters_standard_lf ','label' => 'Cabinet, Base with Counters, Standard, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_antique_finish_lf','label' => 'Cabinet, Base, Antique Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_baked_resin_lf','label' => 'Cabinet, Base, Baked Resin, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_cherry_lf','label' => 'Cabinet, Base, Cherry, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_commercial_lf','label' => 'Cabinet, Base, Commercial, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_distressed_finish_lf ','label' => 'Cabinet, Base, Distressed Finish, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_exotic_wood_lf ','label' => 'Cabinet, Base, Exotic Wood, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_hardwood_lf','label' => 'Cabinet, Base, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_hickory_lf ','label' => 'Cabinet, Base, Hickory, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_maple_lf','label' => 'Cabinet, Base, Maple, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_oak_lf','label' => 'Cabinet, Base, Oak, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_painted_lf ','label' => 'Cabinet, Base, Painted, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_rustic_finish_lf','label' => 'Cabinet, Base, Rustic Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_softwood_lf','label' => 'Cabinet, Base, Softwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_base_stainless_steel_lf ','label' => 'Cabinet, Base, Stainless Steel, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_veneer_finish_lf ','label' => 'Cabinet, Base, Veneer Finish, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_base_walnut_lf','label' => 'Cabinet, Base, Walnut, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_antique_finish_lf ','label' => 'Cabinet, Full Height, Antique Finish, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_baked_resin_lf ','label' => 'Cabinet, Full Height, Baked Resin, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_cherry_lf ','label' => 'Cabinet, Full Height, Cherry, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_commercia_lf ','label' => 'Cabinet, Full Height, Commercia, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_custom_painted_lf ','label' => 'Cabinet, Full Height, Custom Painted, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_distressed_finish_lf ','label' => 'Cabinet, Full Height, Distressed Finish, LF ','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_exotic_wood_lf','label' => 'Cabinet, Full Height, Exotic Wood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_hardwood_lf','label' => 'Cabinet, Full Height, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_hickory_lf','label' => 'Cabinet, Full Height, Hickory, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_maple_lf','label' => 'Cabinet, Full Height, Maple, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_oak_lf','label' => 'Cabinet, Full Height, Oak, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_painted_lf','label' => 'Cabinet, Full Height, Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_rustic_finish_lf','label' => 'Cabinet, Full Height, Rustic Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_softwood_lf','label' => 'Cabinet, Full Height, Softwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_stainless_steel_lf','label' => 'Cabinet, Full Height, Stainless Steel, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_veneer_finish_lf','label' => 'Cabinet, Full Height, Veneer Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_full_height_walnut_lf','label' => 'Cabinet, Full Height, Walnut, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_antique_finish_lf','label' => 'Cabinet, Island, Antique Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_baked_resi_lf','label' => 'Cabinet, Island, Baked Resi, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_cherry_lf','label' => 'Cabinet, Island, Cherry, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_commercial_lf','label' => 'Cabinet, Island, Commercial, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_custom_painted_lf','label' => 'Cabinet, Island, Custom Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_distressed_finish_lf','label' => 'Cabinet, Island, Distressed Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_exotic_wood_lf','label' => 'Cabinet, Island, Exotic Wood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_hardwood_lf','label' => 'Cabinet, Island, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_hickory_lf','label' => 'Cabinet, Island, Hickory, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_maple_lf','label' => 'Cabinet, Island, Maple, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_oak_lf','label' => 'Cabinet, Island, Oak, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_painted_lf','label' => 'Cabinet, Island, Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_rustic_finish_lf','label' => 'Cabinet, Island, Rustic Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_softwood_lf','label' => 'Cabinet, Island, Softwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_stainless_steel_lf','label' => 'Cabinet, Island, Stainless Steel, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_veneer_finish_lf','label' => 'Cabinet, Island, Veneer Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_island_walnut_lf','label' => 'Cabinet, Island, Walnut, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_antique_finish_lf','label' => 'Cabinet, Wall, Antique Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_baked_resin_lf','label' => 'Cabinet, Wall, Baked Resin, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_cherry_lf','label' => 'Cabinet, Wall, Cherry, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_commercial_lf','label' => 'Cabinet, Wall, Commercial, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_custom_painted_lf','label' => 'Cabinet, Wall, Custom Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_distressed_finish_lf','label' => 'Cabinet, Wall, Distressed Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_economy_lf','label' => 'Cabinet, Wall Economy, LF','multi_field' => array('count', 'lf')),
								array('name' => 'cabinet_wall_exotic_wood_lf','label' => 'Cabinet, Wall, Exotic Wood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_hardwood_lf','label' => 'Cabinet, Wall, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_hickory_lf','label' => 'Cabinet, Wall, Hickory, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_maple_lf','label' => 'Cabinet, Wall, Maple, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_oak_lf','label' => 'Cabinet, Wall, Oak, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_painted_lf','label' => 'Cabinet, Wall, Painted, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_rustic_finish_lf','label' => 'Cabinet, Wall, Rustic Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_softwood_lf','label' => 'Cabinet, Wall, Softwood, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_stainless_steel_lf','label' => 'Cabinet, Wall, Stainless Steel, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_standard_lf','label' => 'Cabinet, Wall, Standard, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_veneer_finish_lf','label' => 'Cabinet, Wall, Veneer Finish, LF','multi_field' => array('lf')),
								array('name' => 'cabinet_wall_walnut_lf','label' => 'Cabinet, Wall, Walnut, LF','multi_field' => array('lf')),
								array('name' => 'countertop_acid_etched_concrete_lf','label' => 'Countertop, Acid Etched Concrete, LF','multi_field' => array('lf')),
								array('name' => 'countertop_butcher_block_lf','label' => 'Countertop, Butcher Block, LF','multi_field' => array('lf')),
								array('name' => 'countertop_concrete_lf','label' => 'Countertop, Concrete, LF','multi_field' => array('lf')),
								array('name' => 'countertop_cultured_marble_with_sink_lf','label' => 'Countertop, Cultured Marble With Sink, LF','multi_field' => array('lf')),
								array('name' => 'countertop_custom_painted_tile_lf','label' => 'Countertop, Custom Painted Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_formica_lf','label' => 'Countertop, Formica, LF','multi_field' => array('lf')),
								array('name' => 'countertop_glass_tile_lf','label' => 'Countertop, Glass Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_granite_lf','label' => 'Countertop, Granite, LF','multi_field' => array('lf')),
								array('name' => 'countertop_limestone_lf','label' => 'Countertop, Limestone, LF','multi_field' => array('lf')),
								array('name' => 'countertop_marble_lf','label' => 'Countertop, Marble, LF','multi_field' => array('lf')),
								array('name' => 'countertop_onyx_lf','label' => 'Countertop, Onyx, LF','multi_field' => array('lf')),
								array('name' => 'countertop_quartz_lf','label' => 'Countertop, Quartz, LF','multi_field' => array('lf')),
								array('name' => 'countertop_soapstone_lf','label' => 'Countertop, Soapstone, LF','multi_field' => array('lf')),
								array('name' => 'countertop_solid_surface_lf','label' => 'Countertop, Solid Surface, LF','multi_field' => array('lf')),
								array('name' => 'countertop_stainless_steel_lf','label' => 'Countertop, Stainless Steel, LF','multi_field' => array('lf')),
								array('name' => 'countertop_tile_lf','label' => 'Countertop, Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_wood_lf','label' => 'Countertop, Wood, LF','multi_field' => array('lf')),
								array('name' => 'sink_kitchen_custom_double_extra_deep','label' => 'Sink Kitchen, Custom Double, Extra Deep','multi_field' => array('count')),
								array('name' => 'sink_kitchen_custom_single','label' => 'Sink, Kitchen, Custom Single','multi_field' => array('count')),
								array('name' => 'sink_kitchen_custom_triple','label' => 'Sink, Kitchen, Custom Triple','multi_field' => array('count')),
								array('name' => 'sink_kitchen_deluxe','label' => 'Sink, Kitchen, Deluxe','multi_field' => array('count')),
								array('name' => 'sink_kitchen_stainless_steel_commercial_double','label' => 'Sink, Kitchen, Stainless Steel, Commercial Double','multi_field' => array('count')),
								array('name' => 'sink_kitchen_stainless_steel_commercial_single','label' => 'Sink, Kitchen, Stainless Steel, Commercial Single','multi_field' => array('count')),
								array('name' => 'sink_kitchen_stainless_steel_double','label' => 'Sink, Kitchen, Stainless Steel, Double','multi_field' => array('count')),
								array('name' => 'sink_kitchen_stainless_steel_single','label' => 'Sink, Kitchen, Stainless Steel, Single','multi_field' => array('count')),
								array('name' => 'kitchen_build_up _comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'bathroom_build_up',
				        'label' => 'Bathroom Build-up',
				        'form_items' => array(
								array('name' => 'jacuzzi','label' => 'Jacuzzi','multi_field' => array('count')),
								array('name' => 'hot_water_heater_tankless_gas','label' => 'HW Heater,Tankless (Gas – OnDemand)','multi_field' => array('count')),
								array('name' => 'hot_water_heater_extra','label' => 'HW Heater, Extra (Gas or Electric)','multi_field' => array('count')),
								array('name' => 'environmental_enclosure','label' => 'Environmental Enclosure','multi_field' => array('count')),
								array('name' => 'bathtub_deluxe','label' => 'Bathtub, Deluxe','multi_field' => array('count')),
								array('name' => 'towel_rack_heated','label' => 'Towel Rack, Heated','multi_field' => array('count')),
								array('name' => 'vanity_wd_custom_painted_lf','label' => 'Vanity, Wd, Custom Painted, LF','multi_field' => array('lf')),
								array('name' => 'vanity_with_countertop_custom','label' => 'Vanity with Countertop, Custom','multi_field' => array('count')),

								array('name' => 'toilet_flush','label' => 'Toilet, Flush','multi_field' => array('count')),

								array('type' => 'divider/separator'),

								array('name' => 'bathtub_acrylic','label' => 'Bathtub, Acrylic','multi_field' => array('count')),
								array('name' => 'bathtub_enameled_cast_iron','label' => 'Bathtub, Enameled Cast Iron','multi_field' => array('count')),
								array('name' => 'bathtub_standard','label' => 'Bathtub, Standard','multi_field' => array('count')),
								array('name' => 'bathtub_whirlpool','label' => 'Bathtub, Whirlpool','multi_field' => array('count')),
								array('name' => 'bidet_deluxe','label' => 'Bidet, Deluxe','multi_field' => array('count')),
								array('name' => 'bidet_standard','label' => 'Bidet, Standard','multi_field' => array('count')),
								array('name' => 'cabinet_medicine_basic','label' => 'Cabinet, Medicine, Basic','multi_field' => array('count')),
								array('name' => 'cabinet_medicine_deluxe','label' => 'Cabinet, Medicine, Deluxe','multi_field' => array('count')),
								array('name' => 'cabinet_medicine_luxury','label' => 'Cabinet, Medicine, Luxury','multi_field' => array('count')),
								array('name' => 'cabinet_medicine_semi_custom','label' => 'Cabinet, Medicine, Semi-Custom','multi_field' => array('count')),
								array('name' => 'cabinet_medicine_standard','label' => 'Cabinet, Medicine, Standard','multi_field' => array('count')),
								array('name' => 'countertop_acid_etched_concrete_lf','label' => 'Countertop, Acid Etched Concrete, LF','multi_field' => array('lf')),
								array('name' => 'countertop_butcher_block_lf','label' => 'Countertop, Butcher Block, LF','multi_field' => array('lf')),
								array('name' => 'countertop_concrete_lf','label' => 'Countertop, Concrete, LF','multi_field' => array('lf')),
								array('name' => 'countertop_cultured_marble_with_sink_lf','label' => 'Countertop, Cultured Marble with Sink, LF','multi_field' => array('lf')),
								array('name' => 'countertop_custom_painted_tile_lf','label' => 'Countertop, Custom Painted Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_formica_lf','label' => 'Countertop, Formica, LF','multi_field' => array('lf')),
								array('name' => 'countertop_glass_tile_lf','label' => 'Countertop, Glass Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_granite_lf','label' => 'Countertop, Granite, LF','multi_field' => array('lf')),
								array('name' => 'countertop_limestone_lf','label' => 'Countertop, Limestone, LF','multi_field' => array('lf')),
								array('name' => 'countertop_marble_lf','label' => 'Countertop, Marble, LF','multi_field' => array('lf')),
								array('name' => 'countertop_onyx_lf','label' => 'Countertop, Onyx, LF','multi_field' => array('lf')),
								array('name' => 'countertop_quartz_lf','label' => 'Countertop, Quartz, LF','multi_field' => array('lf')),
								array('name' => 'countertop_soapstone_lf','label' => 'Countertop, Soapstone, LF','multi_field' => array('lf')),
								array('name' => 'countertop_solid_surface_lf','label' => 'Countertop, Solid Surface, LF','multi_field' => array('lf')),
								array('name' => 'countertop_stainless_steel_lf','label' => 'Countertop, Stainless Steel, LF','multi_field' => array('lf')),
								array('name' => 'countertop_tile_lf','label' => 'Countertop, Tile, LF','multi_field' => array('lf')),
								array('name' => 'countertop_wood_lf','label' => 'Countertop, Wood, LF','multi_field' => array('lf')),
								array('name' => 'environmental_enclosure_deluxe','label' => 'Environmental Enclosure, Deluxe','multi_field' => array('count')),
								array('name' => 'heater_bath','label' => 'Heater, Bath','multi_field' => array('count')),
								array('name' => 'hot_tub','label' => 'Hot Tub','multi_field' => array('count')),
								array('name' => 'hot_water_heater_tankless_electric','label' => 'Hot Water Heater, Tankless, Electric','multi_field' => array('count')),
								array('name' => 'sauna','label' => 'Sauna (use only if in bathroom)','multi_field' => array('count')),
								array('name' => 'shower_enclosure_deluxe','label' => 'Shower Enclosure, Deluxe','multi_field' => array('count')),
								array('name' => 'shower_enclosure_standard','label' => 'Shower Enclosure, Standard','multi_field' => array('count')),
								array('name' => 'shower_stall','label' => 'Shower Stall','multi_field' => array('count')),
								array('name' => 'shower_stall_deluxe','label' => 'Shower Stall, Deluxe','multi_field' => array('count')),
								array('name' => 'sink_bathroom_designer','label' => 'Sink, Bathroom, Designer','multi_field' => array('count')),
								array('name' => 'sink_bathroom_gold_plated','label' => 'Sink, Bathroom, Gold Plated','multi_field' => array('count')),
								array('name' => 'sink_bathroom_granite','label' => 'Sink, Bathroom, Granite','multi_field' => array('count')),
								array('name' => 'sink_bathroom_marble','label' => 'Sink, Bathroom, Marble','multi_field' => array('count')),
								array('name' => 'sink_bathroom_pedestal_designer','label' => 'Sink, Bathroom, Pedestal, Designer','multi_field' => array('count')),
								array('name' => 'sink_bathroom_pedestal_gold_plated','label' => 'Sink, Bathroom, Pedestal, Gold Plated','multi_field' => array('count')),
								array('name' => 'sink_bathroom_pedestal_standard','label' => 'Sink, Bathroom, Pedestal, Standard','multi_field' => array('count')),
								array('name' => 'sink_bathroom_polished_metal','label' => 'Sink, Bathroom, Polished Metal','multi_field' => array('count')),
								array('name' => 'sink_bathroom_porcelain_standard','label' => 'Sink, Bathroom, Porcelain, Standard','multi_field' => array('count')),
								array('name' => 'sink_bathroom_solid_surface','label' => 'Sink, Bathroom, Solid Surface','multi_field' => array('count')),
								array('name' => 'sink_bathroom_tempered_glass','label' => 'Sink, Bathroom, Tempered Glass','multi_field' => array('count')),
								array('name' => 'tub_free_standing_antique','label' => 'Tub, Free Standing, Antique','multi_field' => array('count')),
								array('name' => 'tub_free_standing_jetted','label' => 'Tub, Free Standing, Jetted','multi_field' => array('count')),
								array('name' => 'tub_handicapped','label' => 'Tub, Handicapped','multi_field' => array('count')),
								array('name' => 'tub_sunken_granite','label' => 'Tub, Sunken, Granite','multi_field' => array('count')),
								array('name' => 'tub_sunken_marble','label' => 'Tub, Sunken, Marble','multi_field' => array('count')),
								array('name' => 'vanity_custom_installed','label' => 'Vanity (Custom Installed)','multi_field' => array('count')),
								array('name' => 'vanity_with_countertop_deluxe','label' => 'Vanity with Countertop, Deluxe','multi_field' => array('count')),
								array('name' => 'vanity_with_countertop_designer','label' => 'Vanity with Countertop, Designer','multi_field' => array('count')),
								array('name' => 'vanity_with_countertop_standard','label' => 'Vanity with Countertop, Standard','multi_field' => array('count')),
								array('name' => 'vanity_baked_resins','label' => 'Vanity, Baked Resins','multi_field' => array('count')),
								array('name' => 'vanity_hardwood','label' => 'Vanity, Hardwood','multi_field' => array('count')),
								array('name' => 'vanity_painted_lf','label' => 'Vanity, Painted, LF','multi_field' => array('lf')),
								array('name' => 'vanity_pedestal_carved_granite_lf','label' => 'Vanity Pedestal, Carved Granite, LF','multi_field' => array('lf')),
								array('name' => 'vanity_pedestal_designer_lf','label' => 'Vanity, Pedestal, Designer, LF','multi_field' => array('lf')),
								array('name' => 'vanity_pedestal_gold_plated','label' => 'Vanity, Pedestal, Gold Plated','multi_field' => array('count')),
								array('name' => 'vanity_pedestal_marble','label' => 'Vanity, Pedestal, Marble','multi_field' => array('count')),
								array('name' => 'vanity_pedestal_tempered_glass','label' => 'Vanity, Pedestal, Tempered Glass','multi_field' => array('count')),
								array('name' => 'vanity_softwood','label' => 'Vanity, Softwood','multi_field' => array('count')),
								array('name' => 'vanity_stainless_steel','label' => 'Vanity, Stainless Steel','multi_field' => array('count')),
								array('name' => 'vanity_veneer_finish, LF','label' => 'Vanity, Veneer Finish, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_alder_lf','label' => 'Vanity, Wood, Alder, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_antique_painted','label' => 'Vanity, Wood, Antique Painted, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_beech_lf','label' => 'Vanity, Wood, Beech, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_cherry_lf','label' => 'Vanity, Wood, Cherry, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_distressed_lf','label' => 'Vanity, Wood, Distressed, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_exotic_lf','label' => 'Vanity, Wood, Exotic, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_hickory_lf','label' => 'Vanity, Wood, Hickory, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_maple_lf','label' => 'Vanity, Wood, Maple, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_oak_lf','label' => 'Vanity, Wood, Oak, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_rustic_lf','label' => 'Vanity, Wood, Rustic, LF','multi_field' => array('lf')),
								array('name' => 'vanity_wood_walnut_lf','label' => 'Vanity, Wood, Walnut, LF','multi_field' => array('lf')),
								array('name' => 'plexiglas','label' => 'Water Closet, Deluxe','multi_field' => array('count')),
								array('name' => 'bathroom_build_up_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'built_in_cabinetry_niches',
				        'label' => 'Built-in Cabinetry & Niches',
				        'form_items' => array(
								array('name' => 'cabinetry_built_in_cnt','label' => 'Cabinetry, Built-in, Cnt','multi_field' => array('count')),
								array('name' => 'wall_niche_cast_plaster_built_in_lights_small','label' => 'Wall Niche, Cast Plaster, Built-in Lights, Small','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_small','label' => 'Wall Niche, Marble/Granite, Small','multi_field' => array('count')),
								array('name' => 'wall_niche_cast_plaster_small','label' => 'Wall Niche, Cast Plaster, Small','multi_field' => array('count')),

								array('name' => 'cabinetry_built_in_custom_sqft','label' => 'Cabinetry, Built-in, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinetry_built_in','label' => 'Cabinetry, Built-in','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'cabinetry_built_in_average_sqft','label' => 'Cabinetry, Built-in, Average, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinetry_built_in_cherry_cnt','label' => 'Cabinetry, Built-in, Cherry, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_cherry','label' => 'Cabinetry, Built-in, Cherry','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_chestnut','label' => 'Cabinetry, Built-in, Chestnut','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_chestnut_cnt','label' => 'Cabinetry, Built-in Chestnut, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_fruitwood_cnt','label' => 'Cabinetry, Built-in, Fruitwood, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_fruitwood','label' => 'Cabinetry, Built-in, Fruitwood','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_knotty_pine_cnt','label' => 'Cabinetry, Built-in, Knotty Pine, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_knotty_pine','label' => 'Cabinetry, Built-in, Knotty Pine ','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_mahogany_cnt','label' => 'Cabinetry, Built-in , Mahogany, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_mahogany','label' => 'Cabinetry, Built-in Mahogany','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_oak_cnt','label' => 'Cabinetry, Built-in, Oak, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_oak','label' => 'Cabinetry, Built-in, Oak','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_painted_pine_cnt','label' => 'Cabinetry, Built-in, Painted Pine, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_painted_pine','label' => 'Cabinetry, Built-in, Painted Pine','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_rosewood','label' => 'Cabinetry, Built-in, Rosewood','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_stained_pine','label' => 'Cabinetry, Built-in, Stained Pine','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_teak_cnt','label' => 'Cabinetry, Built-in, Teak, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_teak','label' => 'Cabinetry, Built-in, Teak','multi_field' => array('percentage')),
								array('name' => 'cabinetry_built_in_walnut_cnt','label' => 'Cabinetry, Built-in, Walnut, Cnt','multi_field' => array('count')),
								array('name' => 'cabinetry_built_in_walnut','label' => 'Cabinetry, Built-in, Walnut','multi_field' => array('percentage')),
								array('name' => 'wall_niche_cast_plaster_built_in_lights_large','label' => 'Wall Niche, Cast Plaster, Built-in Lights, Large','multi_field' => array('count')),
								array('name' => 'wall_niche_cast_plaster_built_in_lights','label' => 'Wall Niche, Cast Plaster Built-in Lights, Medium','multi_field' => array('count')),
								array('name' => 'wall_niche_cast_plaster_large','label' => 'Wall Niche, Cast Plaster, Large','multi_field' => array('count')),
								array('name' => 'wall_niche_cast_plaster_medium','label' => 'Wall Niche, Cast Plaster, Medium','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_built_in_lights_large','label' => 'Wall Niche, Marble/Granite, Built-in Lights, Large','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_built_in_lights_medium','label' => 'Wall Niche, Marble/Granite, Built-in Lights, Medium','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_built_in_lights_small','label' => 'Wall Niche, Marble/Granite, Built-in Lights, Small','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_large','label' => 'Wall Niche, Marble/Granite, Large','multi_field' => array('count')),
								array('name' => 'wall_niche_marble_granite_medium','label' => 'Wall Niche, Marble/Granite, Medium','multi_field' => array('count')),
								array('name' => 'built_in_cabinetry_niches_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'staircases',
				        'label' => 'Staircases',
				        'form_items' => array(
								array('name' => 'stairs_straight_open_one_sided_hardwood','label' => 'Stairs, Straight, Open One-Sided, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_hdwood_carpet','label' => 'Stairs, Straight, Open One-Sided, Hdwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood','label' => 'Stairs, Straight, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood_carpet','label' => 'Stairs, Straight, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'staircase_spiral_wood','label' => 'Staircase, Spiral, Wood','multi_field' => array('count')),
								array('name' => 'staircase_spiral_metal','label' => 'Staircase, Spiral, Metal','multi_field' => array('count')),
								array('name' => 'staircase_straight_softwood','label' => 'Staircase, Straight, Softwood','multi_field' => array('count')),
								array('name' => 'stairs_curved_hardwood','label' => 'Stairs, Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_curved_hardwood_carpet','label' => 'Stairs, Curved, Hardwood & Carpet','multi_field' => array('count')),

								array('name' => 'stairs_floating_hardwood','label' => 'Stairs, Floating, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_floating_hardwood_carpet','label' => 'Stairs, Floating, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'balustrade_hardwood_lf','label' => 'Balustrade, Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_hardwood_lf','label' => 'Balustrade, Metal & Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_lf','label' => 'Balustrade, Metal, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_glass_lf','label' => 'Balustrade, Metal & Glass, LF','multi_field' => array('lf')),
								array('type' => 'divider/separator'),

								array('name' => 'balustrade_hardwood_carved_lf','label' => 'Balustrade, Hardwood, Carved, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_hardwood_ornate_lf','label' => 'Balustrade, Hardwood, Ornate, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_carved_hardwood_lf','label' => 'Balustrade, Metal & Carved Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_marble_lf','label' => 'Balustrade, Metal & Marble, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_gilded_lf','label' => 'Balustrade, Metal, Gilded, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_gilded_hardwood_lf','label' => 'Balustrade, Metal, Gilded & Hardwood, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_gilded_marble_lf','label' => 'Balustrade, Metal, Gilded & Marble, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_metal_ornate_lf','label' => 'Balustrade, Metal, Ornate, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_rustic_wood_lf','label' => 'Balustrade, Rustic Wood, LF','multi_field' => array('lf')),
								array('name' => 'balustrade_stone_lf','label' => 'Balustrade, Stone, LF','multi_field' => array('lf')),
								array('name' => 'banister_ornate_lf','label' => 'Banister, Ornate, LF','multi_field' => array('lf')),
								array('name' => 'carpet_rod_brass','label' => 'Carpet Rod, Brass','multi_field' => array('count')),
								array('name' => 'staircase_metal_ornamental_lf','label' => 'Staircase, Metal, Ornamental, LF','multi_field' => array('lf')),
								array('name' => 'staircase_stone_ornamental_lf','label' => 'Staircase, Stone, Ornamental, LF','multi_field' => array('lf')),
								array('name' => 'stairs_attic_pull_down_metal_ea','label' => 'Stairs, Attic, Pull Down, Metal, EA','multi_field' => array('count')),
								array('name' => 'stairs_attic_pull_down_wood_ea','label' => 'Stairs, Attic, Pull Down, Wood, EA','multi_field' => array('count')),
								array('name' => 'stairs_curved_granite','label' => 'Stairs, Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_curved_half_timber','label' => 'Stairs, Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_curved_hardwood_carpet','label' => 'Stairs, Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_curved_marble','label' => 'Stairs, Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_curved_stone','label' => 'Stairs, Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_floating_granite','label' => 'Stairs, Floating, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_half_timber','label' => 'Stairs, Floating, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_marble','label' => 'Stairs, Floating, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_stone','label' => 'Stairs, Floating, Stone','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_granite','label' => 'Stairs, Floating/Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_half_timber','label' => 'Stairs, Floating/Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_hardwood','label' => 'Stairs, Floating/Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_hardwood_carpet','label' => 'Stairs, Floating/Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_marble','label' => 'Stairs, Floating/Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_stone','label' => 'Stairs, Floating/Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_spiral_metal_w_railing','label' => 'Stairs, Spiral, Metal, W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_spiral_wood_metal_w_railing','label' => 'Stairs, Spiral, Wood & Metal, W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_spiral_wood_w_railing','label' => 'Stairs, Spiral, Wood W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_granite','label' => 'Stairs, Split T- Shape, Granite','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_half_timber','label' => 'Stairs, Split T-Shape, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood','label' => 'Stairs, Split T-Shape, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood_carpet','label' => 'Stairs, Split T-Shape, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_marble','label' => 'Stairs, Split T-Shape, Marble','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_stone','label' => 'Stairs, Split-T-Shape, Stone','multi_field' => array('count')),
								array('name' => 'stairs_straight_granite','label' => 'Stairs, Straight, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_granite','label' => 'Stairs, Straight, Marble','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_granite','label' => 'Stairs, Straight, Open One -Sided, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_half_timber','label' => 'Stairs, Straight, Open One-Sided, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_marble','label' => 'Stairs, Straight, Open One-Sided, Marble','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_stone','label' => 'Stairs, Straight, Open One Sided, Stone','multi_field' => array('count')),
								array('name' => 'stairs_straight_stone','label' => 'Stairs, Straight, Stone','multi_field' => array('count')),
								array('name' => 'stairs_wood_ornamental','label' => 'Stairs, Wood, Ornamental','multi_field' => array('count')),
								array('name' => 'staircases_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'wide_staircases',
				        'label' => 'Wide Staircases',
				        'form_items' => array(
								array('name' => 'stairs_floating_curved_hardwood_carpet','label' => 'Stairs, Floating/Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_hardwood_carpet','label' => 'Stairs, Straight, Open One-Sided, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_marble','label' => 'Stairs, Straight, Open One-Sided, Marble','multi_field' => array('count')),
								array('name' => 'stairs_curved_hardwood_carpet','label' => 'Stairs, Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'stairs_curved_granite','label' => 'Stairs, Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_curved_half_timber','label' => 'Stairs, Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_curved_hardwood','label' => 'Stairs, Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_curved_marble','label' => 'Stairs, Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_curved_stone','label' => 'Stairs, Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_floating_granite','label' => 'Stairs, Floating, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_half_timber','label' => 'Stairs, Floating, Half-timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_hardwood','label' => 'Stairs, Floating, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_floating_hardwood_carpet','label' => 'Stairs, Floating, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_floating_marble','label' => 'Stairs, Floating, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_stone','label' => 'Stairs, Floating, Stone','multi_field' => array('count')),
				                array('name' => 'stairs_floating_curved_granite','label' => 'Stairs, Floating/Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_half_timber','label' => 'Stairs, Floating/Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_hardwood','label' => 'Stairs, Floating/Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_marble','label' => 'Stairs, Floating/Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_stone','label' => 'Stairs, Floating/Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_spiral_metal_w_railing','label' => 'Stairs, Spiral, Metal, W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_spiral_wood_metal_w_railing','label' => 'Stairs, Spiral, Wood & Metal, W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_spiral_wood_w_railing','label' => 'Stairs, Spiral, Wood , W/Railing','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_granite','label' => 'Stairs, Split T-Shape, Granite','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_half_timber','label' => 'Stairs, Split T-Shape, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood','label' => 'Stairs, Split T-Shape, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood_carpet','label' => 'Stairs, Split T-Shape, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_marble','label' => 'Stairs, Split T-Shape, Marble','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_stone','label' => 'Stairs, Split T-Shape, Stone','multi_field' => array('count')),
								array('name' => 'stairs_straight_granite','label' => 'Stairs, Straight, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood','label' => 'Stairs, Straight, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood_carpet','label' => 'Stairs, Straight, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_marble','label' => 'Stairs, Straight, Marble','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_granite','label' => 'Stairs, Straight, Open One-Sided, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_half_timber','label' => 'Stairs, Straight, Open One-Sided, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_hardwood','label' => 'Stairs, Straight, Open One-Sided, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_stone','label' => 'Stairs, Straight, Open One-Sided, Stone','multi_field' => array('count')),
								array('name' => 'stairs_straight_stone','label' => 'Stairs, Straight, Stone','multi_field' => array('count')),
				                array('name' => 'wide_staircases_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'extra_wide_staircases',
				        'label' => 'Extra Wide Staircases',
				        'form_items' => array(
				        		array('name' => 'stairs_floating_hardwood_carpet','label' => 'Stairs, Floating, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood','label' => 'Stairs, Straight, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_hardwood','label' => 'Stairs, Straight, Open One-Sided, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_hardwood_carpet','label' => 'Stairs, Straight, Open One Sided, Hardwood & Carpet','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'staircase_curved_hardwood','label' => 'Staircase, Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'staircase_curved_hardwood_carpet','label' => 'Staircase, Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'staircase_floating_curved_hardwood','label' => 'Staircase, Floating/Curved, Hardwood','multi_field' => array('count')),
								array('name' => 'staircase_floating_curved_hardwood_carpet','label' => 'Staircase, Floating/Curved, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_curved_granite','label' => 'Stairs, Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_curved_half_timber','label' => 'Stairs, Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_curved_marble','label' => 'Stairs, Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_curved_stone','label' => 'Stairs, Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_floating_granite','label' => 'Stairs, Floating, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_half_timber','label' => 'Stairs, Floating, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_hardwood','label' => 'Stairs, Floating, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_floating_marble','label' => 'Stairs, Floating, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_stne','label' => 'Stairs, Floating, Stone','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_granite','label' => 'Stairs, Floating/Curved, Granite','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_half_timber','label' => 'Stairs, Floating/Curved, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_marble','label' => 'Stairs, Floating/Curved, Marble','multi_field' => array('count')),
								array('name' => 'stairs_floating_curved_stone','label' => 'Stairs, Floating/Curved, Stone','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_granite','label' => 'Stairs, Split T-Shape, Granite','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_half_timber','label' => 'Stairs, Split T-Shape, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood','label' => 'Stairs, Split T-Shape, Hardwood','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_hardwood_carpet','label' => 'Stairs, Split T-Shape, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_marble','label' => 'Stairs, Split T-Shape, Marble','multi_field' => array('count')),
								array('name' => 'stairs_split_t_shape_stone','label' => 'Stairs, Spilt T-Shape, Stone','multi_field' => array('count')),
								array('name' => 'stairs_straight_granite','label' => 'Stairs, Straight, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_hardwood_carpet','label' => 'Stairs, Straight, Hardwood & Carpet','multi_field' => array('count')),
								array('name' => 'stairs_straight_marble','label' => 'Stairs, Straight, Marble','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_granite','label' => 'Stairs, Straight, Open One-Sided, Granite','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_half_timber','label' => 'Stairs, Straight, Open One-Sided, Half-Timber','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_marble','label' => 'Stairs, Straight, Open One-Sided, Marble','multi_field' => array('count')),
								array('name' => 'stairs_straight_open_one_sided_stone','label' => 'Stairs, Straight, Open One-Sided, Stone','multi_field' => array('count')),
								array('name' => 'extra_wide_staircases_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'interior_columns',
				        'label' => 'Interior Columns',
				        'form_items' => array(
								array('name' => 'Column_Wood','label' => 'Column, Wood','multi_field' => array('count')),
								array('name' => 'half_column_wd_stave_12_under_lf','label' => 'Half-Column, Wd Stave, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'Column_Stone','label' => 'Column, Stone','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'Column_Brick_LF','label' => 'Column, Brick, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_12_under_lf','label' => 'Column, Granite, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_13_16_lf','label' => 'Column, Granite, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_17_20_lf','label' => 'Column, Granite, 17&quot;-20&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_21_24_lf','label' => 'Column, Granite, 21&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_25_over_lf','label' => 'Column, Granite, 25&quot; & Over, LF','multi_field' => array('lf')),
								array('name' => 'column_marble_12_under_lf','label' => 'Column, Marble, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'column_marble_13_16_lf','label' => 'Column, Marble, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_marble_17_20_lf','label' => 'Column, Marble, 17&quot;-20&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_marble_21_24_lf','label' => 'Column, Marble, 21&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_marble_25_over_lf','label' => 'Column, Marble, 25&quot; & Over, LF','multi_field' => array('lf')),
								array('name' => 'column_stone_lf','label' => 'Column, Stone, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_stave_12_under_lf','label' => 'Column, Wood Stave, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_stave_13_24_lf','label' => 'Column, Wood Stave, 13&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_2_x_2_lf','label' => 'Column, Wood, 2&apos; x 2&apos;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_brick_lf','label' => 'Half-Column,  Brick, LF','multi_field' => array('lf')),
								array('name' => 'half_column_granite_12_under_lf','label' => 'Half-Column, Granite, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'half_column_granite_13_16_lf','label' => 'Half-Column, Granite, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_granite_17_20_lf','label' => 'Half-Column, Granite, 17&quot;-20&quot;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_granite_21_over_lf','label' => 'Half-Column, Granite, 21&quot; & Over, LF','multi_field' => array('lf')),
								array('name' => 'half_column_marble_12_under_lf','label' => 'Half-Column, Marble, 12&quot; & Under, LF','multi_field' => array('lf')),
								array('name' => 'half_column_marble_13_16_lf','label' => 'Half-Column, Marble, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_marble_17_20_lf','label' => 'Half-Column, Marble, 17&quot;-20&quot;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_marble_21_over_lf','label' => 'Half-Column, Marble, 21&quot; & Over, LF','multi_field' => array('lf')),
								array('name' => 'half_column_stone_lf','label' => 'Half-Column, Stone, LF','multi_field' => array('lf')),
								array('name' => 'half_column_wood_stave_13_24_lf','label' => 'Half-Column, Wood Stave, 13&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'half_column_wood_stave_25_over_lf','label' => 'Half-Column, Wood Stave, 25&quot; & Over, LF','multi_field' => array('lf')),
								array('name' => 'interior_columns_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
			'id' => 'interior_conditions_summary_subheading',
			'sub_label' => 'INTERIOR CONDITIONS SUMMARY',
			'type' => 'subheading'
		);
		$field_sets[] = array(
				        'id' => 'interior_conditions_summary',
				        'label' => 'Interior Conditions Summary',
				        'form_items' => array(
								array('name' => 'interior_conditions_summary','label' => 'Condition of Walls / Floors / Ceilings','type' => 'multiselect','datasets' => self::$cwfc_options),
								array('name' => 'interior_conditions_summary_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
			'id' => 'general_interior_comments_subheading',
			'sub_label' => 'GENERAL INTERIOR COMMENTS',
			'type' => 'subheading'
		);
		$field_sets[] = array(
                        'id' => 'general_interior_public_comments',
                        'label' => 'General Interior: Public Comments',
                        'form_items' => array(
                               	array('name' => 'general_interior_public_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
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
			
		if(empty($params)) { 
			return;
		}
	}

	static function getSelectFields($label) {
		self::drawForm();
		return self::$$label;
	}
}

class Interior extends InteriorView{
		function __construct(){}
}
?>
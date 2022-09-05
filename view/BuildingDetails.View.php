<?php
/**
 * View class containing all the Building tab related functionalities.
 *
 * @since 1.0
 */
class BuildingDetailsView extends BuildingDetailsController{
	
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();	
	public static $site_access_options, $home_building_style_options, $construction_dwelling_options,
					$construction_structure_options, $foundation_materials_options, 
					$foundation_conditions_options, $perimeter_of_house_basic_options, $basement_stairs_options;
	public static $hidden_tor_categories = array('bc_assessment_sqft', 'sqft_stated_by_insured', 'site_access',
													'year_insured_purchased_dwelling', 'reno_status'); 
	
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
								'css_class' => ''
							);

			if(isset($params['inspection_id']) && $params['inspection_id']){
				$form_details['inspection_id'] = $params['inspection_id'];
				$inspection_data = self::getData($params);

				if(is_array($inspection_data) && count($inspection_data) > 0){
					$form_details['values']	= $inspection_data;
				}
			}
		}

		self::$site_access_options = array(
									'1' => 'Slightly Congested Roads',
									'2' => 'Flat Area/Easy Access Roads',
									'3' => 'Mountain Region',
									'4' => 'Narrow/Hillside Area',
									'5' => 'Difficult Access/Steep Terrain',
									'6' => 'Isolated Rural Area',
									'7' => 'Island Access - Long Distance',
									'8' => 'Island Access - Short Distance',
									'9' => 'Elevator Access'
								);
		self::$home_building_style_options = array(
											'1' => '1 Story',
											'2' => '1.5 Story',
											'3' => '2 Story',
											'4' => '2.5 Story',
											'5' => '3 Story',
											'6' => 'Ranch',
											'7' => 'Cape cod',
											'8' => 'Colonial',
											'9' => 'divider/separator',
											'10' => 'Row house - end',
											'11' => 'Row house - center',
											'12' => 'Federal colonial',
											'13' => 'Queen Anne',
											'14' => 'Southwest Adobe',
											'15' => 'Bungalow',
											'16' => 'Cottage',
											'17' => 'Substandard',
											'18' => 'Victorian',
											'19' => 'Townhouse - end',
											'20' => 'Townhouse - center',
											'21' => 'Mediterranean',
											'22' => 'Rambler',
											'23' => 'Condo / co-op',
											'24' => 'Bi-level',
											'25' => 'Split level',
											'26' => 'Back split',
											'27' => 'Raised ranch',
											'28' => 'Split foyer',
											'29' => 'Tri-level',
											'30' => 'Contemporary'
										);
		self::$construction_dwelling_options = array(
											'1' => 'Standard',
											'2' => 'Vintage',
											'3' => 'Std/Vintage'
										);
		self::$construction_structure_options = array(
											'1' => 'Framed Detached: Single Family Dwelling',
											'2' => 'Framed Detached: Single Family Dwelling w/Suite',
											'3' => 'Framed: Semi-Detached',
											'4' => 'Framed/Post & Beam',
											'5' => 'Post & Beam',
											'6' => 'Log Framed',
											'7' => 'Framed/Log Frame',
											'8' => 'FI unable to determine Structure Type (must make a tab (A) note)',
											'9' => 'divider/separator',
											'10' => 'Framed Row House',
											'11' => 'Framed/Steel Stud',
											'12' => 'Concrete Tilt-up',
											'13' => 'Concrete & Steel'
										);
		self::$foundation_materials_options = array(
											'1' => 'Concrete',
											'2' => 'Block',
											'3' => 'Brick',
											'4' => 'Fieldstone',
											'5' => 'Steel',
											'6' => 'Wood, Treated'
										);
		self::$foundation_conditions_options = array(
											'1' => 'Good',
											'2' => 'New',
											'3' => 'Good to Average',
											'4' => 'Average',
											'5' => 'Can&apos;t be seen',
											'6' => 'Cracks (minor)',
											'7' => 'Cracks (major)  - Must have photo(s)!!',
											'8' => 'Damaged Areas  - Must have photo(s)!!'
										);
		self::$perimeter_of_house_basic_options = array(
											'1' => 'Rectangular or Slightly Irregular',
											'2' => 'Irregular',
											'3' => 'Very Irregular'
										);
		self::$basement_stairs_options = array(
											'1' => 'Stairs, Basement, w/Railing',
											'2' => 'Stairs, Basement, Wide, w/Railing'
										);

		$field_sets = array();
		
		$field_sets[] = array(
							'id' => 'building_data_subheading',
							'sub_label' => 'BUIDING DATA',
							'type' => 'subheading'
						);

		$field_sets[] = array(
							'id' => 'sqft_from_field_inspector',
							'label' => 'Sq. Ft. from Field Inspector (FI)',
							'form_items' => array(
												array('name' => 'c3', 'label' => '1st Storey', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c4', 'label' => '2nd Storey', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c5', 'label' => '3rd Storey', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c6', 'label' => '4th Storey', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c7', 'label' => 'Bsmt Area (Total Basement Area)', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c7_extra', 'label' => 'Basement is Larger than Main Floor, Add Extra -', 'onkeyup' => 'request_inspection.building_calculation(this)'),

												array('name' => 'c8', 'label' => 'Built-in Garage (Living/Finished space)', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c9', 'label' => 'Lwr Lvl unfin. space in finished living area', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'c10', 'label' => 'Finished attic Space', 'onkeyup' => 'request_inspection.building_calculation(this)'),
												array('name' => 'tba', 'label' => 'Total Building Area (all space within building)','type' => 'readonly'),
												array('name' => 'c12', 'label' => 'Finished Living Area (FLA)','type' => 'readonly'),
												array('name' => 'tla', 'label' => 'Total Living Area (TLA)','type' => 'readonly'),
												array('name' => 'sqft_from_field_inspector_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		// $field_sets[] = array(
		// 					'id' => 'bc_assessment_sqft',
		// 					'label' => 'BC Assessment Sq Ft',
		// 					'form_items' => array(
		// 										array('name' => 'c17', 'label' => 'First Floor Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
		// 										array('name' => 'c17_hidden', 'type' => 'hidden'),
		// 										array('name' => 'c18', 'label' => 'Second Floor Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
		// 										array('name' => 'c18_hidden', 'type' => 'hidden'),
		// 										array('name' => 'c19', 'label' => 'Basement Finished Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
		// 										array('name' => 'c19_hidden', 'type' => 'hidden'),
		// 										array('name' => 'c20', 'label' => 'Other Areas', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
		// 										array('name' => 'c20_hidden', 'type' => 'hidden'),
		// 										array('name' => 'total_bc_assessment', 'label' => 'Total BC Assessment Sq Ft','type' => 'readonly'),
		// 										array('name' => 'bc_assessment_sqft_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
		// 									)
		// 				);
		// $field_sets[] = array(
						// 	'id' => 'sqft_stated_by_insured',
						// 	'label' => 'Stated by Insured Sq Ft',
						// 	'form_items' => array(
						// 						array('name' => 'sqft_stated_by_insured', 'label' => 'Sq.Ft. Stated by Insured', 'onkeyup' => 'ui.format_comma(this)'),
						// 						array('name' => 'sqft_stated_by_insured_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						// 					)
						// );

		$field_sets[] = array(
							'id' => 'bc_assessment_sqft_stated_by_insured',
							'label' => 'BC Assessment / Insured Stated Sq Ft',
							'form_items' => array(
												array('type' => 'header', 'name' => 'BC Assessment Sq Ft'),
												array('name' => 'c17', 'label' => 'First Floor Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
												array('name' => 'c17_hidden', 'type' => 'hidden'),
												array('name' => 'c18', 'label' => 'Second Floor Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
												array('name' => 'c18_hidden', 'type' => 'hidden'),
												array('name' => 'c19', 'label' => 'Basement Finished Area', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
												array('name' => 'c19_hidden', 'type' => 'hidden'),
												array('name' => 'c20', 'label' => 'Other Areas', 'onkeyup' => 'request_inspection.building_calculation_bc_assessment(this)'),
												array('name' => 'c20_hidden', 'type' => 'hidden'),
												array('name' => 'total_bc_assessment', 'label' => 'Total BC Assessment Sq Ft','type' => 'readonly'),

												array('type' => 'header', 'name' => 'Insured Sq.Ft.'),
												array('name' => 'sqft_stated_by_insured', 'label' => 'Sq.Ft. Stated by Insured', 'onkeyup' => 'ui.format_comma(this)'),
												
												array('name' => 'bc_assessment_sqft_stated_by_insured_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true
		);

		$field_sets[] = array(
							'id' => 'families',
							'label' => 'Enter # of Families',
							'form_items' => array(
												array('name' => 'families', 'label' => 'Enter # of Families'),
												array('name' => 'families_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1, 'override_fieldset' => 1)
											)
							);
		$field_sets[] = array(
							'id' => 'site_access',
							'label' => 'Site Access',
							'form_items' => array(
												array('name' => 'site_access', 'label' => 'Site Access', 'type' => 'multiselect', 'datasets' => self::$site_access_options),
												array('name' => 'site_access_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'home_building_style',
							'label' => 'Home (Building) Style',
							'form_items' => array(
												array('name' => 'home_building_style', 'label' => 'Home (Building) Style', 'type' => 'multiselect',  'datasets' => self::$home_building_style_options),
												array('name' => 'home_building_style_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		// $field_sets[] = array(
		// 					'id' => 'year_built',
		// 					'label' => 'Enter Year Built',
		// 					'form_items' => array(
		// 										array('name' => 'year_built', 'label' => 'Enter Year Built(Enter Year)', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."year"."')"),
		// 										array('name' => 'year_built_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
		// 									)
		// 					);
		// $field_sets[] = array(
		// 					'id' => 'year_built_confirmed',
		// 					'label' => 'Year Built, Confirmed?',
		// 					'form_items' => array(
		// 										array('name' => 'year_built_confirmed_by_insured', 'label' => 'Year Built Confirmed by Insured', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y"."')"),
		// 										array('name' => 'estimated_only', 'label' => 'Estimated only', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y"."')"),
		// 										array('name' => 'unable_to_confirm_year_built', 'label' => 'Unable to Confirm year built', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."x"."')"),
		// 										array('name' => 'year_built_confirmed_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
		// 									)
		// 				);
		// $field_sets[] = array(
		// 					'id' => 'year_insured_purchased_dwelling',
		// 					'label' => 'Year Insured purchased dwelling',
		// 					'form_items' => array(
		// 										array('name' => 'year_insured_purchased_dwelling', 'label' => 'Year Insured purchased dwelling', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."year"."')"),
		// 										array('name' => 'unable_to_confirm_year_purchased', 'label' => 'Unable to Confirm from Insured year purchased', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y-x"."')"),
		// 										array('name' => 'year_insured_purchased_dwelling_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
		// 									)
		// 				);

		$field_sets[] = array(
							'id' => 'year_built_insured_purchased_dwelling',
							'label' => 'Enter Year Built/ Confirmed?/ Insured Purchased?',
							'form_items' => array(

												array('name' => 'year_built', 'label' => 'Enter Year Built(Enter Year)', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."year"."')"),
												array('name' => 'year_built_confirmed_by_insured', 'label' => 'Year Built Confirmed by Insured', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y"."')"),
												array('name' => 'estimated_only', 'label' => 'Estimated only', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y"."')"),
												array('name' => 'unable_to_confirm_year_built', 'label' => 'Unable to Confirm year built', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."x"."')"),

												array('name' => 'year_insured_purchased_dwelling', 'label' => 'Year Insured purchased dwelling', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."year"."')"),
												array('name' => 'unable_to_confirm_year_purchased', 'label' => 'Unable to Confirm from Insured year purchased', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y-x"."')"),
												array('name' => 'year_built_insured_purchased_dwelling_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							),
							'priority' => true

						);
							
							
		$field_sets[] = array(
							'id' => 'reno_status',
							'label' => 'Reno Status',
							'form_items' => array(
												array('name' => 'prior_reno', 'label' => 'Prior Reno', 'place_holder' => 'Month / Year', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."m-y"."')"),
												array('name' => 'currently_under_reno', 'label' => 'Currently under Reno', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y-x"."')"),
												array('name' => 'reno_planned', 'label' => 'Reno Planned', 'place_holder' => 'Month / Year', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."m-y"."')"),
												array('name' => 'major_reno_past_5yrs_or_more', 'label' => 'Major reno past 5yrs or more', 'place_holder' => 'Y / X', 'onkeyup' => "request_inspection.allow_specific_char(this,'"."y-x"."')"),
												array('name' => 'reno_status_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'construction_dwelling_type',
							'label' => 'Construction (Dwelling) Type',
							'form_items' => array(
												array('name' => 'construction_dwelling_type', 'label' => 'Construction (Dwelling) Type', 'type' => 'multiselect', 'datasets' => self::$construction_dwelling_options),
												array('name' => 'construction_dwelling_type_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'number_of_stories',
							'label' => 'Number of Stories',
							'form_items' => array(
												array('name' => 'number_of_stories', 'label' => 'Number of Stories'),
												array('name' => 'number_of_stories_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'type_of_construction_structure',
							'label' => 'Type of (Construction) Structure',
							'form_items' => array(
												array('name' => 'type_of_construction_structure', 'label' => 'Type of (Construction) Structure', 'type' => 'multiselect', 'datasets' => self::$construction_structure_options),
												array('name' => 'type_of_construction_structure_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'foundation_type',
							'label' => 'Foundation Type (enter % only)',
							'form_items' => array(
												array('name' => 'slab_at_grade', 'label' => 'Slab at Grade', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_below_grade', 'label' => 'Basement, Below Grade', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_daylight', 'label' => 'Basement, Daylight', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_walkout', 'label' => 'Basement, Walkout', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_unexcavated', 'label' => 'Crawl Space, Unexcavated', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_excavated', 'label' => 'Crawl Space, Excavated', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'piers', 'label' => 'Piers', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'hillside', 'label' => 'Hillside', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'divider', 'type' => 'divider/separator'),

												array('name' => 'slab_at_grade_moderate_soil', 'label' => 'Slab at Grade - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'slab_at_grade_poor_soil', 'label' => 'Slab at Grade - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'slab_at_grade_severe_soil', 'label' => 'Slab at Grade - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_below_grade_moderate_soil', 'label' => 'Basement, Below Grade - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_below_grade_poor_soil', 'label' => 'Basement, Below Grade - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_below_grade_severe_soil', 'label' => 'Basement, Below Grade - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_daylight_moderate_soil', 'label' => 'Basement, Daylight - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_daylight_poor_soil', 'label' => 'Basement, Daylight - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_daylight_severe_soil', 'label' => 'Basement, Daylight - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_walkout_moderate_soil', 'label' => 'Basement, Walkout - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_walkout_poor_soil', 'label' => 'Basement, Walkout- Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'basement_walkout_severe_soil', 'label' => 'Basement, Walkout - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_unexcavated_moderate_soil', 'label' => 'Crawl Space, Unexcavated - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_unexcavated_poor_soil', 'label' => 'Crawl Space, Unexcavated - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_unexcavated_severe_soil', 'label' => 'Crawl Space, Unexcavated - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_excavated_moderate_soil', 'label' => 'Crawl Space, Excavated - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_excavated_poor_soil', 'label' => 'Crawl Space, Unexcavated - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'crawl_space_excavated_severe_soil', 'label' => 'Crawl Space, Unexcavated - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'hillside_moderate_soil', 'label' => 'Hillside - Moderate Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'hillside_poor_soil', 'label' => 'Hillside - Poor Soil', 'onkeyup' => 'ui.format_percentage(this)'),
												array('name' => 'hillside_severe_soil', 'label' => 'Hillside - Severe Soil', 'onkeyup' => 'ui.format_percentage(this)'),


												array('name' => 'foundation_type_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'crawl_space',
							'label' => 'Crawl Space?',
							'form_items' => array(
												array('name' => 'crawl_space', 'label' => 'Enter Crawl space if present - Height in feet'),
												array('name' => 'crawl_space_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'foundation_materials',
							'label' => 'Foundation Materials',
							'form_items' => array(
												array('name' => 'foundation_materials', 'label' => 'Foundation Materials', 'type' => 'multiselect', 'datasets' => self::$foundation_materials_options),
												array('name' => 'foundation_materials_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'foundation_conditions',
							'label' => 'Foundation Conditions',
							'form_items' => array(
												array('name' => 'foundation_conditions', 'label' => 'Foundation Conditions', 'type' => 'multiselect', 'datasets' => self::$foundation_conditions_options),
												array('name' => 'foundation_conditions_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'basement_stairs',
							'label' => 'Basement Stairs',
							'form_items' => array(
								array('name' => 'basement_stairs', 'label' => 'Basement Stairs', 'type' => 'multiselect', 'datasets' => self::$basement_stairs_options),
								array('name' => 'basement_stairs_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
						);
		$field_sets[] = array(
							'id' => 'basement_levels',
							'label' => 'Basement Levels',
							'form_items' => array(
													array('name' => 'basement_levels', 'label' => 'Enter # of Basement Levels'),								
													array('name' => 'basement_levels_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
							);
		$field_sets[] = array(
			'id' => 'basement_depth',
			'label' => 'Basement Depth',
			'form_items' => array(
								array('name' => 'no_basement','label' => 'No Basement','type' => 'check'),
								array('name' => 'basement_depth', 'label' => 'Enter Basement Depth (Enter Height in feet)'),
								array('name' => 'basement_depth_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							)
		);
		$field_sets[] = array(
			'id' => 'basement_finish',
			'label' => 'Basement Finish',
			'form_items' => array(
								array('name' => 'no_finished_basement_areas','label' => 'No Finished basement areas','type' => 'check'),
								array('name' => 'standard_finish', 'label' => 'Standard Finish', 'onkeyup' => 'request_inspection.calculate_basement_finish(this)'),
								array('name' => 'custom_finish', 'label' => 'Custom Finish', 'onkeyup' => 'request_inspection.calculate_basement_finish(this)'),
								array('name' => 'unfinished_bsmt_area', 'label' => 'Unfinished Bsmt Area (This number must not be negative or your numbers are wrong!)'),
								array('name' => 'total_finished_area', 'label' => 'Total Finished Area'),
							)
		);
		$field_sets[] = array(
			'id' => 'perimeter_of_house_basic',
			'label' => 'Perimeter of House - Basic',
			'form_items' => array(
								array('name' => 'perimeter_of_house_basic', 'label' => 'Perimeter of House - Basic', 'type' => 'multiselect', 'datasets' => self::$perimeter_of_house_basic_options),
								array('name' => 'perimeter_of_house_basic_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							)
		);
		$field_sets[] = array(
			'id' => 'perimeter_of_house_measurement',
			'label' => 'Perimeter of House - Measurement',
			'form_items' => array(
									array('name' => 'perimeter_of_house_measurement', 'label' => 'If measured, perimeter (Enter Linear feet)', 'onkeyup' => 'request_inspection.format_final_output(this, false, true)'),
									array('name' => 'perimeter_of_house_measurement_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							)
		);

		$field_sets[] = array(
			'id' => 'building_comments_subheading',
			'sub_label' => 'BUILDING COMMENTS',
			'type' => 'subheading'
		);

		$field_sets[] = array(
			'id' => 'building_comments',
			'label' => 'Building Comments',
			'form_items' => array(
									array('name' => 'building_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
							)
		);

		if(RequestInspection::isTor($params['inspection_id'])){
			foreach($field_sets as $key => $field){
				if(in_array($field['id'], self::$hidden_tor_categories)){
					unset($field_sets[$key]);          
				}
			}
		}	
		
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
		if(empty($params)) { 
			return;
		}
	}

	//return select input labels
	static function getSelectFields($label) {
		self::drawForm();
		return self::$$label;
	}
}

class BuildingDetails extends BuildingDetailsView{
    function __construct(){}
}
?>
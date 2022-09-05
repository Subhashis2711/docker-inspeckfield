<?php
class ExteriorView extends ExteriorController{
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();
	public static $ewc_options, $rsc_options, $gs_options;

	/**
	 *
	 * Function used for drawing form with fields.
	 *
	 * @param array $params array containing necessary parameters.
	 *
	 */
	static function drawForm($params=array()){
		if(!empty($params)){
			$tab_id 						= $params['tab_id'];
			
			$form_details 					= array(
												'id' => $tab_id.'_form',
												'tab_id' => $tab_id,
												'css_class' => '',
												'inspection_id' => $params['inspection_id']

											);

			if(isset($params['inspection_id']) && $params['inspection_id']){
				$inspection_data 			= self::getData($params);

				if(is_array($inspection_data) && count($inspection_data) > 0){
					$form_details['values']	= $inspection_data;
				}
			}
		}

		$field_sets = array();
		self::$ewc_options = array(
				'1' => 'Good condition',
				'2' => 'High quality materials, very good conditions',
				'3' => 'New Construction, high quality materials',
				'4' => 'Average condition',
				'5' => 'Renovated wall surfaces, Add Note',
				'6' => 'Paint required and/or exposed materials',
				'7' => 'Minor defects, Add Note',
				'8' => 'Wall damage, Add Note'
			);
		self::$rsc_options = array(
				'1' => 'Roof in Good condition, no problems visible with material',
				'2' => 'Roof surface in good overall condition ',
				'3' => 'New roof, in Excellent condition',
				'4' => 'Roof surface in Good condition overall, but with some minor moss growth',
				'5' => 'Roof surface in Average condition, some areas showing normal weathering',
				'6' => 'Roof surface in Average condition, but with moss growth and/or debris',
				'7' => 'Unable to confirm Roof Surface conditions',
				'8' => 'Roof surface is damaged, Add Note',
				'9' => 'Roof surface is in Poor condition, decay and/or damage visible',
				'10' => 'Curling and/or split shingles visible on Roof Surface',
				'11' => 'Patched areas visible, Add Note',
				'12' => 'Tarp is being used to cover roof area(s)',
				'13' => 'Unfinished and/or replacement work is in progress on Roof'
			);
		self::$gs_options = array(
				'1' => 'Gutters & Soffits in Good condition',
				'2' => 'Hidden Gutters & Soffits in Good condition',
				'3' => 'Downspouts off and/or missing',
				'4' => 'Gutters loose and/or damaged',
				'5' => 'Copper Gutters and/or Copper downspoouts in Good condition'
			);
		$field_sets[] = array(
						'id' => 'exterior_subheading',
						'sub_label' => 'EXTERIOR',
						'type' => 'subheading'
					);
		$field_sets[] = array(
						'id' => 'roof_subheading',
						'sub_label' => 'Roof',
						'type' => 'subheading2'
					);
		$field_sets[] = array(
				        'id' => 'roof_style_slope',
				        'label' => 'Roof Style/Slope',
				        'form_items' => array(
				                array('name' => 'gable_moderate_pitch','label' => 'Gable, Moderate Pitch','multi_field' => array('percentage')),
								array('name' => 'hip_moderate_pitch','label' => 'Hip, Moderate Pitch','multi_field' => array('percentage')),
								array('name' => 'flat','label' => 'Flat','multi_field' => array('percentage')),
								array('name' => 'gable_steep_pitch','label' => 'Gable, Steep Pitch','multi_field' => array('percentage')),
								array('name' => 'hip_steep_pitch','label' => 'Hip, Steep Pitch','multi_field' => array('percentage')),
								array('name' => 'gambrel_barn','label' => 'Gambrel (Dual Pitched)','multi_field' => array('percentage')),
								array('name' => 'gable_slight_pitch','label' => 'Gable, Slight Pitch','multi_field' => array('percentage')),
								array('name' => 'hip_slight_pitch','label' => 'Hip, Slight Pitch','multi_field' => array('percentage')),
								array('name' => 'shed','label' => 'Shed','multi_field' => array('percentage')),
								array('name' => 'turret_moderate_pitch','label' => 'Turret, Moderate Pitch','multi_field' => array('percentage')),
								array('name' => 'mansard_hip_roof_2_pitches','label' => 'Mansard (hip roof, 2 pitches)','multi_field' => array('percentage')),
								array('name' => 'turret_slight_pitch','label' => 'Turret, Slight Pitch','multi_field' => array('percentage')),
								array('name' => 'turret_steep_pitch','label' => 'Turret, Steep Pitch','multi_field' => array('percentage')),
				                array('name' => 'roof_style_slope_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'roof_cover',
				        'label' => 'Roof Cover',
				        'form_items' => array(
				                array('name' => 'shingles_asphalt_fiberglass','label' => 'Shingles, Asphalt/Fiberglass','multi_field' => array('percentage')),
								array('name' => 'shingles_architectural','label' => 'Shingles, Architectural','multi_field' => array('percentage')),
								array('name' => 'shakes_wood','label' => 'Shakes, Wood','multi_field' => array('percentage')),
								array('name' => 'aluminum_corrugated','label' => 'Aluminum, Corrugated','multi_field' => array('percentage')),
								array('name' => 'steel','label' => 'Steel','multi_field' => array('percentage')),
								array('name' => 'steel_standing_seam','label' => 'Steel, Standing Seam','multi_field' => array('percentage')),
								array('name' => 'copper','label' => 'Copper','multi_field' => array('percentage')),
								array('name' => 'shingles_synthetic_rubber','label' => 'Shingles, Synthetic/Rubber','multi_field' => array('percentage')),
								array('name' => 'shingles_wood','label' => 'Shakes, Wood','multi_field' => array('percentage')),
								array('name' => 'tile_concrete','label' => 'Tile, Concrete','multi_field' => array('percentage')),
								array('name' => 'tile_spanish','label' => 'Tile, Spanish','multi_field' => array('percentage')),
								array('name' => 'built_up_tar_gravel','label' => 'Built-Up/Tar & Gravel','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'copper_standing_seam','label' => 'Copper, Standing Seam','multi_field' => array('percentage')),
								array('name' => 'aluminum_standing_seam','label' => 'Aluminum, Standing Seam','multi_field' => array('percentage')),
								array('name' => 'copper_batten_seam','label' => 'Copper, Batten Seam','multi_field' => array('percentage')),
								array('name' => 'copper_flat_seam','label' => 'Copper, Flat Seam','multi_field' => array('percentage')),
								array('name' => 'fibreglass_translucent_panel','label' => 'Fibreglass, Translucent Panel','multi_field' => array('percentage')),
								array('name' => 'foam','label' => 'Foam,','multi_field' => array('percentage')),
								array('name' => 'glass_greenhouse','label' => 'Glass/Greenhouse','multi_field' => array('percentage')),
								array('name' => 'plexiglas','label' => 'Plexiglas','multi_field' => array('percentage')),
								array('name' => 'rolled_roof_single_ply','label' => 'Rolled Roof/Single Ply','multi_field' => array('percentage')),
								array('name' => 'rubber','label' => 'Rubber,','multi_field' => array('percentage')),
								array('name' => 'shakes_victorian_scalloped','label' => 'Shakes, Victorian Scalloped','multi_field' => array('percentage')),
								array('name' => 'shingle_cement_fiber','label' => 'Shingle, Cement Fiber','multi_field' => array('percentage')),
								array('name' => 'shingles_aluminum','label' => 'Shingles, Aluminum','multi_field' => array('percentage')),
								array('name' => 'shingles_asphalt_fiberglass, Irregular Pattern','label' => 'Shingles, Asphalt/Fiberglass, Irregular Pattern','multi_field' => array('percentage')),
								array('name' => 'shingles_copper','label' => 'Shingles, Copper','multi_field' => array('percentage')),
								array('name' => 'shingles_photovoltaic','label' => 'Shingles, Photovoltaic','multi_field' => array('percentage')),
								array('name' => 'shingles_pine','label' => 'Shingles, Pine','multi_field' => array('percentage')),
								array('name' => 'shingles_slate_red','label' => 'Shingles, Slate, Red','multi_field' => array('percentage')),
								array('name' => 'shingles_steel_aggregate Finish','label' => 'Shingles, Steel, Aggregate Finish','multi_field' => array('percentage')),
								array('name' => 'shingles_titanium_pct','label' => 'Shingles, Titanium, Pct','multi_field' => array('percentage')),
								array('name' => 'shingles_wood','label' => 'Shingles, Wood','multi_field' => array('percentage')),
								array('name' => 'shingles_wood_fire_resistent','label' => 'Shingles, Wood, Fire Resistent','multi_field' => array('percentage')),
								array('name' => 'singles_zinc_pct','label' => 'Singles, Zinc Pct','multi_field' => array('percentage')),
								array('name' => 'slate','label' => 'Slate','multi_field' => array('percentage')),
								array('name' => 'slate_reinforced_fibre_composite','label' => 'Slate, Reinforced Fibre Composite','multi_field' => array('percentage')),
								array('name' => 'teme_batten_seam','label' => 'Teme, Batten Seam','multi_field' => array('percentage')),
								array('name' => 'teme_flat_seam','label' => 'Teme, Flat Seam','multi_field' => array('percentage')),
								array('name' => 'teme_standing_seam','label' => 'Teme, Standing Seam','multi_field' => array('percentage')),
								array('name' => 'thatch','label' => 'Thatch','multi_field' => array('percentage')),
								array('name' => 'tile_clay','label' => 'Tile, Clay','multi_field' => array('percentage')),
								array('name' => 'tile_clay_custom_colours','label' => 'Tile, Clay Custom Colours','multi_field' => array('percentage')),
								array('name' => 'tile_clay_glazed','label' => 'Tile, Clay, Glazed','multi_field' => array('percentage')),
								array('name' => 'tile_mission','label' => 'Tile, Mission','multi_field' => array('percentage')),
								array('name' => 'tiles_photovoltaic','label' => 'Tiles, Photovoltaic','multi_field' => array('percentage')),
								array('name' => 'tin','label' => 'Tin','multi_field' => array('percentage')),
								array('name' => 'tin_lead_coated_batten_seam','label' => 'Tin, Lead-Coated, Batten Seam','multi_field' => array('percentage')),
								array('name' => 'tin_lead_coated_flat_seam','label' => 'Tin, Lead-Coated, Flat Seam','multi_field' => array('percentage')),
								array('name' => 'tin_lead_coated_standing_seam','label' => 'Tin, Lead-Coated, Standing Seam','multi_field' => array('percentage')),
								array('name' => 'titanium_standing_seam_pct','label' => 'Titanium, Standing Seam, Pct','multi_field' => array('percentage')),
								array('name' => 'vinyl','label' => 'Vinyl','multi_field' => array('percentage')),
								array('name' => 'zinc_standing_seam_pct','label' => 'Zinc Standing Seam, Pct','multi_field' => array('percentage')),
				                array('name' => 'roof_cover_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
						),
						'priority' => true
		);
		$field_sets[] = array(
				        'id' => 'roof_shape',
				        'label' => 'Roof Shape',
				        'form_items' => array(
					        	array('name' => 'standard_simple','label' => 'Standard / Simple','multi_field' => array('percentage')),
					        	array('name' => 'elaborate_attributes','label' => 'Elaborate attributes','multi_field' => array('percentage')),
				                array('name' => 'roof_shape_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'skylights',
				        'label' => 'Skylights',
				        'form_items' => array(
				                array('name' => 'small_approx_2x4','label' => 'Small (approx. 2&apos;x4&apos;)','multi_field' => array('count')),
								array('name' => 'Large_min_4x6','label' => 'Large (min. 4&apos;x6&apos;)','multi_field' => array('count')),
								array('name' => 'custom_nonstd_size_does_not_open_sqft','label' => 'Custom (non-std size, does not open), sq.ft','multi_field' => array('sqft')),
								array('name' => 'solar_tube_small_approx_14_dia','label' => 'Solar Tube, Small (approx. 14&quot; dia.)','multi_field' => array('count')),
								array('name' => 'solar_tube_large_min_21_dia','label' => 'Solar Tube, Large (min. 21&quot; dia.)','multi_field' => array('count')),
								array('name' => 'electric_control_opens_sq.ft','label' => 'Electric Control (opens), sq.ft','multi_field' => array('sqft')),
								array('type' => 'divider/separator'),

								array('name' => 'luxury_blinds_opens_sqft','label' => 'Luxury (blinds, opens), sq.ft','multi_field' => array('sqft')),
								array('name' => 'moveable_roof_sqft','label' => 'Moveable Roof, sq.ft','multi_field' => array('sqft')),
								array('name' => 'stained_glass_sqft','label' => 'Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'Standard_does_not_open_sqft','label' => 'Standard (does not open), sq.ft','multi_field' => array('sqft')),
								array('name' => 'vented_dome_opens_sqft','label' => 'Vented Dome (opens), sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_walk_sqft','label' => 'Window&apos;s Walk, sq.ft','multi_field' => array('sqft')),
				                array('name' => 'skylights_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
							'id' => 'exterior_walls_subheading',
							'sub_label' => 'EXTERIOR WALLS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
				        'id' => 'exterior_walls_on_frame',
				        'label' => 'Exterior Walls (on Frame)',
				        'form_items' => array(
								array('name' => 'stucco_on_frame','label' => 'Stucco on Frame','multi_field' => array('percentage')),
								array('name' => 'stone_on_frame','label' => 'Stone on Frame','multi_field' => array('percentage')),
								array('name' => 'brick_on_frame_veneer','label' => 'Brick on Frame (veneer)','multi_field' => array('percentage')),
								array('name' => 'brick_custom_high_quality_veneer','label' => 'Brick, Custom (High quality, veneer)','multi_field' => array('percentage')),
								array('name' => 'siding_vinyl','label' => 'Siding, Vinyl','multi_field' => array('percentage')),
								array('name' => 'siding_wood','label' => 'Siding, Wood','multi_field' => array('percentage')),
								array('name' => 'siding_cement_fibre','label' => 'Siding, Cement Fibre (i.e. Hardiplank)','multi_field' => array('percentage')),
								array('name' => 'siding_aluminum','label' => 'Siding, Aluminum','multi_field' => array('percentage')),
								array('name' => 'shakes_wood','label' => 'Shakes, Wood','multi_field' => array('percentage')),
								array('name' => 'glass_block','label' => 'Glass Block','multi_field' => array('percentage')),
								array('name' => 'stucco_on_frame_custom','label' => 'Stucco on Frame, Custom','multi_field' => array('percentage')),
								array('name' => 'siding_wood_custom','label' => 'Siding, Wood, Custom','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'shingles_cement_fibre','label' => 'Shingles, Cement Fibre (i.e. Hardiplank)','multi_field' => array('percentage')),
								array('name' => 'logs_solid_logs_only_not_attached_to_frame','label' => 'Logs, Solid (logs only, not attached to Frame)','multi_field' => array('percentage')),
								array('name' => 'logs_siding_attached_to_frame','label' => 'Logs, Siding (attached to Frame)','multi_field' => array('percentage')),
								array('name' => 'siding_barn_plank','label' => 'Siding, Barn Plank','multi_field' => array('percentage')),
								array('name' => 'efis_on_frame','label' => 'EFIS on Frame','multi_field' => array('percentage')),
				                array('name' => 'exterior_walls_on_frame_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        ),
						'priority' => true
		);
		$field_sets[] = array(
				        'id' => 'exterior_walls_on_masonry',
				        'label' => 'Exterior Walls (on Masonry)',
				        'form_items' => array(
								array('name' => 'stucco_on_masonry','label' => 'Stucco on Masonry','multi_field' => array('percentage')),
								array('name' => 'concrete_poured_in_place_constructed_by_forms','label' => 'Concrete, Poured-in-Place (constructed by forms)','multi_field' => array('percentage')),
								array('name' => 'stone_on_masonry_veneer','label' => 'Stone on Masonry (veneer)','multi_field' => array('percentage')),
								array('name' => 'brick_on_masonry_veneer','label' => 'Brick on Masonry (veneer)','multi_field' => array('percentage')),
								array('name' => 'brick_custom_high_quality_veneer','label' => 'Brick, Custom (High quality, veneer)','multi_field' => array('percentage')),
								array('name' => 'brick_solid_solid_brick_wall','label' => 'Brick Solid (solid brick wall)','multi_field' => array('percentage')),
								array('name' => 'brick_solid_custom_high_quality_solid_brick_wall','label' => 'Brick Solid, Custom (High quality, solid brick wall)','multi_field' => array('percentage')),
								array('name' => 'glass_block','label' => 'Glass Block','multi_field' => array('percentage')),
								array('name' => 'stone_solid','label' => 'Stone Solid','multi_field' => array('percentage')),
								array('name' => 'siding_steel','label' => 'Siding, Steel','multi_field' => array('percentage')),
								array('name' => 'efis_on_masonry','label' => 'EFIS on Masonry','multi_field' => array('percentage')),  
				                array('name' => 'exterior_walls_on_masonry_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
							'id' => 'garages_and_carports_subheading',
							'sub_label' => 'GARAGES AND CARPORTS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
				        'id' => 'garages_and_carports',
				        'label' => 'Garages and Carports',
				        'form_items' => array(
								array('name' => 'no_garages_carports','label' => 'There are no Garages/Carports','type' => 'check'),
								array('name' => 'attached_garage_sqft','label' => 'Attached Garage, sq.ft','multi_field' => array('sqft')),
								array('name' => 'built_in_garage_sqft','label' => 'Built-in Garage, sq.ft','multi_field' => array('sqft')),
								array('name' => 'basement_garage_sqft','label' => 'Basement Garage, sq.ft','multi_field' => array('sqft')),
								array('name' => 'detached_garage_sqft','label' => 'Detached Garage, sq.ft','multi_field' => array('sqft')),
								array('name' => 'carport_sqft','label' => 'Carport, sq.ft','multi_field' => array('sqft')),
								array('name' => 'carport_sqft_detached','label' => 'Carport, sq.ft (Detached)','multi_field' => array('sqft')),
								array('name' => 'detached_garage_w_finished_area_sqft','label' => 'Detached Garage w/Finished Area, sq.ft','multi_field' => array('sqft')),
								array('name' => 'opener_garage_door','label' => 'Opener, Garage Door','multi_field' => array('count')),
								array('name' => 'garage_door_1_car_steel_sectional','label' => 'Garage Door, 1 Car, Steel Sectional','multi_field' => array('count')),
								array('name' => 'garage_door_2_car_steel_sectional','label' => 'Garage Door, 2 Car, Steel Sectional','multi_field' => array('count')),
								array('name' => 'detached_garage_w_living_area_adjacent_sq.ft','label' => 'Detached Garage, w/Living Area Adjacent, sq.ft','multi_field' => array('sqft')),
								array('name' => 'detached_garage_w_living_area_over_sq.ft','label' => 'Detached Garage, w/Living Area Over, sq.ft','multi_field' => array('sqft')),
								array('type' => 'divider/separator'),

								array('name' => 'cabinetry_garage_custom_sqft','label' => 'Cabinetry, Garage, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cabinetry_garage_standard_sqft','label' => 'Cabinetry, Garage, Standard, sq.ft','multi_field' => array('sqft')),
								array('name' => 'detached_garage_w_area_over_sqft','label' => 'Detached Garage, w/Area Over, sq.ft','multi_field' => array('sqft')),
								array('name' => 'garage_door_custom_wood_sqft','label' => 'Garage Door, Custom Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'garage_door_redwood_cedar_sqft','label' => 'Garage Door, Redwood/Cedar, sq.ft','multi_field' => array('sqft')),
								array('name' => 'garage_door_steel_sectional_sqft','label' => 'Garage Door, Steel sectional, sq.ft','multi_field' => array('sqft')),
								array('name' => 'garage_door_steel_swing_up_sqft','label' => 'Garage Door, Steel Swing-Up, sq.ft','multi_field' => array('sqft')),
								array('name' => 'garage_door_wood_sqft','label' => 'Garage Door, Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'lift_vehicle','label' => 'Lift Vehicle','multi_field' => array('count')),
				                array('name' => 'garages_and_carports_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        ),
						'priority' => true
		);
		$field_sets[] = array(
							'id' => 'porches_decks_breezeways_subheading',
							'sub_label' => 'PORCHES, DECKS, & BREEZEWAYS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
				        'id' => 'porches_decks_breezeways',
				        'label' => 'Porches, Decks & Breezeways',
				        'form_items' => array(
								array('name' => 'no_attached_structures_porches_decks','label' => 'There are no Attached Structures/Porches/Decks','type' => 'check'),
								array('name' => 'open_porch_sqft','label' => 'Open Porch, sq.ft','multi_field' => array('sqft')),
								array('name' => 'wood_deck_sqft','label' => 'Wood Deck, sq.ft','multi_field' => array('sqft')),
								array('name' => 'patio_cover_sqft','label' => 'Patio Cover, sq.ft','multi_field' => array('sqft')),
								array('name' => 'composite_deck_sqft','label' => 'Composite Deck, sq.ft','multi_field' => array('sqft')),
								array('name' => 'open_breezway_sqft','label' => 'Open Breezway, sq.ft','multi_field' => array('sqft')),
								array('name' => 'redwood_deck_sqft','label' => 'Redwood Deck, sq.ft','multi_field' => array('sqft')),
								array('name' => 'solar_room_sqft','label' => 'Solar Room, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_porch_sqft','label' => 'Enclosed Porch, sq.ft','multi_field' => array('sqft')),
								array('type' => 'divider/separator'),

								array('name' => 'screened_breezeway_sqft','label' => 'Screened Breezeway, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_breezeway_sqft','label' => 'Enclosed Breezeway, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_porch_custom_sqft','label' => 'Enclosed porch, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_breezeway_custom_sqft','label' => 'Enclosed Breezeway, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_breezeway_luxury_sqft','label' => 'Enclosed Breezeway, Luxury, sq.ft','multi_field' => array('sqft')),
								array('name' => 'enclosed_porch_luxury_sqft','label' => 'Enclosed Porch, Luxury, sq.ft','multi_field' => array('sqft')),
								array('name' => 'greenhouse_sqft','label' => 'Greenhouse, sq.ft','multi_field' => array('sqft')),
								array('name' => 'screened_porch_sqft','label' => 'Screened Porch, sq.ft','multi_field' => array('sqft')),
				                array('name' => 'porches_decks_breezeways_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
							'id' => 'exterior_summary_subheading',
							'sub_label' => 'EXTERIOR SUMMARY 1 of 2',
							'type' => 'subheading'
						);
		$field_sets[] = array(
							'id' => 'exterior_wall_conditions',
							'label' => 'Exterior Wall Conditions',
							'form_items' => array(
												array('name' => 'exterior_wall_conditions','label' => 'Exterior Wall Conditions','type' => 'multiselect','datasets' => self::$ewc_options),
												array('name' => 'exterior_wall_conditions_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
		);
		$field_sets[] = array(
							'id' => 'roof_surface_conditions',
							'label' => 'Roof Surface Conditions',
							'form_items' => array(
												array('name' => 'roof_surface_conditions','label' => 'Roof Surface Conditions','type' => 'multiselect','datasets' => self::$rsc_options),
												array('name' => 'roof_surface_conditions_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
		);
		$field_sets[] = array(
							'id' => 'roof_age',
							'label' => 'Roof Age',
							'form_items' => array(
												array('name' => 'roof_age','label' => 'Roof Age (Enter Calender Year)'),
												array('name' => 'roof_age_in_years','label' => 'Roof Age in Years(Enter how many Years old in Age)'),
												array('name' => 'roof_age_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
		);
		$field_sets[] = array(
							'id' => 'gutters_soffits',
							'label' => 'Gutters & Soffits',
							'form_items' => array(
												array('name' => 'gutters_soffits','label' => 'Gutters & Soffits','type' => 'multiselect','datasets' => self::$gs_options),
												array('name' => 'gutters_soffits_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
											)
		);
		$field_sets[] = array(
							'id' => 'exterior_extras_subheading',
							'sub_label' => 'EXTERIOR EXTRAS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
				        'id' => 'windows',
				        'label' => 'Windows',
				        'form_items' => array(
				                array('name' => 'sash_vinyl_with_glass','label' => 'Sash, Vinyl with Glass','multi_field' => array('percentage')),
								array('name' => 'aluminum_sash_with_glass','label' => 'Aluminum Sash with Glass','multi_field' => array('percentage')),
								array('name' => 'sash_wood_with_glass_standard','label' => 'Sash, Wood with Glass, Standard','multi_field' => array('percentage')),
								array('name' => 'sash_wood_with_glass_custom','label' => 'Sash, Wood with Glass, Custom','multi_field' => array('percentage')),
								array('name' => 'sash_wood_with_glass','label' => 'Sash, Wood with Glass','multi_field' => array('percentage')),
								array('name' => 'wood_metal_clad_with_glass','label' => 'Wood, Metal Clad with Glass','multi_field' => array('percentage')),
								array('name' => 'wood_vinyl_clad_with_glass','label' => 'Wood, Vinyl Clad with Glass','multi_field' => array('percentage')),
								array('name' => 'window_atrium','label' => 'Window, Atrium','multi_field' => array('count')),
								array('name' => 'window_bay','label' => 'Window, Bay','multi_field' => array('count')),
								array('name' => 'window_picture','label' => 'Window, Picture','multi_field' => array('count')),
								array('name' => 'transom_fixed','label' => 'Transom, Fixed','multi_field' => array('count')),
								array('name' => 'transome_half_round_fixed','label' => 'Transome, Half-Round, Fixed','multi_field' => array('count')),
								array('name' => 'transom_elipse_fixed','label' => 'Transom, Elipse, Fixed','multi_field' => array('count')),
								array('name' => 'transom_moveable','label' => 'Transom, Moveable','multi_field' => array('count')),
								array('name' => 'window_bow','label' => 'Window, Bow','multi_field' => array('count')),
								array('name' => 'window_half_round','label' => 'Window, Half Round','multi_field' => array('count')),
								array('name' => 'window_round','label' => 'Window, Round','multi_field' => array('count')),
								array('name' => 'window_garden','label' => 'Window, Garden','multi_field' => array('count')),
								array('name' => 'window_stained_glass_small','label' => 'Window, Stained Glass, Small','multi_field' => array('count')),
								array('name' => 'window_stained_glass_large','label' => 'Window, Stained Glass, Large','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'window_greenhouse','label' => 'Window, Greenhouse','multi_field' => array('count')),
								array('name' => 'window_palladium','label' => 'Window, Palladium','multi_field' => array('count')),
								array('name' => 'window_picture_insulated_glass','label' => 'Window, Picture, Insulated Glass','multi_field' => array('count')),
								array('name' => 'window_double_hung_6x6','label' => 'Window, Double Hung, 6&apos;x6&apos;','multi_field' => array('count')),
								array('name' => 'window_bow_insulated_glass','label' => 'Window, Bow, Insulated Glass','multi_field' => array('count')),
								array('name' => 'trim_window_ornate_lf','label' => 'Trim, Window, Ornate, LF','multi_field' => array('lf')),
								array('name' => 'awning_fabric_sqft','label' => 'Awning, Fabric, sq.ft','multi_field' => array('sqft')),
								array('name' => 'awning_retractable_motorized_sqft','label' => 'Awning, Retractable Motorized, sq.ft','multi_field' => array('sqft')),
								array('name' => 'transome_half_round_moveable','label' => 'Transome, Half-Round, Moveable','multi_field' => array('count')),
								array('name' => 'transom_elipse_moveable','label' => 'Transom, Elipse, Moveable','multi_field' => array('count')),
								array('name' => 'awning_acrylic_sqft','label' => 'Awning, Acrylic, sq.ft','multi_field' => array('sqft')),
								array('name' => 'awning_retractable_sqft','label' => 'Awning, Retractable, sq.ft','multi_field' => array('sqft')),
								array('name' => 'shutters_exterior','label' => 'Shutters, Exterior','multi_field' => array('count')),
								array('name' => 'storm_windows_and_doors','label' => 'Storm Windows and Doors','multi_field' => array('percentage')),
								array('name' => 'screens','label' => 'Screens','multi_field' => array('percentage')),
								array('name' => 'awning_aluminum_sqft','label' => 'Awning, Aluminum, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_double_hung_3x4','label' => 'Window, Double Hung, 3&apos;x4&apos;','multi_field' => array('count')),
								array('name' => 'Window_Double_Hung_3x5','label' => 'Window, Double Hung, 3&apos;x5&apos;','multi_field' => array('count')),
								array('name' => 'sash_only_stone','label' => 'Sash Only, Stone','multi_field' => array('percentage')),
								array('name' => 'sash_only_stone_carved','label' => 'Sash Only, Stone, Carved','multi_field' => array('percentage')),
								array('name' => 'shutter_exterior_storm','label' => 'Shutter, Exterior  Storm','multi_field' => array('count')),
								array('name' => 'shutter_storm_proof_automatic','label' => 'Shutter, Storm Proof, Automatic','multi_field' => array('count')),
								array('name' => 'combination_screen_storm','label' => 'Combination Screen/Storm','multi_field' => array('percentage')),
								array('name' => 'Awning_Fibreglass_sqft','label' => 'Awning, Fibreglass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'bronze_with_glass','label' => 'Bronze with Glass','multi_field' => array('percentage')),
								array('name' => 'window_basement_egress_with_stairs','label' => 'Window, Basement Egress, with Stairs','multi_field' => array('count')),
				                array('name' => 'windows_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'exterior_doors',
				        'label' => 'Doors',
				        'form_items' => array(
				                array('name' => 'door_wood','label' => 'Door, Wood','multi_field' => array('count')),
								array('name' => 'door_french','label' => 'Door, French','multi_field' => array('count')),
								array('name' => 'door_sliding_glass','label' => 'Door, Sliding Glass','multi_field' => array('count')),
								array('name' => 'door_steel','label' => 'Door, Steel','multi_field' => array('count')),
								array('name' => 'door_atrium_with_sidelights','label' => 'Door, Atrium with Sidelights','multi_field' => array('count')),
								array('name' => 'door_atrium','label' => 'Door, Atrium','multi_field' => array('count')),
								array('name' => 'door_fibreglass','label' => 'Door, Fibreglass','multi_field' => array('count')),
								array('name' => 'door_wood_walnut_w_sidelights','label' => 'Door, Wood, Walnut, w/Sidelights','multi_field' => array('count')),
								array('name' => 'door_wood_teak_w_sidelights','label' => 'Door, Wood, Teak, w/Sidelights','multi_field' => array('count')),
								array('name' => 'door_sliding_glass_plate_glass','label' => 'Door, Sliding Glass, Plate Glass','multi_field' => array('count')),
								array('name' => 'door_wood_dutch','label' => 'Door, Wood, Dutch','multi_field' => array('count')),
								array('name' => 'door_wood_carved','label' => 'Door, Wood, Carved','multi_field' => array('count')),
								array('name' => 'door_sliding_glass_insulated_glass','label' => 'Door, Sliding Glass, insulated Glass','multi_field' => array('count')),
								array('name' => 'door_wood_mahogany','label' => 'Door, Wood, Mahogany','multi_field' => array('count')),
								array('name' => 'door_wood_teak','label' => 'Door, Wood, Teak','multi_field' => array('count')),
								array('name' => 'door_wood_walnut','label' => 'Door, Wood, Walnut','multi_field' => array('count')),
								array('type' => 'divider/separator'),

								array('name' => 'door_fibreglass_w_belved_glass','label' => 'Door, Fibreglass, w/Belved Glass','multi_field' => array('count')),
								array('name' => 'door_fibreglass_w_crystal_glass','label' => 'Door, Fibreglass, w/Crystal Glass','multi_field' => array('count')),
								array('name' => 'door_hollow_metal','label' => 'Door, Hollow Metal','multi_field' => array('count')),
								array('name' => 'door_fibreglass_w_stained_glass','label' => 'Door, Fibreglass, w/Stained Glass','multi_field' => array('count')),
								array('name' => 'door_wood_mahogany_custom','label' => 'Door, Wood, Mahogany, Custom','multi_field' => array('count')),
								array('name' => 'door_wood_white_ash_w_beveled_glass','label' => 'Door, Wood, White Ash, w/Beveled Glass','multi_field' => array('count')),
								array('name' => 'door_stainless_steel','label' => 'Door, Stainless Steel','multi_field' => array('count')),
								array('name' => 'door_wood_teak_w_beveled_glass','label' => 'Door, Wood, Teak, w/Beveled Glass','multi_field' => array('count')),
								array('name' => 'door_wood_teak_w_stained_glass','label' => 'Door, Wood, Teak, w/Stained Glass','multi_field' => array('count')),
								array('name' => 'door_wood_teak_w_crystal_glass','label' => 'Door, Wood, Teak, w/Crystal Glass','multi_field' => array('count')),
								array('name' => 'door_wood_walnut_w_beveled_glass','label' => 'Door, Wood, Walnut, w/Beveled Glass','multi_field' => array('count')),
								array('name' => 'door_wood_walnut_w_stained_glass','label' => 'Door, Wood, Walnut, w/Stained Glass','multi_field' => array('count')),
								array('name' => 'door_wood_white_ash_w_crystal_glass','label' => 'Door, Wood, White Ash, w/Crystal Glass','multi_field' => array('count')),
				                array('name' => 'doors_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'doors_windows_sqft_oversized',
				        'label' => 'Doors + Windows sq.ft. (Oversized)',
				        'form_items' => array(
				                array('name' => 'door_french_sqft','label' => 'Door, French, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_patio_sliding_glass_insulated_sqft','label' => 'Door, Patio, Sliding Glass insulated, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_atrium_sqft','label' => 'Door, Atrium, sq.ft','multi_field' => array('sqft')),
								array('name' => 'wall_folding_glass_pocket_sqft','label' => 'Wall, Folding, Glass/Pocket, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_sqft','label' => 'Door, Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_atrium_with_sidelights_sqft','label' => 'Door, Atrium with Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_patio_sliding_glass_plate_glass_sqft','label' => 'Door, Patio, Sliding Glass, Plate Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_w_sidelights_sqft','label' => 'Door, Wood, w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_picture_wood_sqft','label' => 'Window, Picture, Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_sqft','label' => 'Door, Wood, Mahogany, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_bow_picture_plate_glass_sqft','label' => 'Window, Bow/Picture, Plate Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'shutters_security_rolling_sqft','label' => 'Shutters, Security, Rolling, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_teak_w_sidelights_sqft','label' => 'Door, Wood, Teak w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_walnut_w_sidelights_sqft','label' => 'Door, Wood, Walnut w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'stained_glass_sqft','label' => 'Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_palladian_sqft','label' => 'Window, Palladian, sq.ft','multi_field' => array('sqft')),
								array('type' => 'divider/separator'),

								array('name' => 'door_wood_w_sidelights_sqft','label' => 'Door, Wood, w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_teak_sqft','label' => 'Door, Wood, Teak, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_walnut_sqft','label' => 'Door, Wood, Walnut, sq.ft','multi_field' => array('sqft')),
								array('name' => 'stained_glass_overlay_sqft','label' => 'Stained Glass, Overlay, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_atrium_sqft','label' => 'Window, Atrium, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_leaded_glass_sqft','label' => 'Window, Leaded Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_custom_sqft','label' => 'Door, Wood, Mahogany, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_bow_picture_insultated_glass_sqft','label' => 'Window, Bow/Picture, Insultated Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_w_plate_glass_sqft','label' => 'Door, Wood, w/Plate Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_w_beveled_glass_sqft','label' => 'Door, Wood, w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_french_with_built_in_cloth_shade_sqft','label' => 'Door, French with Built-in Cloth Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_french_with_built_in_louvered_shade_sqft','label' => 'Door, French with Built-in Louvered Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_w_stained_glass_sqft','label' => 'Door, Wood, w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_walnut_w_stained_glass_sqft','label' => 'Door, Wood, Walnut w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'entrance_ornate_sqft','label' => 'Entrance, Ornate, sq.ft','multi_field' => array('sqft')),
								array('name' => 'shade_sqft','label' => 'Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_picture_wood_insultated_sqft','label' => 'Window, Picture, Wood, Insultated, sq.ft','multi_field' => array('sqft')),
								array('name' => 'solar_glass_sqft','label' => 'Solar Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'vitrolite_glass','label' => 'Vitrolite Glass','multi_field' => array('sqft')),
								array('name' => 'window_fixed_insulated_glass_w_built_in_cloth_shade_sqft','label' => 'Window, Fixed, Insulated Glass, w/Built-in Cloth Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'window_fixed_insulated_glass_w_built_in_louver_shade_sqft','label' => 'Window, Fixed, Insulated Glass, w/Built-in Louver Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_white_ash_sqft','label' => 'Door, Wood, White Ash, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_white_ash_w_beveled_glass_sqft','label' => 'Door, Wood, White Ash w/ Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_white_ash_w_crystal_glass_sqft','label' => 'Door, Wood, White Ash w/ Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_white_ash_w_sidelights_sqft','label' => 'Door, Wood, White Ash w/ Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_white_ash_w_stained_glass_sqft','label' => 'Door, Wood, White Ash w/ Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_walnut_w_beveled_glass_sqft','label' => 'Door, Wood, Walnut w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_walnut_w_crystal_glass_sqft','label' => 'Door, Wood, Walnut w/Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_teak_w_stained_glass_sqft','label' => 'Door, Wood, Teak w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_teak_w_crystal_glass_sqft','label' => 'Door, Wood, Teak w/Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_teak_w_beveled_glass_sqft','label' => 'Door, Wood, Teak w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_custom_w_stained_glass_sqft','label' => 'Door, Wood, Mahogany, Custom w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_custom_w_sidelights_sqft','label' => 'Door, Wood, Mahogany, Custom w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_custom_w_beveled_glass_sqft','label' => 'Door, Wood, Mahogany, Custom w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_patio_sliding_insulated_glass_w_built_in_cloth_shade_sqft','label' => 'Door, Patio, Sliding Insulated Glass w/Built-in Cloth Shade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_w_crystal_glass_sqft','label' => 'Door, Wood, Mahogany w/Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_patio_sliding_insulated_glass_w_built_in_louvered_sqft','label' => 'Door, Patio, Sliding Insulated Glass w/Built-in Louvered, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_custom_sqft','label' => 'Door, Wood, Carved, Custom, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_w_beveled_glass_sqft','label' => 'Door, Wood, Carved, w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_w_crystal_glass_sqft','label' => 'Door, Wood, Carved, w/Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_w_sidelights_sqft','label' => 'Door, Wood, Carved, w/Sidelights, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_w_stained_glass_sqft','label' => 'Door, Wood, Carved, w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_carved_w_stained_glass_sqft','label' => 'Door, Wood, Carved, w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_custom_mahogany_w_crystal_glass_sqft','label' => 'Door, Wood, Custom Mahogany w/Crystal Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_dutch_sqft','label' => 'Door, Wood, Dutch, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_w_beveled_glass_sqft','label' => 'Door, Wood, Mahogany w/Beveled Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'door_wood_mahogany_w_stained_glass_sqft','label' => 'Door, Wood, Mahogany w/Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'aluminum_louver_sqft','label' => 'Aluminum Louver, sq.ft','multi_field' => array('sqft')),
				                array('name' => 'doors_windows_sqft_oversized_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'roof_extras',
				        'label' => 'Roof Extras',
				        'form_items' => array(
								array('name' => 'no_roof_extras','label' => 'There are no Roof Extras','type' => 'check'),
								array('name' => 'dormer_gable_lf','label' => 'Dormer, Gable, LF','multi_field' => array('lf')),
								array('name' => 'dormer_pediment_lf','label' => 'Dormer, Pediment, LF','multi_field' => array('lf')),
								array('name' => 'dormer_shed_lf','label' => 'Dormer, Shed, LF','multi_field' => array('lf')),
								array('name' => 'dormer_wall_lf','label' => 'Dormer, Wall, LF','multi_field' => array('lf')),
								array('name' => 'attic_finished_sqft','label' => 'Attic, Finished, sq.ft','multi_field' => array('sqft')),
								array('name' => 'gutters_downspouts_copper','label' => 'Gutters/Downspouts, Copper','multi_field' => array('percentage')),
								array('name' => 'cornice_lf','label' => 'Cornice, LF','multi_field' => array('lf')),
								array('name' => 'cupola','label' => 'Cupola','multi_field' => array('count')),
								array('name' => 'dormer_arched_top_lf','label' => 'Dormer, Arched top, LF','multi_field' => array('lf')),
								array('type' => 'divider/separator'),

								array('name' => 'ceiling_domes_stained_glass_sqft','label' => 'Ceiling Domes, Stained Glass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'cornice_brick_lf','label' => 'Cornice, Brick, LF','multi_field' => array('lf')),
								array('name' => 'cornice_stone_lf','label' => 'Cornice, Stone, LF','multi_field' => array('lf')),
								array('name' => 'dormer_eyebrow_lf','label' => 'Dormer, Eyebrow, LF','multi_field' => array('lf')),
								array('name' => 'dormer_oval_lf','label' => 'Dormer, Oval, LF','multi_field' => array('lf')),
								array('name' => 'dormer_round_lf','label' => 'Dormer, Round, LF','multi_field' => array('lf')),
								array('name' => 'dormer_shaped_lf','label' => 'Dormer, Shaped, LF','multi_field' => array('lf')),
								array('name' => 'flashing_copper','label' => 'Flashing, Copper','multi_field' => array('percentage')),
								array('name' => 'system_radiant_gutter_dnspout_melt_lf','label' => 'System, Radiant Gutter & DnSpout Melt, LF','multi_field' => array('lf')),
								array('name' => 'system_radiant_roof_melt_sqft','label' => 'System, Radiant Roof Melt, sq.ft','multi_field' => array('sqft')),
				                array('name' => 'roof_extras_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'wall_extras',
				        'label' => 'Wall Extras',
				        'form_items' => array(
								array('name' => 'no_exterior_wall_extras','label' => 'There are no Exterior Wall Extras','type' => 'check'),
								array('name' => 'trim_molding_wood_lf','label' => 'Trim/Molding, Wood, LF','multi_field' => array('lf')),
								array('name' => 'window_wall','label' => 'Window Wall','multi_field' => array('percentage')),
								array('name' => 'door_folding_glass_pocket_sqft','label' => 'Door, Folding Glass/Pocket, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fire_escape','label' => 'Fire Escape','multi_field' => array('count')),
								array('name' => 'breakaway_wall_lattice_on_frame_sqft','label' => 'Breakaway Wall, Lattice on Frame, sq.ft','multi_field' => array('sqft')),
								array('name' => 'coping_lf','label' => 'Coping, LF','multi_field' => array('lf')),
								array('name' => 'skirting_hillside','label' => 'Skirting, Hillside','multi_field' => array('sqft')),
								array('name' => 'stain_only','label' => 'Stain Only','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'stuccco_eifs_panel_relief_sqft','label' => 'Stuccco, (EIFS) Panel Relief, sq.ft','multi_field' => array('sqft')),
								array('name' => 'stucco_finish_stay_in_place_forming','label' => 'Stucco Finish, Stay-in-Place Forming','multi_field' => array('percentage')),
								array('name' => 'shutters_storm_security_rollup_sqft','label' => 'Shutters, Storm/Security, Rollup, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pictorial_artwork_wood_sqft','label' => 'Pictorial Artwork, Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'paint_only','label' => 'Paint Only','multi_field' => array('percentage')),
								array('name' => 'trim_molding_stone_lf','label' => 'Trim/Molding, Stone, LF','multi_field' => array('lf')),
								array('name' => 'trim_molding_masonry_lf','label' => 'Trim/Molding, Masonry, LF ','multi_field' => array('lf')),
								array('name' => 'tile_ceramic_mosiac_sqft','label' => 'Tile, Ceramic, Mosiac, sq.ft','multi_field' => array('sqft')),
								array('name' => 'skirting_masonry_sqft','label' => 'Skirting, Masonry, sq.ft','multi_field' => array('sqft')),
								array('name' => 'skirting_stucco_on_frame_sqft','label' => 'Skirting, Stucco on Frame, sq.ft','multi_field' => array('sqft')),
								array('name' => 'trim_molding_gingerbread_18','label' => 'Trim/Molding, Gingerbread, 18&quot;','multi_field' => array('percentage')),
								array('name' => 'trim_molding_gingerbread_18_lf','label' => 'Trim/Molding, Gingerbread, 18&quot;, LF ','multi_field' => array('lf')),
								array('name' => 'trim_molding_gingerbread_24','label' => 'Trim/Molding, Gingerbread, 24&quot;','multi_field' => array('percentage')),
								array('name' => 'trim_molding_gingerbread_24_lf','label' => 'Trim/Molding, Gingerbread, 24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'trim_molding_gingerbread_36','label' => 'Trim/Molding, Gingerbread, 36&quot;','multi_field' => array('percentage')),
				                array('name' => 'wall_extras_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'balconies_and_columns',
				        'label' => 'Balconies and Columns',
				        'form_items' => array(
								array('name' => 'no_balconies_and_columns','label' => 'There are no Balconies or Columns','type' => 'check'),
								array('name' => 'balcony_sqft','label' => 'Balcony, sq.ft','multi_field' => array('sqft')),
								array('name' => 'balcony_wood_w_wood_rails_sqft','label' => 'Balcony, Wood w/Wood Rails, sq.ft','multi_field' => array('sqft')),
								array('name' => 'balcony_cement_w_glass_rails_sqft ','label' => 'Balcony, Cement w/Glass Rails, sq.ft ','multi_field' => array('sqft')),
								array('name' => 'column_wood_lf','label' => 'Column, Wood, LF','multi_field' => array('lf')),
								array('name' => 'column_stone_lf','label' => 'Column, Stone, LF','multi_field' => array('lf')),
								array('name' => 'column_brick_lf','label' => 'Column, Brick, LF ','multi_field' => array('lf')),
								array('name' => 'column_concrete_17_20_lf ','label' => 'Column, Concrete, 17&quot; -20&quot;, LF ','multi_field' => array('lf')),
								array('type' => 'divider/separator'),

								array('name' => 'balcony_cement_w_ornamental_iron_rails_sqft','label' => 'Balcony, Cement w/Ornamental Iron Rails, sq.ft','multi_field' => array('sqft')),
								array('name' => 'balcony_cement_w_ornamental_stone_balustrade_sqft','label' => 'Balcony, Cement w/Ornamental Stone Balustrade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'balcony_large','label' => 'Balcony, Large','multi_field' => array('count')),
								array('name' => 'balcony_medium','label' => 'Balcony, Medium','multi_field' => array('count')),
								array('name' => 'balcony_small','label' => 'Balcony, Small ','multi_field' => array('count')),
								array('name' => 'balcony_wd_w_ornamental_iron_rails_sq_ft','label' => 'Balcony Wd w/Ornamental Iron Rails, sq.ft','multi_field' => array('sqft')),
								array('name' => 'balcony_cement_w_ornamental_stone_balustrade_sqft','label' => 'Balcony, Cement w/Ornamental Stone Balustrade, sq.ft','multi_field' => array('sqft')),
								array('name' => 'column_aluminum_12_and_under_lf','label' => 'Column, Aluminum, 12&quot; and Under, LF ','multi_field' => array('lf')),
								array('name' => 'column_aluminum_13_24_lf','label' => 'Column, Aluminum, 13&quot;-24&quot;, LF ','multi_field' => array('lf')),
								array('name' => 'column_aluminum_25_and_over_lf','label' => 'Column, Aluminum, 25&quot; and Over, LF','multi_field' => array('lf')),
								array('name' => 'coumn_aluminum_ornamental_lf','label' => 'Coumn, Aluminum, Ornamental, LF','multi_field' => array('lf')),
								array('name' => 'column_concrete_12_and_under_lf','label' => 'Column, Concrete, 12&quot; and Under, LF ','multi_field' => array('lf')),
								array('name' => 'column_concrete_13_16_lf','label' => 'Column, Concrete, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_concrete_21_24_lf','label' => 'Column, Concrete, 21&quot;-24&quot;, LF ','multi_field' => array('lf')),
								array('name' => 'column_concrete_25_and_over_lf','label' => 'Column, Concrete, 25&quot; and Over, LF','multi_field' => array('lf')),
								array('name' => 'column_concrete_ornamental_lf','label' => 'Column, Concrete, Ornamental, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_12_and_under_lf','label' => 'Column, Granite, 12&quot; and Under, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_13_16_lf','label' => 'Column, Granite, 13&quot;-16&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_17_20_lf','label' => 'Column, Granite, 17&quot;-20&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_11_24_lf','label' => 'Column, Granite, 21&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_25_and_over_lf','label' => 'Column, Granite, 25&quot; and Over, LF','multi_field' => array('lf')),
								array('name' => 'column_granite_ornamental_lf','label' => 'Column, Granite, Ornamental, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_stave_12_and_under_lf','label' => 'Column, Wood Stave, 12&quot; and Under, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_stave_13_24_lf','label' => 'Column, Wood Stave, 13&quot;-24&quot;, LF','multi_field' => array('lf')),
								array('name' => 'column_wood_stave_25_and_over_lf','label' => 'Column, Wood Stave, 25&quot; and Over, LF','multi_field' => array('lf')),
				                array('name' => 'balconies_and_columns_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        ),
						'priority' => true
		);
		$field_sets[] = array(
				        'id' => 'other_attached_structures',
				        'label' => 'Other Attached Structures',
				        'form_items' => array(
								array('name' => 'no_other_attached_structures','label' => 'There are no Other Attached Items/Pool/Structures','type' => 'check'),
								array('name' => 'swimming_pool_concrete_large_attached','label' => 'Swimming Pool, Concrete, Large, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_concrete_medium_attached','label' => 'Swimming Pool, Concrete, Medium, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_concrete_small_attached','label' => 'Swimming Pool, Concrete, Small, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_concrete_extra_large_attached','label' => 'Swimming Pool, Concrete, Extra Large, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_fiberglass_attached','label' => 'Swimming Pool, Fiberglass, Attached','multi_field' => array('count')),
								array('name' => 'pergola_wood_sqft','label' => 'Pergola, Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'fire_pit_outdoor_attached','label' => 'Fire Pit, Outdoor (Attached)','multi_field' => array('count')),
								array('name' => 'retaining_wall_poured_concrete_sqft','label' => 'Retaining Wall, Poured Concrete, sq.ft ','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_block','label' => 'Retaining Wall, Block','multi_field' => array('sqft')),
								array('name' => 'water_tank_steel_medium','label' => 'Water Tank, Steel, Medium','multi_field' => array('count')),
								array('name' => 'water_tank_poly_medium','label' => 'Water Tank, Poly, Medium','multi_field' => array('count')),
				                array('name' => 'storage_area_sqft','label' => 'Attached/Built-in Storage Area , sq.ft','multi_field' => array('sqft')),
								array('type' => 'divider/separator'),

								array('name' => 'access_ramp_wood_ada_compliant_lf','label' => 'Access Ramp, Wood, ADA Compliant, LF','multi_field' => array('lf')),
								array('name' => 'attached_barn_sqft','label' => 'Attached Barn, sq.ft','multi_field' => array('sqft')),
								array('name' => 'attached_barn_vintage_sqft','label' => 'Attached Barn, Vintage, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pergola_fiberglass_sqft','label' => 'Pergola, Fiberglass, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pergola_vinyl_sqft','label' => 'Pergola, Vinyl, sq.ft','multi_field' => array('sqft')),
								array('name' => 'pool_heater_solar_extra_large','label' => 'Pool Heater, Solar, Extra Large','multi_field' => array('count')),
								array('name' => 'pool_heater_solar_large','label' => 'Pool Heater, Solar, Large','multi_field' => array('count')),
								array('name' => 'pool_heater_solar_medium','label' => 'Pool Heater, Solar, Medium','multi_field' => array('count')),
								array('name' => 'pool_heater_solar_small','label' => 'Pool Heater, Solar, Small','multi_field' => array('count')),
								array('name' => 'retaining_wall_brick_block_sqft','label' => 'Retaining Wall, Brick & Block, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_brick_sqft','label' => 'Retaining Wall, Brick, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_fieldstone_sqft','label' => 'Retaining Wall, Fieldstone, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_gabion_sqft','label' => 'Retaining Wall, Gabion, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_railroad_tile_sqft','label' => 'Retaining Wall, Railroad Tile, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_stacked_boulder_sqft','label' => 'Retaining Wall, Stacked Boulder, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_stone_veneer_sqft','label' => 'Retaining Wall, Stone Veneer, sq.ft','multi_field' => array('sqft')),
								array('name' => 'retaining_wall_timber_wood_sqft','label' => 'Retaining Wall, Timber/Wood, sq.ft','multi_field' => array('sqft')),
								array('name' => 'screened_pool_enclosure_sqft','label' => 'Screened Pool Enclosure, sq.ft','multi_field' => array('sqft')),
								array('name' => 'screened_pool_enclosure_custom_attached_sqft','label' => 'Screened pool Enclosure, Custom (Attached), sq.ft','multi_field' => array('sqft')),
								array('name' => 'screened_pool_enclosure_deluxe_attached_sqft','label' => 'Screened Pool Enclosure, Deluxe (Attached), sq.ft','multi_field' => array('sqft')),
								array('name' => 'storm_shelter_concrete_above_ground_sqft','label' => 'Storm Shelter, Concrete, Above Ground, sq.ft','multi_field' => array('sqft')),
								array('name' => 'storm_shelter_concrete_below_ground_sqft','label' => 'Storm Shelter, Concrete, Below Ground, sq.ft','multi_field' => array('sqft')),
								array('name' => 'storm_shelter_fiberglass_below_ground_sqft','label' => 'Storm Shelter, Fiberglass, Below Ground, sq.ft','multi_field' => array('sqft')),
								array('name' => 'storm_shelter_steel_above_ground_sqft','label' => 'Storm Shelter, Steel, Above Ground, sq.ft','multi_field' => array('sqft')),
								array('name' => 'storm_shelter_steel_below_ground_sqft','label' => 'Storm Shelter, Steel, Below Ground, sq.ft ','multi_field' => array('sqft')),
								array('name' => 'swimming_pool_above_grnd_extra_large_attached','label' => 'Swimming Pool, Above Grnd, Extra Large, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_above_grnd_large_attached','label' => 'Swimming Pool, Above Grnd, Large, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_above_grnd_medium_attached','label' => 'Swimming Pool, Above Grnd, Medium, Attached','multi_field' => array('count')),
								array('name' => 'swimming_pool_above_grnd_small_attached','label' => 'Swimming Pool, Above Grnd, Small, Attached','multi_field' => array('count')),
								array('name' => 'water_tank_concrete_extra_large','label' => 'Water Tank, Concrete, Extra-Large','multi_field' => array('count')),
								array('name' => 'water_tank_concrete_large','label' => 'Water Tank, Concrete, Large','multi_field' => array('count')),
								array('name' => 'water_tank_concrete_medium','label' => 'Water Tank, Concrete, Medium','multi_field' => array('count')),
								array('name' => 'water_tank_concrete_small','label' => 'Water Tank, Concrete, Small','multi_field' => array('count')),
								array('name' => 'water_tank_poly_extra_large','label' => 'Water Tank, Poly, Extra-Large','multi_field' => array('count')),
								array('name' => 'water_tank_poly_large','label' => 'Water Tank, Poly, Large','multi_field' => array('count')),
								array('name' => 'water_tank_poly_small','label' => 'Water Tank, Poly, Small','multi_field' => array('count')),
								array('name' => 'water_tank_steel_extra_large','label' => 'Water Tank, Steel, Extra-Large','multi_field' => array('count')),
								array('name' => 'water_tank_steel_large','label' => 'Water Tank, Steel, Large','multi_field' => array('count')),
								array('name' => 'water_tank_steel_small','label' => 'Water Tank, Steel, Small','multi_field' => array('count')),
				                array('name' => 'other_attached_structures_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
							'id' => 'construction_details_subheading',
							'sub_label' => 'CONSTRUCTION DETAILS',
							'type' => 'subheading'
						);
		$field_sets[] = array(
				        'id' => 'exterior_wall_framing',
				        'label' => 'Exterior Wall framing',
				        'form_items' => array(
				                array('name' => 'stud_2x6','label' => 'Stud, 2&quot; X 6&quot;','multi_field' => array('percentage')),
								array('name' => 'stud_2x4','label' => 'Stud, 2&quot; X 4&quot;','multi_field' => array('percentage')),
								array('name' => 'concrete_forms_stay_in_place','label' => 'Concrete Forms, Stay-in-Place','multi_field' => array('percentage')),
								array('name' => 'post_and_beam','label' => 'Post and Beam','multi_field' => array('percentage')),
								array('name' => 'structural_insulated_panels','label' => 'Structural Insulated Panels','multi_field' => array('percentage')),
								array('name' => 'stud_steel','label' => 'Stud Steel','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),


								array('name' => 'framing_rough_lumber','label' => 'Framing, Rough Lumber','multi_field' => array('percentage')),
								array('name' => 'framing_rough_lumber_2x6','label' => 'Framing, Rough Lumber, 2 X 6','multi_field' => array('percentage')),
								array('name' => 'stud_2x10','label' => 'Stud, 2&quot; X 10&quot;','multi_field' => array('percentage')),
								array('name' => 'stud_2x12','label' => 'Stud, 2&quot; X 12&quot;','multi_field' => array('percentage')),
								array('name' => 'stud_2x8','label' => 'Stud, 2&quot; X 8&quot;','multi_field' => array('percentage')),
				                array('name' => 'exterior_wall_framing_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'interior_wall_framing',
				        'label' => 'Interior Wall framing',
				        'form_items' => array(
				                array('name' => 'stud_2x4','label' => 'Stud, 2&quot; X 4&quot;','multi_field' => array('percentage')),
								array('name' => 'stud_2x6','label' => 'Stud, 2&quot; X 6&quot;','multi_field' => array('percentage')),
								array('name' => 'stud_steel','label' => 'Stud, Steel','multi_field' => array('percentage')),
								array('name' => 'post_and_beam','label' => 'Post and Beam','multi_field' => array('percentage')),
								array('name' => 'framing_rough_lumber','label' => 'Framing, Rough Lumber','multi_field' => array('percentage')),
								array('name' => 'concrete_w_drywall_poured_4_to_6','label' => 'Concrete w/Drywall, Poured, 4&quot; to 6&quot;','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'block','label' => 'Block','multi_field' => array('percentage')),
								array('name' => 'brick_block','label' => 'Brick & Block','multi_field' => array('percentage')),
								array('name' => 'concrete_poured_4_to_6','label' => 'Concrete, Poured, 4&quot; to 6&quot;','multi_field' => array('percentage')),
								array('name' => 'solid_brick','label' => 'Solid Brick','multi_field' => array('percentage')),
								array('name' => 'solid_stone','label' => 'Solid Stone','multi_field' => array('percentage')),
								array('name' => 'solid_block','label' => 'Solid & Block','multi_field' => array('percentage')),
								array('name' => 'stucco_on_masonry','label' => 'Stucco on Masonry','multi_field' => array('percentage')),
								array('name' => 'stud_2x8','label' => 'Stud, 2&quot; X 8&quot;','multi_field' => array('percentage')),
				                array('name' => 'interior_wall_framing_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'superstructure_framing',
				        'label' => 'Superstructure Framing',
				        'form_items' => array(
				                array('name' => 'framing_bearing_wall','label' => 'Framing, Bearing Wall','multi_field' => array('percentage')),
								array('name' => 'framing_steel','label' => 'Framing, Steel','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'framing_mill_timber','label' => 'Framing, Mill Timber','multi_field' => array('percentage')),
								array('name' => 'framing_rough_lumber','label' => 'Framing Rough Lumber','multi_field' => array('percentage')),
								array('name' => 'framing_steel_with_wood_beams','label' => 'Framing, Steel with Wood Beams','multi_field' => array('percentage')),
				                array('name' => 'superstructure_framing_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'roof_structure',
				        'label' => 'Roof Structure',
				        'form_items' => array(
				                array('name' => 'wood_trusses_sheathing','label' => 'Wood Trusses & Sheathing','multi_field' => array('percentage')),
								array('name' => 'rafters_wood_with_sheathing','label' => 'Rafters, Wood with Sheathing','multi_field' => array('percentage')),
								array('name' => 'beams_wood','label' => 'Beams, Wood','multi_field' => array('percentage')),
								array('name' => 'bar_joists_with_wood_sheathing','label' => 'Bar Joists with Wood Sheathing','multi_field' => array('percentage')),
								array('name' => 'concrete_cast_in_place','label' => 'Concrete, Cast-in-Place','multi_field' => array('percentage')),
								array('name' => 'steel_joist_concrete_slab','label' => 'Steel Joist, Concrete Slab','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'bar_joists_with_metal_deck','label' => 'Bar Joists with Metal Deck','multi_field' => array('percentage')),
								array('name' => 'concrete_plank_precast','label' => 'Concrete Plank, Precast','multi_field' => array('percentage')),
								array('name' => 'framing_rough_lumber','label' => 'Framing, Rough Lumber','multi_field' => array('percentage')),
								array('name' => 'roof_moveable','label' => 'Roof, Moveable','multi_field' => array('percentage')),
								array('name' => 'steel_joists_precast_deck','label' => 'Steel Joists, Precast Deck','multi_field' => array('percentage')),
								array('name' => 'steel_joists_wood_sheathing','label' => 'Steel Joists, Wood Sheathing','multi_field' => array('percentage')),
								array('name' => 'wood_trusses','label' => 'Wood Trusses','multi_field' => array('percentage')),
				                array('name' => 'roof_structure_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'floor_ceiling_structure',
				        'label' => 'Floor/Ceiling Structure',
				        'form_items' => array(
				                array('name' => 'wood_joists_sheathing','label' => 'Wood Joists & Sheathing','multi_field' => array('percentage')),
								array('name' => 'steel_joists_wood_sheathing','label' => 'Steel Joists, Wood Sheathing','multi_field' => array('percentage')),
								array('name' => 'bar_joists_with_wood_sheathing','label' => 'Bar Joists with Wood Sheathing','multi_field' => array('percentage')),
								array('name' => 'framing_post_beam','label' => 'Framing, Post & Beam','multi_field' => array('percentage')),
								array('name' => 'concrete_precast_beams_plank','label' => 'Concrete, Precast Beams & Plank','multi_field' => array('percentage')),
								array('name' => 'concrete','label' => 'Concrete','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'concrete_plank_precast','label' => 'Concrete Plank, Precast','multi_field' => array('percentage')),
								array('name' => 'cantilever_precast_concrete','label' => 'Cantilever, Precast Concrete','multi_field' => array('percentage')),
								array('name' => 'cantilever_steel_bar_joist','label' => 'Cantilever, Steel Bar Joist','multi_field' => array('percentage')),
								array('name' => 'cantilever_wood_joist','label' => 'Cantilever, Wood Joist','multi_field' => array('percentage')),
								array('name' => 'framing_rough_lumber','label' => 'Framing, Rough Lumber','multi_field' => array('percentage')),
								array('name' => 'glass_tempered','label' => 'Glass, Tempered','multi_field' => array('percentage')),
								array('name' => 'gypsum_poured','label' => 'Gypsum, Poured','multi_field' => array('percentage')),
								array('name' => 'steel_joists_flat_slab','label' => 'Steel Joists, Flat Slab','multi_field' => array('percentage')),
								array('name' => 'steel_joists_precast_deck','label' => 'Steel Joists, Precast Deck','multi_field' => array('percentage')),
				                array('name' => 'floor_ceiling_structure_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
				        'id' => 'electrical_wiring',
				        'label' => 'Electrical & Wiring',
				        'form_items' => array(
								array('name' => 'electrical_service_type', 'label' => 'Electrical Services', 'link_tab' => 'utilities_details', 'type' => 'hyperlink'),
								array('name' => 'field_200_amp_service_standard','label' => '200 Amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'field_200_amp_service_custom','label' => '200 Amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'field_100_amp_service_standard','label' => '100 amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'field_400_amp_service_standard','label' => '400 Amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'field_400_amp_service_custom','label' => '400 Amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'wiring_cable_satellite_phone','label' => 'Wiring, Cable, Satellite & Phone','multi_field' => array('percentage')),
								array('name' => 'wiring_category_5','label' => 'Wiring, Category 5','multi_field' => array('percentage')),
								array('name' => 'lighting_low_voltage','label' => 'Lighting, Low Voltage','multi_field' => array('percentage')),
								array('name' => 'lighting_system_central','label' => 'Lighting System, Central','multi_field' => array('percentage')),
								array('type' => 'divider/separator'),

								array('name' => 'field_100_amp_service_custom','label' => '100 amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'field_150_amp_service_custom','label' => '150 Amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'field_150_amp_service_standard','label' => '150 Amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'field_600_amp_service_custom','label' => '600 Amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'field_600_amp_service_standard','label' => '600 Amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'field_800_amp_service_custom','label' => '800 Amp Service, Custom','multi_field' => array('percentage')),
								array('name' => 'field_800_amp_service_standard','label' => '800 Amp Service, Standard','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_2kwh','label' => 'Photovoltaic System 2 kWH','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_4kwh','label' => 'Photovoltaic System 4 kWH','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_6kwh','label' => 'Photovoltaic System 6 kWH','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_8kwh','label' => 'Photovoltaic System 8 kWH','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_10kwh','label' => 'Photovoltaic System 10 kWH','multi_field' => array('percentage')),
								array('name' => 'photovoltaic_system_12kwh','label' => 'Photovoltaic System 12 kWH','multi_field' => array('percentage')),
								array('name' => 'wiring_computer_cable_electric_dedicated','label' => 'Wiring, Computer Cable & Electric, Dedicated','multi_field' => array('percentage')),
				                array('name' => 'electrical_wiring_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				        )
		);
		$field_sets[] = array(
								'id' => 'exterior_summary_2_subheading',
								'sub_label' => 'EXTERIOR SUMMARY 2 of 2',
								'type' => 'subheading'
							);
		$field_sets[] = array(
                        'id' => 'general_exterior_public_comments',
                        'label' => 'General Exterior: Public Comments',
                        'form_items' => array(
                               	array('name' => 'general_exterior_public_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
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
	}
	
	static function getSelectFields($label) {
		self::drawForm();
		return self::$$label;
	}
}

class Exterior extends ExteriorView{
    function __construct(){}
}
?>
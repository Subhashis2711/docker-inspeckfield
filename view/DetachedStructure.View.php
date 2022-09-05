<?php
class DetachedStructureView extends DetachedStructureController{

        public static $fieldset_infos = array();
        public static $fieldset_ids = array();
        public static $hidden_tor_categories = array('detached_structures_features_subheading', 'detached_public_comments');

        /**
         *
         * Function used for drawing form with fields.
         *
         * @param array $params array containing necessary parameters.
         *
         */
	static function drawForm($params=array()){
                if(!empty($params)){
                        $tab_id 	        = $params['tab_id'];
                        
                        $form_details 		= array(
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
                $field_sets[] = array(
                                        'id' => 'detached_structures_subheading',
                                        'sub_label' => 'DETACHED STRUCTURES',
                                        'type' => 'subheading'
                                );
                $field_sets[] = array(
                                'id' => 'outbuildings',
                                'label' => 'Outbuildings',
                                'form_items' => array(
                                        array('name' => 'no_outbuildings_detached_items','label' => 'No Outbuildings / Detached items','type' => 'check'),
                                        array('name' => 'shed_small_149_sqft','label' => 'Shed, Small (< 149 sq.ft)','multi_field' => array('count')),
                                        array('name' => 'shed_medium_150_799_sqft','label' => 'Shed, Medium (150-799 sq.ft)','multi_field' => array('count')),
                                        array('name' => 'building_utility_shop_sqft','label' => 'Building, Utility (Shop), sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'gazebo','label' => 'Gazebo','multi_field' => array('count')),
                                        array('name' => 'pergola_wood_sqft','label' => 'Pergola, Wood, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'canopy_sqft','label' => 'Canopy, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'arbor_sqft','label' => 'Arbor, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'pool_house_sqft','label' => 'Pool House, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'cover_patio','label' => 'Cover, Patio','multi_field' => array('count')),
                                        array('name' => 'fire_pit_oudoor_detached','label' => 'Fire Pit, Oudoor (Detached)','multi_field' => array('count')),
                                        array('name' => 'guesthouse_sqft','label' => 'Guesthouse, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'boat_lift_medium','label' => 'Boat Lift, Medium','multi_field' => array('count')),
                                        array('name' => 'boat_lift_small','label' => 'Boat Lift, Small','multi_field' => array('count')),
                                        array('name' => 'pergola_sqft','label' => 'Pergola, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'boat_lift_extra_large','label' => 'Boat Lift, Extra-Large','multi_field' => array('count')),
                                        array('name' => 'boat_lift_large','label' => 'Boat Lift, Large','multi_field' => array('count')),
                                        array('name' => 'boat_lift_personal_watercraft','label' => 'Boat Lift, Personal Watercraft','multi_field' => array('count')),
                                        array('name' => 'boat_lift_seasonal','label' => 'Boat Lift, Seasonal','multi_field' => array('count')),
                                        array('name' => 'cabana','label' => 'Cabana','multi_field' => array('count')),
                                        array('name' => 'conservatory_sqft','label' => 'Conservatory, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'gazebo_metal_deluxe_w_metal_Roof_sqft','label' => 'Gazebo, Metal, Deluxe, w/Metal Roof, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'gazebo_wood_deluxe_w_metal_Roof_sqft','label' => 'Gazebo, Wood, deluxe, w/Metal Roof, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'gazebo_wood_deluxe_w_wood_Roof_sqft','label' => 'Gazebo, Wood, Deluxe, w/Wood Roof, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'pavilion','label' => 'Pavilion','multi_field' => array('count')),
                                        array('name' => 'pergola_fibergalss_sqft','label' => 'Pergola, Fibergalss, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'pergola_vinyl_sqft','label' => 'Pergola, Vinyl, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'portico_sqft','label' => 'Portico, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'security_building','label' => 'Security Building','multi_field' => array('count')),
                                        array('name' => 'shed_large_800_1_499_sqft','label' => 'Shed, Large (800-1,499 sq ft)','multi_field' => array('count')),
                                        array('name' => 'storm_shelter_concrete_above_ground_sqft','label' => 'Storm Shelter, Concrete, Above Ground, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'storm_shelter_concrete_below_ground_sqft','label' => 'Storm Shelter, Concrete, Below Ground, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'storm_shelter_fiberglass_below_ground_sqft','label' => 'Storm Shelter, Fiberglass, Below Ground, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'storm_shelter_steel_above_ground_sqft','label' => 'Storm Shelter, Steel, Above Ground, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'storm_shelter_steel_below_ground_sqft','label' => 'Storm Shelter, Steel, Below Ground, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'studio','label' => 'Studio','multi_field' => array('count')),
                                        array('name' => 'temple','label' => 'Temple','multi_field' => array('count')),
                                        array('name' => 'water_tank_concrete_exra_large','label' => 'Water Tank, Concrete, Exra-Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_concrete_large','label' => 'Water Tank, Concrete, Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_concrete_medium','label' => 'Water Tank, Concrete, Medium','multi_field' => array('count')),
                                        array('name' => 'water_tank_concrete_small','label' => 'Water Tank, Concrete, Small','multi_field' => array('count')),
                                        array('name' => 'water_tank_poly_exra_large','label' => 'Water Tank, Poly, Exra-Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_poly_large','label' => 'Water Tank, Poly, Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_poly_medium','label' => 'Water Tank, Poly, Medium','multi_field' => array('count')),
                                        array('name' => 'water_tank_poly_small','label' => 'Water Tank, Poly, Small','multi_field' => array('count')),
                                        array('name' => 'water_tank_steel_exra_large','label' => 'Water Tank, Steel, Exra-Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_steel_large','label' => 'Water Tank, Steel, Large','multi_field' => array('count')),
                                        array('name' => 'water_tank_steel_medium','label' => 'Water Tank, Steel, Medium','multi_field' => array('count')),
                                        array('name' => 'water_tank_steel_small','label' => 'Water Tank, Steel, Small','multi_field' => array('count')),
                                        array('name' => 'outbuildings_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
                                )
                        );
                $field_sets[] = array(
                                'id' => 'pools_and_sports',
                                'label' => 'Pools & Sports',
                                'form_items' => array(
                                        array('name' => 'no_pool_sports_items','label' => 'No Pool or Sports related items','type' => 'check'),
                                        array('name' => 'hot_tub_jacuzzi','label' => 'Hot Tub/Jacuzzi','multi_field' => array('count')),
                                        array('name' => 'basketball_court_unlighted','label' => 'Basketball Court, Unlighted','multi_field' => array('count')),
                                        array('name' => 'basketball_court_ighted','label' => 'Basketball Court, Lighted','multi_field' => array('count')),
                                        array('name' => 'putting_green_sqft','label' => 'Putting Green, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'boathouse_sqft','label' => 'Boathouse, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'dock_bulkhead_sqft','label' => 'Dock & Bulkhead, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'tennis_courts_asphalt_unlighted','label' => 'Tennis Courts, Asphalt Unlighted','multi_field' => array('count')),
                                        array('name' => 'tennis_courts_asphalt_lighted','label' => 'Tennis Courts, Asphalt Lighted','multi_field' => array('count')),
                                        array('name' => 'bathhouse_sqft','label' => 'Bathhouse, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'boat_ramp','label' => 'Boat Ramp','multi_field' => array('count')),
                                        array('name' => 'boat_shelter_sqft','label' => 'Boat Shelter, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'screened_pool_enclosure_sqft','label' => 'Screened Pool Enclosure, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'screened_pool_enclosure_custom_detached_sqft','label' => 'Screened Pool Enclosure, Custom (Detached), sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'screened_pool_enclosure_deluxe_detached_sqft','label' => 'Screened Pool Enclosure, Deluxe (Detached), sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'shuffleboard_court','label' => 'Shuffleboard Court','multi_field' => array('count')),
                                        array('name' => 'table_tennis_court','label' => 'Table Tennis Court','multi_field' => array('count')),
                                        array('name' => 'tennis_courts_clay_lighted','label' => 'Tennis Courts, Clay Lighted','multi_field' => array('count')),
                                        array('name' => 'tennis_courts_clay_unlighted','label' => 'Tennis Courts, Clay Unlighted','multi_field' => array('count')),
                                        array('name' => 'tennis_courts_grass_lighted','label' => 'Tennis Courts, Grass Lighted','multi_field' => array('count')),
                                        array('name' => 'tennis_courts_grass_unlighted','label' => 'Tennis Courts, Grass Unlighted','multi_field' => array('count')),
                                        array('name' => 'pools_and_sports_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
                                ),
                                'associations' => array('hot_tub_jacuzzi')
                );
                $field_sets[] = array(
                                'id' => 'sitework',
                                'label' => 'Sitework',
                                'form_items' => array(
                                        array('name' => 'no_sitework_items','label' => 'No Sitework_Improvement items','type' => 'check'),
                                        array('name' => 'driveway_concrete_sqft','label' => 'Driveway, Concrete, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'driveway_brick_sqft','label' => 'Driveway, Brick, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'driveway_flagstone_sqft','label' => 'Driveway, Flagstone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_poured_concrete_sqft','label' => 'Patio, Poured Concrete, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_stone_sqft','label' => 'Patio, Stone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_block_sqft','label' => 'Patio, Block, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_slate_sqft','label' => 'Patio, Slate, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'fish_pond_water_garden_sqft','label' => 'Fish Pond/Water Garden, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'barbeque','label' => 'Barbeque','multi_field' => array('count')),
                                        array('name' => 'wet_bar_exterior','label' => 'Wet Bar, Exterior','multi_field' => array('count')),
                                        array('name' => 'irrigation_system_sqft','label' => 'Irrigation System, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sprinklers_lawn_standard_sqft','label' => 'Sprinklers, Lawn, Standard, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'bridge_block_sqft','label' => 'Bridge, Block, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'bridge_concrete_sqft','label' => 'Bridge, Concrete, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'bridge_metal_sqft','label' => 'Bridge, Metal, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'bridge_stone_sqft','label' => 'Bridge, Stone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'bridge_wood_sqft','label' => 'Bridge, Wood, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'driveway_asphalt_sqft','label' => 'Driveway, Asphalt, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'driveway_cobblestone_sqft','label' => 'Driveway, Cobblestone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'driveway_slate','label' => 'Driveway, Slate','multi_field' => array('sqft')),
                                        array('name' => 'flagpole','label' => 'Flagpole','multi_field' => array('count')),
                                        array('name' => 'fountain','label' => 'Fountain','multi_field' => array('count')),
                                        array('name' => 'garden_ornament','label' => 'Garden Ornament','multi_field' => array('count')),
                                        array('name' => 'landcaping_custom_sqft','label' => 'Landcaping, Custom, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'light_post','label' => 'Light Post','multi_field' => array('count')),
                                        array('name' => 'lighting_landscape','label' => 'Lighting, Landscape','multi_field' => array('count')),
                                        array('name' => 'lighting_security','label' => 'Lighting, Security','multi_field' => array('count')),
                                        array('name' => 'pad_helicopter','label' => 'Pad, Helicopter','multi_field' => array('count')),
                                        array('name' => 'patio_tile_sqft','label' => 'Patio, Tile, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_tile_marble_sqft','label' => 'Patio, Tile, Marble, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'patio_tile_polished_stone_sqft','label' => 'Patio, Tile, Polished Stone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'radiant_heat_system_driveway_sqft','label' => 'Radiant Heat System, Driveway, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'satellite_dish','label' => 'Satellite Dish','multi_field' => array('count')),
                                        array('name' => 'sidewalk_asphalt_sqft','label' => 'Sidewalk, Asphalt, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_brick_sqft','label' => 'Sidewalk, Brick, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_concrete_sqft','label' => 'Sidewalk, Concrete, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_flagstone_sqft','label' => 'Sidewalk, Flagstone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_patio_block_sqft','label' => 'Sidewalk, Patio Block, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_slate_sqft','label' => 'Sidewalk, Slate, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_stone_sqft','label' => 'Sidewalk, Stone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sidewalk_tile_sqft','label' => 'Sidewalk, Tile, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'sprinklers_lawn_custom_sqft','label' => 'Sprinklers, Lawn, Custom, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'stairs_exterior_brick_architectural','label' => 'Stairs, Exterior, Brick, Architectural','multi_field' => array('count')),
                                        array('name' => 'stairs_exterior_brick_ornate','label' => 'Stairs, Exterior, Brick, Ornate','multi_field' => array('count')),
                                        array('name' => 'stairs_exterior_stone_architectural','label' => 'Stairs, Exterior, Stone, Architectural','multi_field' => array('count')),
                                        array('name' => 'stairs_exterior_stone_ornate','label' => 'Stairs, Exterior, Stone, Ornate','multi_field' => array('count')),
                                        array('name' => 'sitework_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
                                )
                );
                $field_sets[] = array(
                                'id' => 'pets_and_livestock',
                                'label' => 'Pets & Livestock',
                                'form_items' => array(
                                        array('name' => 'no_pet_form_livestock_items','label' => 'No Pet_Farm_Livestock items','type' => 'check'),
                                        array('name' => 'barn_standard_detached_sqft','label' => 'Barn, Standard, Detached, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'barn_standard_detached_2story_sqft','label' => 'Barn, Standard, Detached, 2-Story, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'stable_small','label' => 'Stable, Small, sq.ft','multi_field' => array('count')),
                                        array('name' => 'stable_medium','label' => 'Stable, Medium, sq.ft','multi_field' => array('count')),
                                        array('name' => 'stable_custom_sqft','label' => 'Stable, Custom, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'corral_livestock_sqft','label' => 'Corral, Livestock, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'corral_equestrian_lf','label' => 'Corral, Equestrian, LF','multi_field' => array('lf')),
                                        array('name' => 'arena_sqft','label' => 'Arena, sq.ft','multi_field' => array('count', 'lf', 'sqft')),
                                        array('name' => 'barn_vintage_detached_sqft','label' => 'Barn, Vintage, Detached, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'barn_vintage_detached_2story_sqft','label' => 'Barn, Vintage, Detached, 2-Story, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'kennel_run_sqft','label' => 'Kennel Run, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'stable_large','label' => 'Stable, Large','multi_field' => array('count')),
                                        array('name' => 'pets_and_livestock_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
                                )
                );
                $field_sets[] = array(
                                'id' => 'walls_and_fences',
                                'label' => 'Walls and Fences',
                                'form_items' => array(
                                        array('name' => 'no_retaining_wall_fences','label' => 'No Retaining Walls or Fences','type' => 'check'),
                                        array('name' => 'fence_chain_link_sqft','label' => 'Fence, Chain Link, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'fence_wood_ornamental_sqft','label' => 'Fence, Wood, Ornamental, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'garden_wall_block_lf','label' => 'Garden Wall, Block, LF','multi_field' => array('lf')),
                                        array('name' => 'gate_wrought_iron','label' => 'Gate Wrought Iron','multi_field' => array('count')),
                                        array('name' => 'gate_wood','label' => 'Gate Wood','multi_field' => array('count')),
                                        array('name' => 'retaining_wall_fieldstone_sqft','label' => 'Retaining Wall, Fieldstone, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_poured_concrete_sqft','label' => 'Retaining Wall, Poured Concrete, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_railroad_tie_sqft','label' => 'Retaining Wall, Railroad Tie, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_stacked_boulder_sqft','label' => 'Retaining Wall, Stacked Boulder, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_block_sqft','label' => 'Retaining Wall, Block, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'gate_chain_link','label' => 'Gate, Chain Link','multi_field' => array('lf')),
                                        array('name' => 'gate_wood_ornate','label' => 'Gate, Wood, Ornate','multi_field' => array('count')),
                                        array('name' => 'retaining_wall_brick_and_block_sqft','label' => 'Retaining Wall, Brick & Block, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_brick_sqft','label' => 'Retaining Wall, Brick, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_gabion_sqft','label' => 'Retaining Wall, Gabion, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'retaining_wall_stone_veneer_sqft','label' => 'Retaining Wall, Stone Veneer, sq.ft','multi_field' => array('sqft')),
                                        array('name' => 'walls_and_fences_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
                                )
                );
                $field_sets[] = array(
                                'id' => 'detached_structures_features_subheading',
                                'sub_label' => 'DETACHED STRUCTURES & FEATURES',
                                'type' => 'subheading'
                        );
                $field_sets[] = array(
                                'id' => 'detached_public_comments',
                                'label' => 'Detached: Public Comments',
                                'form_items' => array(
                                                array('name' => 'detached_public_comments','label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
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
	}
}

class DetachedStructure extends DetachedStructureView{
    function __construct(){}
}
?>
<?php
class InteriorMoreView extends InteriorMoreController{
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
								'css_class' => ''
							);

			if(isset($params['inspection_id']) && $params['inspection_id']){
				$inspection_data = self::getData($params);
				$percentage_data = self::getPercentages($params);

				if(is_array($inspection_data) && count($inspection_data) > 0){
					$form_details['values']	= $inspection_data;
				}
			}
		}

		$ep1 = (isset($percentage_data['estimated_percentage1']))?$percentage_data['estimated_percentage1']:'';
		$ep2 = (isset($percentage_data['estimated_percentage2']))?$percentage_data['estimated_percentage2']:'';
		$ep3 = (isset($percentage_data['estimated_percentage3']))?$percentage_data['estimated_percentage3']:'';
		$ep4 = (isset($percentage_data['estimated_percentage4']))?$percentage_data['estimated_percentage4']:'';
		$fp1 = (isset($percentage_data['fp1']))?$percentage_data['fp1']:'';
		$fp2 = (isset($percentage_data['fp2']))?$percentage_data['fp2']:'';
		$fp3 = (isset($percentage_data['fp3']))?$percentage_data['fp3']:'';
		$fp4 = (isset($percentage_data['fp4']))?$percentage_data['fp4']:'';

		$fc_options = array(
						'none' => 'Select Floor Coverings',
						'Hardwood' => 'Hardwood',
						'Tile, Ceramic' => 'Tile, Ceramic',
						'Carpet, Acrylic/Nylon' => 'Carpet, Acrylic/Nylon',
						'Carpet, Custom Acrylic/Nylon' => 'Carpet, Custom Acrylic/Nylon',
						'Carpet, Wool/Berber' => 'Carpet, Wool/Berber',
						'Laminated Wood Flooring' => 'Laminated Wood Flooring',
						'Engineered Wood Flooring' => 'Engineered Wood Flooring',
						'Tile, Marble' => 'Tile, Marble',
						'Tile, Ceramic, Imported' => 'Tile, Ceramic, Imported',
						'Vinyl' => 'Vinyl',
						'Concrete, Stamped/Textured' => 'Concrete, Stamped/Textured',
						'Plank' => 'Plank',
						'Slate' => 'Slate',
						'Tile, Porcelain' => 'Tile, Porcelain',
						'Bamboo' => 'Bamboo',
						'Brick' => 'Brick',
						'Carpet over Hardwood, Acrylic/Nylon' => 'Carpet over Hardwood, Acrylic/Nylon',
						'Carpet over Hardwood, Custom Acrylic' => 'Carpet over Hardwood, Custom Acrylic',
						'Carpet over Hardwood, Wool/Berber' => 'Carpet over Hardwood, Wool/Berber',
						'Carpet with Pad, Wool' => 'Carpet with Pad, Wool',
						'Carpet, Custom Acrylic/Nylon' => 'Carpet, Custom Acrylic/Nylon',
						'Carpet, Wool/Berber' => 'Carpet, Wool/Berber',
						'Concrete, Acid Washed/Etched' => 'Concrete, Acid Washed/Etched',
						'Concrete, Gypsum' => 'Concrete, Gypsum',
						'Concrete, Lightweight' => 'Concrete, Lightweight',
						'Cork' => 'Cork',
						'Cypress' => 'Cypress',
						'Cypress, Heart' => 'Cypress, Heart',
						'poxy' => 'Epoxy',
						'Flagstone' => 'Flagstone',
						'Floor, Gym' => 'Floor, Gym',
						'Floor, Gym, Resilient' => 'Floor, Gym, Resilient',
						'Fruitwood' => 'Fruitwood',
						'Granite' => 'Granite',
						'Hardwood' => 'Hardwood',
						'Hardwood, Surface Nailed' => 'Hardwood, Surface Nailed',
						'Laminated Wood Flooring' => 'Laminated Wood Flooring',
						'Limestone, Pillowed' => 'Limestone, Pillowed ',
						'Mahogany' => 'Mahogany',
						'Maple' => 'Maple',
						'arble, Group A' => 'Marble, Group A',
						'Marble, Group B' => 'Marble, Group B',
						'Marble, Group C' => 'Marble, Group C',
						'Marble, Group D' => 'Marble, Group D',
						'Marble, Imported' => 'Marble, Imported',
						'Oak' => 'Oak',
						'Oak, Quarter Sawn' => 'Oak, Quarter Sawn',
						'Oak, Random Width' => 'Oak, Random Width',
						'Oak, Tiger' => 'Oak, Tiger',
						'Paint' => 'Paint',
						'Parquet' => 'Parquet',
						'Pine, Heart' => 'Pine, Heart',
						'Pine, Quarter Sawn' => 'Pine, Quarter Sawn',
						'Plank, Ash' => 'Plank, Ash',
						'Plank, Cherry' => 'Plank, Cherry',
						'Plant, Chestnut, Reclaimed' => 'Plant, Chestnut, Reclaimed',
						'Plank, Fruitwood' => 'Plank, Fruitwood',
						'Plank, Knotty Pine' => 'Plank, Knotty Pine',
						'Plank, Laminated Bamboo' => 'Plank, Laminated Bamboo',
						'lank, Maple' => 'Plank, Maple',
						'Plank, Oak' => 'Plank, Oak',
						'Plank, Pine' => 'Plank, Pine',
						'Plank, Pine, Heart, Richmond' => 'Plank, Pine, Heart, Richmond',
						'Plank, Pine, Wide' => 'Plank, Pine, Wide',
						'Plank, Pine, Wide, Reclaimed' => 'Plank, Pine, Wide, Reclaimed',
						'Plank, Walnut' => 'Plank, Walnut',
						'Plywood (only)' => 'Plywood (only)',
						'Rubber' => 'Rubber',
						'Sandstone' => 'Sandstone',
						'Sandstone, Herringbone' => 'Sandstone, Herringbone',
						'Slate' => 'Slate',
						'Softwood' => 'Softwood',
						'Stone' => 'Stone',
						'Stone Planks' => 'Stone Planks',
						'Synthetic Sports' => 'Synthetic Sports',
						'Teak' => 'Teak',
						'Tile, Ceramic' => 'Tile, Ceramic',
						'Tile, Ceramic, Fresco' => 'Tile, Ceramic, Fresco',
						'Tile, Ceramic, Hand-painted' => 'Tile, Ceramic, Hand-painted',
						'Tile, Ceramic, Imported' => 'Tile, Ceramic, Imported',
						'Tile, Limestone' => 'Tile, Limestone',
						'Tile, Marble' => 'Tile, Marble',
						'Tile, Mexican' => 'Tile, Mexican',
						'Tile, Mosaic' => 'Tile, Mosaic',
						'Tile, Mural' => 'Tile, Mural',
						'Tile, Onyx' => 'Tile, Onyx',
						'Tile, Quarry' => 'Tile, Quarry',
						'Tile, Terrazzo' => 'Tile, Terrazzo',
						'Tile, Travertine' => 'Tile, Travertine',
						'Tile, Vinyl, Average' => 'Tile, Vinyl, Average',
						'Tile, Vinyl, Composition' => 'Tile, Vinyl, Composition',
						'Tile, Vinyl, Custom' => 'Tile, Vinyl, Custom',
						'Tile, Vinyl, Luxury' => 'Tile, Vinyl, Luxury',
						'Tile, Wood Fresco' => 'Tile, Wood Fresco',
						'Wood block' => 'Wood block',
						'Wood Inlay' => 'Wood Inlay',
						'Wood Inlay, Artwork' => 'Wood Inlay, Artwork'
					);
		$wc_options = array(
						'none' => 'Select Wall Coverings',
						'Paint' => 'Paint',
						'Tile, Ceramic' => 'Tile, Ceramic',
						'Tile, Ceramic, Imported' => 'Tile, Ceramic, Imported',
						'Tile, Marble' => 'Tile, Marble',
						'Paneling, Solid Wood' => 'Paneling, Solid Wood',
						'Wallpaper, Vinyl' => 'Wallpaper, Vinyl',
						'Mirrors' => 'Mirrors',
						'Millwork' => 'Millwork',
						'Facing, Stone' => 'Facing, Stone',
						'Facing, Brick' => 'Facing, Brick',
						'Paneling, Tng & Grve' => 'Paneling, Tng & Grve',
						'Wainscoting, Decorative Panel' => 'Wainscoting, Decorative Panel',
						'Paneling, Sheet' => 'Paneling, Sheet',
						'Artwork, Fresco/Mural' => 'Artwork, Fresco/Mural',
						'Board, Soundproof' => 'Board, Soundproof',
						'Burlap' => 'Burlap',
						'Carpet' => 'Carpet',
						'Chair Rail, Hardwood' => 'Chair Rail, Hardwood',
						'Corkboard' => 'Corkboard',
						'Fabric' => 'Fabric',
						'Fabric, Padded Upholstered' => 'Fabric, Padded Upholstered',
						'Insulation, Sound' => 'Insulation, Sound',
						'Leaf, Copper' => 'Leaf, Copper',
						'Leaf, Gold' => 'Leaf, Gold',
						'Leaf, Palladium' => 'Leaf, Palladium',
						'Leaf, Silver' => 'Leaf, Silver',
						'Marble, Group A' => 'Marble, Group A',
						'Marble, Group B' => 'Marble, Group B',
						'Marble, Group C' => 'Marble, Group C',
						'Marble, Group D' => 'Marble, Group D',
						'Molding, Ornate Wall' => 'Molding, Ornate Wall',
						'Paint, Marble' => 'Paint, Marble',
						'Paneling, Cedar' => 'Paneling, Cedar',
						'Paneling, Cherry' => 'Paneling, Cherry',
						'Paneling, Cherry, Tng & Grve' => 'Paneling, Cherry, Tng & Grve',
						'Paneling, Chestnut, Tng & Grve' => 'Paneling, Chestnut, Tng & Grve',
						'Paneling, Cypress' => 'Paneling, Cypress',
						'Paneling, Knotty Pine' => 'Paneling, Knotty Pine',
						'Paneling, Knotty Pine, Tng & Grve' => 'Paneling, Knotty Pine, Tng & Grve',
						'Paneling Mahogany' => 'Paneling Mahogany',
						'Paneling, Mahogany, Tng & Grve' => 'Paneling, Mahogany, Tng & Grve',
						'Paneling, Maple' => 'Paneling, Maple',
						'Paneling, Oak' => 'Paneling, Oak',
						'Paneling, Oak,Tng & Grve' => 'Paneling, Oak,Tng & Grve',
						'Paneling , Pina, Tng & Grve' => 'Paneling , Pina, Tng & Grve',
						'Paneling, Plastic Coated' => 'Paneling, Plastic Coated',
						'Paneling, Plywood' => 'Paneling, Plywood',
						'Paneling, Redwood' => 'Paneling, Redwood',
						'Paneling, Rosewd, Tng & Grve' => 'Paneling, Rosewd, Tng & Grve',
						'Paneling, Teak' => 'Paneling, Teak',
						'Paneling, Teak, Tng & Grve' => 'Paneling, Teak, Tng & Grve',
						'Paneling, Walnut' => 'Paneling, Walnut',
						'Paneling, Wormy Chestnut, Tng & Grve' => 'Paneling, Wormy Chestnut, Tng & Grve',
						'Pegboard' => 'Pegboard',
						'Planks, Cherry' => 'Planks, Cherry',
						'Planks, Chestnut' => 'Planks, Chestnut',
						'Planks, Fruitwood' => 'Planks, Fruitwood',
						'Planks, Mahogany' => 'Planks, Mahogany',
						'Planks, Oak' => 'Planks, Oak',
						'Planks, Pine' => 'Planks, Pine',
						'Planks, Rosewood' => 'Planks, Rosewood',
						'Planks, Teak' => 'Planks, Teak',
						'Planks, Wormy Chestnut' => 'Planks, Wormy Chestnut',
						'Plaster, Skimcoat' => 'Plaster, Skimcoat',
						'Raised Panel, Cherry Ornate' => 'Raised Panel, Cherry Ornate',
						'Raised Panel, Chestnut, Ornate' => 'Raised Panel, Chestnut, Ornate',
						'Raised Panel, Knotty Pine, Ornate' => 'Raised Panel, Knotty Pine, Ornate',
						'Raise Panel, Mahogany, Ornate' => 'Raise Panel, Mahogany, Ornate',
						'Raised Panel, Oak, Ornate' => 'Raised Panel, Oak, Ornate',
						'Raised Panel, Rosewood, Ornate' => 'Raised Panel, Rosewood, Ornate',
						'Raised Panel, Teak, Ornate' => 'Raised Panel, Teak, Ornate',
						'Raised Pnl, Wormy Chestnut, Ornate' => 'Raised Pnl, Wormy Chestnut, Ornate',
						'Random Stone' => 'Random Stone',
						'Sheet Vinyl' => 'Sheet Vinyl',
						'Shellac' => 'Shellac',
						'Silk Brocade' => 'Silk Brocade',
						'Sponge Coat' => 'Sponge Coat',
						'Stain' => 'Stain',
						'Terrazzo' => 'Terrazzo',
						'Tile, Custom Painted' => 'Tile, Custom Painted',
						'Tile, Decorative' => 'Tile, Decorative',
						'Tile, Glass' => 'Tile, Glass',
						'Tile, Granite' => 'Tile, Granite',
						'Tile, Limestone' => 'Tile, Limestone',
						'Tile, Mosaic' => 'Tile, Mosaic',
						'Tile, Onyx' => 'Tile, Onyx',
						'Tile, Plaster' => 'Tile, Plaster',
						'Tile, Plastic' => 'Tile, Plastic',
						'Tile, Quartz' => 'Tile, Quartz',
						'Veneer, Granite' => 'Veneer, Granite',
						'Veneer, Marble' => 'Veneer, Marble',
						'Veneer, Stone' => 'Veneer, Stone',
						'Venetian Plaster' => 'Venetian Plaster',
						'Wainscoting, Beadboard Panel' => 'Wainscoting, Beadboard Panel',
						'Wainscoting, Tng & Grve' => 'Wainscoting, Tng & Grve',
						'Wallpaper, Foil' => 'Wallpaper, Foil',
						'Wallpaper, Grass Cloth' => 'Wallpaper, Grass Cloth',
						'Wallpaper, Hand-painted' => 'Wallpaper, Hand-painted',
						'Wallpaper, Special' => 'Wallpaper, Special'
					);
		$cm_options = array(
						'none' => 'Select Ceiling Material',
						'Drywall' => 'Drywall',
						'Drywall, Textured' => 'Drywall, Textured',
						'Plaster' => 'Plaster',
						'Plywd/Hdwd, Coffered/Vaulted Panel' => 'Plywd/Hdwd, Coffered/Vaulted Panel',
						'Textured Drywall Ceiling, Ornate' => 'Textured Drywall Ceiling, Ornate',
						'Wood, Tng & Grve' => 'Wood, Tng & Grve',
						'Wood Plank' => 'Wood Plank',
						'Ornamental Ceiling' => 'Ornamental Ceiling',
						'Millwork' => 'Millwork',
						'Tile, Acoustical' => 'Tile, Acoustical',
						'Foil' => 'Foil',
						'Carpet' => 'Carpet',
						'Ceiling Paper' => 'Ceiling Paper',
						'Cork' => 'Cork',
						'Fabric' => 'Fabric',
						'Fabric, Padded Upholstered' => 'Fabric, Padded Upholstered',
						'Fresco/Murals' => 'Fresco/Murals',
						'Grass Cloth' => 'Grass Cloth',
						'Leaf, Copper' => 'Leaf, Copper',
						'Leaf, Gold' => 'Leaf, Gold',
						'Leaf, Palladium' => 'Leaf, Palladium',
						'Leaf, Silver' => 'Leaf, Silver',
						'Luminous Ceiling' => 'Luminous Ceiling',
						'Marble' => 'Marble',
						'Marble, Group A' => 'Marble, Group A',
						'Marble, Group B' => 'Marble, Group B',
						'Marble, Group C' => 'Marble, Group C',
						'Marble, Group D' => 'Marble, Group D',
						'Metal' => 'Metal',
						'Mirrors' => 'Mirrors',
						'Ornamental Ceiling, Ornate' => 'Ornamental Ceiling, Ornate',
						'Paint Only' => 'Paint Only',
						'Panels, Mirror-Faced' => 'Panels, Mirror-Faced',
						'Panels, Plywd/Hdwood' => 'Panels, Plywd/Hdwood',
						'Plank' => 'Plank',
						'Plaster, Horsehair' => 'Plaster, Horsehair',
						'Plaster, Horsehair with Wd Beams' => 'Plaster, Horsehair with Wd Beams',
						'Plaster, Textured' => 'Plaster, Textured',
						'Plywood Only' => 'Plywood Only',
						'Plywd/Hdwd, Coffered/Vlted Pnl, Csm Wd' => 'Plywd/Hdwd, Coffered/Vlted Pnl, Csm Wd',
						'Soundproof Board' => 'Soundproof Board',
						'Spray Acoustical Texture' => 'Spray Acoustical Texture',
						'Stain Only' => 'Stain Only',
						'Stucco Finish' => 'Stucco Finish',
						'Terrazzo' => 'Terrazzo',
						'Tile, Ceramic' => 'Tile, Ceramic',
						'Tile, Ceramic, Decorative, Custom' => 'Tile, Ceramic, Decorative, Custom',
						'Tile, Lay-in Acoustical, 2&apos; x 2&apos;' => 'Tile, Lay-in Acoustical, 2&apos; x 2&apos;',
						'Tile, Lay-in Acoustical, 2&apos; x 4&apos;' => 'Tile, Lay-in Acoustical, 2&apos; x 4&apos;',
						'Tile, Lay-in Acoustical, Custom' => 'Tile, Lay-in Acoustical, Custom',
						'Tile, Leather' => 'Tile, Leather',
						'Tile, Mosaic' => 'Tile, Mosaic',
						'Tile, Plaster' => 'Tile, Plaster',
						'Tile, Plastic' => 'Tile, Plastic',
						'Tin' => 'Tin',
						'Tongue and Groove' => 'Tongue and Groove',
						'Tray Ceiling, Drywall' => 'Tray Ceiling, Drywall',
						'Tray Ceiling, Plaster' => 'Tray Ceiling, Plaster',
						'Venetian Plaster' => 'Venetian Plaster',
						'Wallpaper, Custom' => 'Wallpaper, Custom',
						'Wood' => 'Wood',
						'Wood, Carved, Decorative' => 'Wood, Carved, Decorative'
					);
		$wch_options = array(
						'none' => 'Select height',
						'n/a' => 'Enter Height',
						'7 Ft.' => '7 Ft.',
						'8 Ft.' => '8 Ft.',
						'9 Ft.' => '9 Ft.',
						'10 Ft.' => '10 Ft.',
						'11 Ft.' => '11 Ft.',
						'12 Ft.' => '12 Ft.',
						'13 Ft.' => '13 Ft.',
						'14 Ft.' => '14 Ft.',
						'15 Ft.' => '15 Ft.',
						'16 Ft.' => '16 Ft.',
						'17 Ft.' => '17 Ft.',
						'18 Ft.' => '18 Ft.',
						'19 Ft.' => '19 Ft.',
						'20 Ft.' => '20 Ft.',
						'21 Ft.' => '21 Ft.',
						'22 Ft.' => '22 Ft.',
						'23 Ft.' => '23 Ft.',
						'24 Ft.' => '24 Ft.',
						'25 Ft.' => '25 Ft.',
						'26 Ft.' => '26 Ft.',
						'27 Ft.' => '27 Ft.',
						'28 Ft.' => '28 Ft.',
						'29 Ft.' => '29 Ft.',
						'30 Ft.' => '30 Ft.',
						'31 Ft.' => '31 Ft.',
						'32 Ft.' => '32 Ft.',
					);
		
		$field_sets = array();
		$field_sets[] = array(
							'id' => 'floor_coverings_storey1',
							'css_class' => 'initial_fieldset',
							'label' => 'Floor Coverings (1st Storey)',
							'form_items' => array(
												array('name' => 'fc1_hidden', 'type' => 'hidden'),
												array('name' => 'fc1_select_hidden', 'type' => 'hidden'),
												array('name' => 'fc1_storey_hidden', 'type' => 'hidden'),
												array('name' => 'fc1_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep1),
												array('name' => 'fc1_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp1),
												array('name' => 'fc1_field1', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc1'),
												array('name' => 'fc1_field2', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc1'),
												array('name' => 'fc1_field3', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc1'),
												array('name' => 'fc1_field4', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc1'),
												array('name' => 'fc1_field5', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc1'),
												array('name' => 'fc1_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'floor_coverings_storey1_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)

											)
						);
		$field_sets[] = array(
							'id' => 'floor_coverings_storey2',
							'css_class' => 'initial_fieldset',
							'label' => 'Floor Coverings (2nd Storey)',
							'form_items' => array(
												array('name' => 'fc2_hidden', 'type' => 'hidden'),
												array('name' => 'fc2_select_hidden', 'type' => 'hidden'),
												array('name' => 'fc2_storey_hidden', 'type' => 'hidden'),
												array('name' => 'fc2_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep2),
												array('name' => 'fc2_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp2),
												array('name' => 'fc2_field1', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc2'),
												array('name' => 'fc2_field2', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc2'),
												array('name' => 'fc2_field3', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc2'),
												array('name' => 'fc2_field4', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc2'),
												array('name' => 'fc2_field5', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc2'),
												array('name' => 'fc2_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'floor_coverings_storey2_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'floor_coverings_storey3',
							'css_class' => 'initial_fieldset',
							'label' => 'Floor Coverings (3rd Storey)',
							'form_items' => array(
												array('name' => 'fc3_hidden', 'type' => 'hidden'),
												array('name' => 'fc3_select_hidden', 'type' => 'hidden'),
												array('name' => 'fc3_storey_hidden', 'type' => 'hidden'),
												array('name' => 'fc3_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep3),
												array('name' => 'fc3_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp3),
												array('name' => 'fc3_field1', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc3'),
												array('name' => 'fc3_field2', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc3'),
												array('name' => 'fc3_field3', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc3'),
												array('name' => 'fc3_field4', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc3'),
												array('name' => 'fc3_field5', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc3'),
												array('name' => 'fc3_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'floor_coverings_storey3_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'floor_coverings_storey4',
							'css_class' => 'initial_fieldset',
							'label' => 'Floor Coverings (4th Storey)',
							'form_items' => array(
												array('name' => 'fc4_hidden', 'type' => 'hidden'),
												array('name' => 'fc4_select_hidden', 'type' => 'hidden'),
												array('name' => 'fc4_storey_hidden', 'type' => 'hidden'),
												array('name' => 'fc4_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep4),
												array('name' => 'fc4_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp4),
												array('name' => 'fc4_field1', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc4'),
												array('name' => 'fc4_field2', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc4'),
												array('name' => 'fc4_field3', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc4'),
												array('name' => 'fc4_field4', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc4'),
												array('name' => 'fc4_field5', 'type' => 'interior_more_select', 'datasets' => $fc_options, 'drop_down_type' => 'fc4'),
												array('name' => 'fc4_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'floor_coverings_storey4_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'cumulative_summary_fc',
							'css_class' => 'initial_fieldset cumulative-section',
							'label' => 'Cumulative Summary',
							'form_items' => array(
												array('name' => 'cs_fc_hidden', 'type' => 'hidden'),
												array('name' => 'cs_fc_field1', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field2', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field3', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field4', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field5', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field6', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field7', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field8', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field9', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field10', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field11', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field12', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field13', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field14', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field15', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field16', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field17', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field18', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field19', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_field20', 'type' => 'interior_more_cs', 'datasets' => $fc_options, 'calculation' => 'cumulative'),
												array('name' => 'cs_fc_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_cs', 'calculation' => 'sum'),
												array('name' => 'cumulative_summary_fc_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)

											)
						);
		$field_sets[] = array(
							'id' => 'wall_coverings_storey1',
							'css_class' => 'initial_fieldset',
							'label' => 'WALL Coverings (1st Storey)',
							'form_items' => array(
												array('name' => 'wc1_hidden', 'type' => 'hidden'),
												array('name' => 'wc1_select_hidden', 'type' => 'hidden'),
												array('name' => 'wc1_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wc1_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep1),
												array('name' => 'wc1_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp1),
												array('name' => 'wc1_field1', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc1'),
												array('name' => 'wc1_field2', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc1'),
												array('name' => 'wc1_field3', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc1'),
												array('name' => 'wc1_field4', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc1'),
												array('name' => 'wc1_field5', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc1'),
												array('name' => 'wc1_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_coverings_storey1_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)
				


											)
						);
		$field_sets[] = array(
							'id' => 'wall_coverings_storey2',
							'css_class' => 'initial_fieldset',
							'label' => 'WALL Coverings (2nd Storey)',
							'form_items' => array(
												array('name' => 'wc2_hidden', 'type' => 'hidden'),
												array('name' => 'wc2_select_hidden', 'type' => 'hidden'),
												array('name' => 'wc2_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wc2_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep2),
												array('name' => 'wc2_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp2),
												array('name' => 'wc2_field1', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc2'),
												array('name' => 'wc2_field2', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc2'),
												array('name' => 'wc2_field3', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc2'),
												array('name' => 'wc2_field4', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc2'),
												array('name' => 'wc2_field5', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc2'),
												array('name' => 'wc2_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_coverings_storey2_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)

												

											)
						);
		$field_sets[] = array(
							'id' => 'wall_coverings_storey3',
							'css_class' => 'initial_fieldset',
							'label' => 'WALL Coverings (3rd Storey)',
							'form_items' => array(
												array('name' => 'wc3_hidden', 'type' => 'hidden'),
												array('name' => 'wc3_select_hidden', 'type' => 'hidden'),
												array('name' => 'wc3_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wc3_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep3),
												array('name' => 'wc3_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp3),
												array('name' => 'wc3_field1', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc3'),
												array('name' => 'wc3_field2', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc3'),
												array('name' => 'wc3_field3', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc3'),
												array('name' => 'wc3_field4', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc3'),
												array('name' => 'wc3_field5', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc3'),
												array('name' => 'wc3_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_coverings_storey3_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'wall_coverings_storey4',
							'css_class' => 'initial_fieldset',
							'label' => 'WALL Coverings (4th Storey)',
							'form_items' => array(
												array('name' => 'wc4_hidden', 'type' => 'hidden'),
												array('name' => 'wc4_select_hidden', 'type' => 'hidden'),
												array('name' => 'wc4_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wc4_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep4),
												array('name' => 'wc4_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp4),
												array('name' => 'wc4_field1', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc4'),
												array('name' => 'wc4_field2', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc4'),
												array('name' => 'wc4_field3', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc4'),
												array('name' => 'wc4_field4', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc4'),
												array('name' => 'wc4_field5', 'type' => 'interior_more_select', 'datasets' => $wc_options, 'drop_down_type' => 'wc4'),
												array('name' => 'wc4_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_coverings_storey4_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'cumulative_summary_wc',
							'css_class' => 'initial_fieldset cumulative-section',
							'label' => 'Cumulative Summary',
							'form_items' => array(
												array('name' => 'cs_wc_hidden', 'type' => 'hidden'),
												array('name' => 'cs_wc_field1', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field2', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field3', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field4', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field5', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field6', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field7', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field8', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field9', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field10', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field11', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field12', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field13', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field14', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field15', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field16', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field17', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field18', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field19', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_field20', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wc_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_cs', 'calculation' => 'sum'),
												array('name' => 'cumulative_summary_wc_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'ceiling_material_storey1',
							'css_class' => 'initial_fieldset',
							'label' => 'CEILING Material (1st Storey)',
							'form_items' => array(
												array('name' => 'cm1_hidden', 'type' => 'hidden'),
												array('name' => 'cm1_select_hidden', 'type' => 'hidden'),
												array('name' => 'cm1_storey_hidden', 'type' => 'hidden'),
												array('name' => 'cm1_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep1),
												array('name' => 'cm1_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp1),
												array('name' => 'cm1_field1', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm1'),
												array('name' => 'cm1_field2', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm1'),
												array('name' => 'cm1_field3', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm1'),
												array('name' => 'cm1_field4', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm1'),
												array('name' => 'cm1_field5', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm1'),
												array('name' => 'cm1_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'ceiling_material_storey1_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'ceiling_material_storey2',
							'css_class' => 'initial_fieldset',
							'label' => 'CEILING Material (2nd Storey)',
							'form_items' => array(
												array('name' => 'cm2_hidden', 'type' => 'hidden'),
												array('name' => 'cm2_select_hidden', 'type' => 'hidden'),
												array('name' => 'cm2_storey_hidden', 'type' => 'hidden'),
												array('name' => 'cm2_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep2),
												array('name' => 'cm2_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp2),
												array('name' => 'cm2_field1', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm2'),
												array('name' => 'cm2_field2', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm2'),
												array('name' => 'cm2_field3', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm2'),
												array('name' => 'cm2_field4', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm2'),
												array('name' => 'cm2_field5', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm2'),
												array('name' => 'cm2_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'ceiling_material_storey2_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'ceiling_material_storey3',
							'css_class' => 'initial_fieldset',
							'label' => 'CEILING Material (3rd Storey)',
							'form_items' => array(
												array('name' => 'cm3_hidden', 'type' => 'hidden'),
												array('name' => 'cm3_select_hidden', 'type' => 'hidden'),
												array('name' => 'cm3_storey_hidden', 'type' => 'hidden'),
												array('name' => 'cm3_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep3),
												array('name' => 'cm3_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp3),
												array('name' => 'cm3_field1', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm3'),
												array('name' => 'cm3_field2', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm3'),
												array('name' => 'cm3_field3', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm3'),
												array('name' => 'cm3_field4', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm3'),
												array('name' => 'cm3_field5', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm3'),
												array('name' => 'cm3_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'ceiling_material_storey3_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'ceiling_material_storey4',
							'css_class' => 'initial_fieldset',
							'label' => 'CEILING Material (4th Storey)',
							'form_items' => array(
												array('name' => 'cm4_hidden', 'type' => 'hidden'),
												array('name' => 'cm4_select_hidden', 'type' => 'hidden'),
												array('name' => 'cm4_storey_hidden', 'type' => 'hidden'),
												array('name' => 'cm4_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep4),
												array('name' => 'cm4_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp4),
												array('name' => 'cm4_field1', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm4'),
												array('name' => 'cm4_field2', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm4'),
												array('name' => 'cm4_field3', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm4'),
												array('name' => 'cm4_field4', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm4'),
												array('name' => 'cm4_field5', 'type' => 'interior_more_select', 'datasets' => $cm_options, 'drop_down_type' => 'cm4'),
												array('name' => 'cm4_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'ceiling_material_storey4_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'cumulative_summary_cm',
							'css_class' => 'initial_fieldset cumulative-section',
							'label' => 'Cumulative Summary',
							'form_items' => array(
												array('name' => 'cs_cm_hidden', 'type' => 'hidden'),
												array('name' => 'cs_cm_field1', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field2', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field3', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field4', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field5', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field6', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field7', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field8', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field9', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field10', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field11', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field12', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field13', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field14', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field15', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field16', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field17', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field18', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field19', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_field20', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_cm_total', 'type' => (RequestInspection::isTor($params['inspection_id']))? 'hidden' : 'interior_more_cs', 'calculation' => 'sum'),
												array('name' => 'cumulative_summary_cm_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'wall_ceiling_heights_storey1',
							'css_class' => 'initial_fieldset',
							'label' => 'Wall (Ceiling) Heights (1st Storey)',
							'form_items' => array(
												array('name' => 'wch1_hidden', 'type' => 'hidden'),
												array('name' => 'wch1_select_hidden', 'type' => 'hidden'),
												array('name' => 'wch1_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wch1_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep1),
												array('name' => 'wch1_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp1),

												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Regular Ceiling + Cathdral Ceiling', 'type' => 'heading'),
												array('name' => 'wch1_field1', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch1_field2', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch1_field3', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch1_field4', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch1_field5', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Overall Ceiling', 'type' => 'heading'),
												array('name' => 'wch1_overall1', 'type' => 'interior_more_overall'),
												array('name' => 'wch1_overall2', 'type' => 'interior_more_overall'),
												array('name' => 'wch1_overall3', 'type' => 'interior_more_overall'),

												array('name' => 'wch1_total', 'type' =>'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_ceiling_heights_storey1_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)

										)
						);
		$field_sets[] = array(
							'id' => 'wall_ceiling_heights_storey2',
							'css_class' => 'initial_fieldset',
							'label' => 'Wall (Ceiling) Heights (2nd Storey)',
							'form_items' => array(
												array('name' => 'wch2_hidden', 'type' => 'hidden'),
												array('name' => 'wch2_select_hidden', 'type' => 'hidden'),
												array('name' => 'wch2_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wch2_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep2),
												array('name' => 'wch2_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp2),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Regular Ceiling + Cathdral Ceiling', 'type' => 'heading'),
												array('name' => 'wch2_field1', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch2'),
												array('name' => 'wch2_field2', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch2'),
												array('name' => 'wch2_field3', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch2'),
												array('name' => 'wch2_field4', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch2_field5', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Overall Ceiling', 'type' => 'heading'),
												array('name' => 'wch2_overall1', 'type' => 'interior_more_overall'),
												array('name' => 'wch2_overall2', 'type' => 'interior_more_overall'),
												array('name' => 'wch2_overall3', 'type' => 'interior_more_overall'),

												array('name' => 'wch2_total', 'type' =>'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_ceiling_heights_storey2_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'wall_ceiling_heights_storey3',
							'css_class' => 'initial_fieldset',
							'label' => 'Wall (Ceiling) Heights (3rd Storey)',
							'form_items' => array(
												array('name' => 'wch3_hidden', 'type' => 'hidden'),
												array('name' => 'wch3_select_hidden', 'type' => 'hidden'),
												array('name' => 'wch3_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wch3_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep3),
												array('name' => 'wch3_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp3),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Regular Ceiling + Cathdral Ceiling', 'type' => 'heading'),
												array('name' => 'wch3_field1', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch3'),
												array('name' => 'wch3_field2', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch3'),
												array('name' => 'wch3_field3', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch3'),
												array('name' => 'wch3_field4', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch3_field5', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Overall Ceiling', 'type' => 'heading'),
												array('name' => 'wch3_overall1', 'type' => 'interior_more_overall'),
												array('name' => 'wch3_overall2', 'type' => 'interior_more_overall'),
												array('name' => 'wch3_overall3', 'type' => 'interior_more_overall'),

												array('name' => 'wch3_total', 'type' =>'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_ceiling_heights_storey3_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'wall_ceiling_heights_storey4',
							'css_class' => 'initial_fieldset',
							'label' => 'Wall (Ceiling) Heights (4th Storey)',
							'form_items' => array(
												array('name' => 'wch4_hidden', 'type' => 'hidden'),
												array('name' => 'wch4_select_hidden', 'type' => 'hidden'),
												array('name' => 'wch4_storey_hidden', 'type' => 'hidden'),
												array('name' => 'wch4_ep', 'label' => 'Info:Automatic entry(Estimated percentage this Storey is to overall dwelling)','type' => 'readonly', 'no_bind' => '1', 'value' => $ep4),
												array('name' => 'wch4_fp', 'label' => 'Floor % over or under accounted for (auto entry)','type' => 'readonly', 'no_bind' => '1', 'value' => $fp4),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Regular Ceiling + Cathdral Ceiling', 'type' => 'heading'),
												array('name' => 'wch4_field1', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch4'),
												array('name' => 'wch4_field2', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch4'),
												array('name' => 'wch4_field3', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch4'),
												array('name' => 'wch4_field4', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'wch4_field5', 'type' => 'interior_more_select', 'datasets' => $wch_options, 'drop_down_type' => 'wch1'),
												array('name' => 'hr_space', 'type' => 'space'),
												array('name' => 'Overall Ceiling', 'type' => 'heading'),
												array('name' => 'wch4_overall1', 'type' => 'interior_more_overall'),
												array('name' => 'wch4_overall2', 'type' => 'interior_more_overall'),
												array('name' => 'wch4_overall3', 'type' => 'interior_more_overall'),

												array('name' => 'wch4_total', 'type' =>'interior_more_select', 'calculation' => 'sum'),
												array('name' => 'wall_ceiling_heights_storey4_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)


											)
						);
		$field_sets[] = array(
							'id' => 'cumulative_summary_wch',
							'css_class' => 'initial_fieldset cumulative-section',
							'label' => 'Cumulative Summary',
							'form_items' => array(
												array('name' => 'cs_wch_hidden', 'type' => 'hidden'),
												array('name' => 'cs_wch_field1', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field2', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field3', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field4', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field5', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field6', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field7', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field8', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field9', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field10', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field11', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),
												array('name' => 'cs_wch_field12', 'type' => 'interior_more_cs', 'calculation' => 'cumulative'),

												array('name' => 'cathedral_ceilings', 'type' => 'interior_more_cs', 'calculation' => 'cathedral_total', 'label' => 'Cathedral Ceilings (in %)'),

												array('name' => 'cs_wch_total', 'type' => 'interior_more_cs', 'calculation' => 'sum'),
												array('name' => 'cumulative_summary_wch_comments', 'label' => 'Comments','type' => 'textarea','override_fieldset' => 1)

											)
						);
		if(empty($params) || count($params) == 1) {
			foreach($field_sets as $field_set) {
				array_push(self::$fieldset_infos, array('label' => $field_set['label'], 'id' => $field_set['id']));
				self::$fieldset_ids[] .= $field_set['id'];

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

	//return fieldset ID as JSON response.
	static function getFieldsetIds() {
		self::$fieldset_ids = [];
		self::drawForm();
		echo json_encode(self::$fieldset_ids);
	}
}

class InteriorMore extends InteriorMoreView{
    function __construct(){}
}
?>
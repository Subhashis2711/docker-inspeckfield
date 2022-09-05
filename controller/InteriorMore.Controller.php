<?php
/**
 * Controller class containing all the Interior tab related functionalities.
 *
 * @since 1.0
 */
class InteriorMoreController extends InteriorMoreModel{

	/**
     *
     * Function used to save data to the database.
     *
     * @param array $params inspection details.
	 *
     */
	static function save($params=array()){
		
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

	static function getPercentages($params=array()){ 
		$result = self::actionGetPercentages($params);
		
		if(is_array($result) && !empty($result)){
			$result['fp1'] = (isset($result['estimated_percentage1']))?(($result['estimated_percentage1']/100)-1)*100:0;
			$result['fp2'] = (isset($result['estimated_percentage2']))?((($result['estimated_percentage1']+$result['estimated_percentage2']))-100):0;
			$result['fp3'] = (isset($result['estimated_percentage3']))?((($result['estimated_percentage1']+$result['estimated_percentage2']+$result['estimated_percentage3']))-100):0;
			$result['fp4'] = (isset($result['estimated_percentage4']))?((($result['estimated_percentage1']+$result['estimated_percentage2']+$result['estimated_percentage3']+$result['estimated_percentage4']))-100):0;
			
		}
		return $result;
	}

	static function savePercentages($params=array()){
		$inspection_id		= $params['inspection_id'];
		$resp 				= array();
		$resp['error']		= true;
		$field_maps = array();

		if($inspection_id){
			$row = self::actionGetData($params);
			if(is_array($row) && !empty($row)){
				$params['row_present'] = 1;
			}
			$count = 1;

			while( $count <= 4 ){
				$ep = isset($params['ep'.$count])?$params['ep'.$count]:0;
				$ep = ($ep != 0)? $ep : NULL;
				if($ep){
					$field_maps['estimated_percentage'.$count] = $ep.' %';
					$fc_data = isset($row['floor_coverings_storey'.$count])?json_decode($row['floor_coverings_storey'.$count]):array();
					$wc_data = isset($row['wall_coverings_storey'.$count])?json_decode($row['wall_coverings_storey'.$count]):array();
					$cm_data = isset($row['ceiling_material_storey'.$count])?json_decode($row['ceiling_material_storey'.$count]):array();
					$wch_data = isset($row['wall_ceiling_heights_storey'.$count])?json_decode($row['wall_ceiling_heights_storey'.$count]):array();
					
					if(is_array($fc_data) && !empty($fc_data)){
						for ($x = 1; $x <= 5; $x++) {
							$sp = 0;
							foreach ($fc_data as $key => $value) {

								$key_percentage = 'fc'.$count.'_field'.$x;
								if($value->key == $key_percentage){
									$sp = $value->value;
								}

								$key_sm = 'fc'.$count.'_field'.$x.'_storey_metrics';
								if($value->key == $key_sm){
									$fc_data[$key]->value = ($ep*$sp)/100;
								}
							}
						}
						$field_maps['floor_coverings_storey'.$count] = json_encode($fc_data);
					}

					if(is_array($wc_data) && !empty($wc_data)){
						for ($x = 1; $x <= 5; $x++) {
							$sp = 0;
							foreach ($wc_data as $key => $value) {

								$key_percentage = 'wc'.$count.'_field'.$x;
								if($value->key == $key_percentage){
									$sp = $value->value;
								}

								$key_sm = 'wc'.$count.'_field'.$x.'_storey_metrics';
								if($value->key == $key_sm){
									$wc_data[$key]->value = ($ep*$sp)/100;
								}
							}
						}
						$field_maps['wall_coverings_storey'.$count] = json_encode($wc_data);
					}

					if(is_array($cm_data) && !empty($cm_data)){
						for ($x = 1; $x <= 5; $x++) {
							$sp = 0;
							foreach ($cm_data as $key => $value) {

								$key_percentage = 'cm'.$count.'_field'.$x;
								if($value->key == $key_percentage){
									$sp = $value->value;
								}

								$key_sm = 'cm'.$count.'_field'.$x.'_storey_metrics';
								if($value->key == $key_sm){
									$cm_data[$key]->value = ($ep*$sp)/100;
								}
							}
						}
						$field_maps['ceiling_material_storey'.$count] = json_encode($cm_data);
					}

					if(is_array($wch_data) && !empty($wch_data)){
						for ($x = 1; $x <= 5; $x++) {
							$sp = 0;
							foreach ($wch_data as $key => $value) {

								$key_percentage = 'wch'.$count.'_field'.$x;
								if($value->key == $key_percentage){
									$sp = $value->value;
								}

								$key_sm = 'wch'.$count.'_field'.$x.'_storey_metrics';
								if($value->key == $key_sm){
									$wch_data[$key]->value = ($ep*$sp)/100;
								}
							}
						}
						$field_maps['wall_ceiling_heights_storey'.$count] = json_encode($wch_data);
					}

				}else{
					$field_maps['estimated_percentage'.$count] = $ep+' %';
				}
				$count++;
			}
			$params['field_maps'] = $field_maps;
			self::actionsavePercentages($params);
			$resp['error']	= false;
		}

		echo json_encode($resp);
	}
}
?>
<?php
/**
 * Model class containing all the BUilding tab related database functionalities.
 *
 * @since 1.0
 */
class BuildingDetailsModel{
	/* Table Information */
	const table 				= 'building_details';
	
	/**
     *
     * Function used to save inspection form inputs to the database.
     *
     * @param array $params inspection input's key value informations.
	 *
     */
	static function actionSave($params=array()){
		$inspection_id 			= $params['inspection_id'];
		$key 					= $params['key'];
		$value 					= $params['value'];

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);
		
		// check if the corresponding table contains information related to the inspection
		$row					= $table->select()->where('inspection_id',$inspection_id)->one();

		if(is_array($row) && !empty($row)){
			// update the row
			$table->update([$key => $value])->where('inspection_id',$inspection_id)->execute();
		}else{
			// insert new row
			$table->insert([$key => $value, 'inspection_id' => $inspection_id])->execute();
		}
	}

	/**
     *
     * Function used to get inspection form inputs from the database.
     *
     * @param array $params inspection input's key value informations.
	 *
     */
	static function actionGetData($params=array()){
		$inspection_id 			= trim($params['inspection_id']);
		$row 					= array();

		if(!$inspection_id){
			return $row;
		}

		$obj 					= DB::getInstance();
		$sql_statement 			= $obj->table(self::table)->select();
		$sql_statement			= $sql_statement->where('inspection_id',$inspection_id);
		$row 					= $sql_statement->one();

		return $row;
	}
}
?>
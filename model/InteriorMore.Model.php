<?php
/**
 * Model class containing all the Interior-more tab related database functionalities.
 *
 * @since 1.0
 */
class InteriorMoreModel{
	/* Table Information */
	const table 				= 'interior_more';
	
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
	 * @return array $row fetched data
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

	/**
     *
     * Function used to get estimated percentage values for interior more from the database.
     *
     * @param array $params inspection input's key value informations.
	 * 
	 * @return array $row fetched data
     */
	static function actionGetPercentages($params=array()){
		$inspection_id = trim($params['inspection_id']);
		$row = array();

		if(!$inspection_id){
			return $row;
		}

		$obj = DB::getInstance();
		$row = $obj->table(self::table)->select(['estimated_percentage1', 'estimated_percentage2', 'estimated_percentage3', 'estimated_percentage4'])->where('inspection_id',$inspection_id)->one();

		return $row;
	}

	/**
     *
     * Function used to save estimated percentage values for interior more to the database.
     *
     * @param array $params inspection input's key value informations.
	 * 
	 * @return boolean save status
     */
	static function actionsavePercentages($params=array()){
		$inspection_id = $params['inspection_id'];
		$field_maps = $params['field_maps'];

		$obj = DB::getInstance();
		$table = $obj->table(self::table);
		$field_maps['inspection_id'] = $inspection_id;

		if($params['row_present'] == 1){
			// update the row
			$output = $table
			->update($field_maps)
			->where('inspection_id', $inspection_id)
			->execute();
		}else{
			// insert new row
			$output = $table
			->insert($field_maps)
			->execute();
		}

		if($output){
			return true;
		}else{
			return false;
		}
	}
}
?>
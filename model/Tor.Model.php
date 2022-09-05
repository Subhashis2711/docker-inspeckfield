<?php
/**
 * Model class containing all the Interior-more tab related database functionalities.
 *
 * @since 1.0
 */
class TorModel{
	/* Table Information */
	const table = 'tor';
	const inspection = 'inspection';
	
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
     * Function used to get tor inspections of a parent inspection from the database.
     *
     * @param array $params inspection input's key value informations.
	 *
	 * @return array $row fetched data
     */
	static function actionGetTors($params=array()){
		$inspection_id   = $params['inspection_id'];
		$rows 			 = array();

		if(!$inspection_id){
			return $rows;
		}

		$obj 			 = DB::getInstance();
		$sql_statement 	 = $obj->table(self::inspection)->select('inspection_id');
		$rows	         = $sql_statement->where('parent_id',$inspection_id)->get();

		return $rows;
	}

	/**
     *
     * Function used to get number of TOR inspections of a parent inspection from the database.
     *
     * @param array $params inspection input's key value informations.
	 *
	 * @return array $row fetched data
     */
	static function actionGetTorNumber($params=array()){
		$parent_id  = $params['parent_id'];
		$tor_type 	= $params['tor_type'];
		$tor_number = 0;

		if(!$parent_id){
			return $tor_number;
		}

		$obj 			 = DB::getInstance();
		$sql_statement 	 = $obj->table(self::inspection)->select();
		$tor_number	     = $sql_statement->where('parent_id',$parent_id)
											->where('tor_type', $tor_type)
											->count();

		return $tor_number;
	}

	/**
     *
     * Function used to create TOR inspection in the database.
     *
     * @param array $params inspection input's key value informations.
	 *
	 * @return string $inspection_id ID of the inspection
     */
	static function actionCreateTorInspection($params=array()){
		$current_time	= date('Y-m-d');
		$parent_id 		= $params['parent_id'];
		$user_id 		= $params['user_id'];
		$tor_type 		= $params['tor_type'];
		if((int)$params['tor_number'] != 0){
			$tor_number     = (int)$params['tor_number']+1;
		}else{
			$tor_number     = '';
		}
		
		$inspection_id  = $parent_id . ' TOR-' . $tor_type.$tor_number;

		if(!$parent_id || !$user_id){
			return '';
		}

		$obj 				= DB::getInstance();
		$table 				= $obj->table(self::inspection);

		$field_maps 		= array(
								'inspection_id' => $inspection_id,
								'user_id' => $user_id,
								'status' => 'inprocess',
								'created_at' => $current_time,
								'update_at'	=> $current_time,
								'parent_id' => $parent_id,
								'tor_type' => $tor_type
							);

		if($table->insert($field_maps)->execute()){
			return $inspection_id;
		}else{
			return false;
		}
	}
}
?>
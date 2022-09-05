<?php
/**
 * Model class containing all the Insured-Info tab related database functionalities.
 *
 * @since 1.0
 */
class InsuredInfoModel{
	/* Table Information */
	const table 				= 'insured_property_info';
	
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
		$tor_inspection 		= isset($params['tor_parent'])?$params['tor_parent']:$inspection_id;

		$obj 					= DB::getInstance();
		$sql_statement 			= $obj->table(self::table)->select();
		$sql_statement			= $sql_statement->where('inspection_id',$tor_inspection);
		$row 					= $sql_statement->one();

		if(isset($params['tor_parent'])){
			$tor_row = $obj->table(self::table)->select()->where('inspection_id',$inspection_id)->one();
			if($tor_row){
				foreach($tor_row as $field => $value){
					if($value == ''){
						$tor_row[$field] = $row[$field];
					}
				}

				return $tor_row;
			}
		}

		$row['insured_info_comments'] = ($row['insured_info_comments'] === '')? "Clearly describe the purpose of this Outbuilding (example: “It is a 2 story Laneway house used as a guest house having a garage, workshop/office, and a suite adjacent”)." : $row['insured_info_comments'];

		return $row;
	}

	static function actionSaveInsuredComments($params=array()){
		$inspection_id 			= $params['inspection_id'];
		$category_id 			= $params['category_id']."_comments";
		$value					= $params['value'];

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);

		$res      				= $table->update([$category_id => $value])
										->where('inspection_id', $inspection_id)
										->execute();

		return $res;
	}

	static function actionGetInsuredComments($params=array()){
		$inspection_id 			= $params['inspection_id'];
		$category_id 			= $params['category_id']."_comments";
		$res = array();

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);

		$res = $table->select($category_id)
						->where('inspection_id', $inspection_id)
						->get();
		
		return $res;


	}
}
?>
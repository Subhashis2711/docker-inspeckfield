<?php
/**
 * Model class containing all the Dashboard related database functionalities.
 *
 * @since 1.0
 */
class DashBoardModel{
	/* Table informations */
	const table 				= 'inspektech_dashboard_options';

	/**
     *
     * Function used to save dashboard options to the database.
     *
     * @param array $params inspection input's key value informations.
	 *
     */
	static function actionSaveOptions($params=array()){
		$key 	= (isset($params['key']) && $params['key'])?$params['key']:'';
		$value 	= $params['value'];

		if(!$key){
			return false;
		}

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);
		
		$option_entry			= $table->select()->where('option_name',$key)->one();

		if(is_array($option_entry) && !empty($option_entry)){
			// update the field
			// output -> number of rows affected, if success
			$output 			= $table->update(['option_value' => $value])->where('option_name',$key)->execute();
		}else{
			// insert new record
			// output -> id of the new record entered, if success
			$output 			= $table->insert(['option_name' => $key, 'option_value' => $value])->execute();
		}

		if($output){
			return true;
		}else{
			return false;
		}
	}

	/**
     *
     * Function used to get dashboard options from the database.
     *
     * @param array $params inspection input's key value informations.
	 *
	 * @param string $value option value string.
     */
	static function actionGetOptionValue($params=array()){
		$key	= $params['key'];
		$value 	= '';

		if(!$key){
			return $value;
		}

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);
		
		$option_entry			= $table->select()->where('option_name',$key)->one();

		if(is_array($option_entry) && !empty($option_entry)){
			$value 				= $option_entry['option_value'];
		}

		return $value;
	}
}
?>
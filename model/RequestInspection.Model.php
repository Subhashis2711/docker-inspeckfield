<?php
/**
 * Model class containing all the Inspection form related database functionalities.
 *
 * @since 1.0
 */
class RequestInspectionModel{

	/**
     *
     * Function used to get inspection information by inspection id from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $res fetched data
     */
	static function actionGetRow($params=array()){

		$inspection_id 	= $params['inspection_id'];
		$res 			= array();

		if($inspection_id){
			$obj 		= DB::getInstance();
			$table 		= $obj->table(Inspection::table);
			$res		= $table->select()->where('inspection_id',$inspection_id)->one();
		}

		return $res;
	}

	/**
     *
     * Function used to change inspection status after submission in the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return string $user_type type of the user submitting the inspection
     */
	static function actionSubmitInspection($params=array()){
		$current_time			= date('Y-m-d');

		$inspection_id 			= $params['inspection_id'];
		$obj 					= DB::getInstance();

		$sql_statement          = $obj
									->table("user")
									->select('user_type')
									->where('id', $_SESSION['current_user']['id']);

		$res                  	= $sql_statement->one();
		$user_type 				= $res['user_type'];

		$status = ($user_type == 1)? "approved": "complete";
		$update_params = [
			'status' => $status,
			'update_at' => $current_time
		];
		if($user_type == 1){
			$kt_status = $params['kt_status'];
			$update_params['kt_status'] = $kt_status;
		}
		
		$res 					= $obj
									->table(Inspection::table)
									->update($update_params)
									->where('inspection_id',$inspection_id)
									->execute();

		return array('user_type' => $user_type);
	}

	/**
     *
     * Function used to get inspection information by inspection id from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $res fetched data
     */
	static function actionGetInspectionData($params=array()){

		$inspection_id 			= $params['inspection_id'];

		if(!$inspection_id){
			return false;
		}

		$res 					= array();

		$obj 					= DB::getInstance();
		$sql_statement 			= $obj
									->table(Inspection::view)
									->select()
									->where('inspection_id',$inspection_id)
									->where('archived',0);

		$res					= $sql_statement->one();

		return $res;
	}

	/**
     *
     * Function used to get all the category informations of a tab from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $row fetched data
     */
	static function actionCheckForm($params=array()){

		$tab_id					= $params['tab_id'];
		$res					= array();

		if((self::isTor($params['inspection_id']) != false) && $tab_id == 'insured_property_info'){
			$inspection_id 		= self::isTor($params['inspection_id']);
		}else{
			$inspection_id 		= $params['inspection_id'];
		}

		if(!$inspection_id || !$tab_id){
			return false;
		}

		$obj 					= DB::getInstance();
		try{
			$res 			    = $obj
									->table($tab_id)
									->select()
									->where('inspection_id', $inspection_id)
									->get();
		} catch(Exception $e) {
			Ui::logError('Error in getting '.$tab_id.'fields from database');
		}

		return $res;
	}
	
	
	/**
     *
     * Function used for checking if the inspection is a TOR or not.
     *
     * @param string $inspection_id inspection id.
	 *
	 * @return string $parent_id id of the parent inspection
     */
	static function isTor($inspection_id){
		if(!$inspection_id){
			return false;
		}

		$obj 		 = DB::getInstance();
		$parent_id = $obj->table(Inspection::table)
							->select()
							->where('inspection_id', $inspection_id)
							->column('parent_id');
		if($parent_id){
			return $parent_id;
		}else{
			return false;
		}
	}

	/**
     *
     * Function used for fetching value for a given category label field from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $row fetched data
     */
	static function actionFetchValueFromDatabase($params=array()){

        $inspection_id 	= $params['inspection_id'];
        $table_name     = $params['table'];
        $field          = $params['field'];
		$res 			= array();

		if($inspection_id){
			$obj 		= DB::getInstance();
			$table 		= $obj->table($table_name);
			if($field == "*"){
				$res		= $table->select()->where('inspection_id',$inspection_id)->one();
			}else {
				$res		= $table->select($field)->where('inspection_id',$inspection_id)->one();
			}
		}

		if($field == "*" && !empty($res)){
			return $res;
		}elseif(!empty($res[$field])) {
			return $res[$field];
		}else{
			return 'n/a';
		}
	}

	/**
     *
     * Function used for changing status of an inspection to ITVAWIP for corrections by admin in the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $inspection fetched inspection data
	 * 
     */
	static function actionCreateITVAWIP($params=array()) {
		$res 					= array();

		$inspection_id			= $params['inspection_id'];
		$updated_id				= $params['updated_id'];
		$itva_id				= $params['itva_id'];

		if($inspection_id){
			$obj 				= DB::getInstance();
			$res 				= $obj->table(Inspection::table)
									    ->update([
											'inspection_id' => $updated_id,
											'status' => 'itvawip',
											'itva_id' => $itva_id,
											'kt_status' => 'review',
										])
									    ->where('inspection_id', $inspection_id)
									    ->execute();

			if($res){
				$inspection 	= self::actionGetRow(['inspection_id' => $updated_id]);

				return $inspection;
			}

		}

	}

	/**
     *
     * Function used for saving explore information to the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return boolean save status
	 * 
     */
	static function actionSaveExploreInfo($params=array()){
		$tab_id = $params['tab_id'];
		$section_id = $params['section_id'];
		$update = array();

		if(isset($params['explore_info'])){
			$explore_info = $params['explore_info'];
		}
		if(isset($params['alert'])){
			$alert = $params['alert'];
		}

		$obj 					= DB::getInstance();
		$table 					= $obj->table('explore_items');
		
		$explore_entry			= $table->select()
											->where([
												'tab_id' => $tab_id,
												'section_id' => $section_id,
											])
											->one();

		if(is_array($explore_entry) && !empty($explore_entry)){
			// update the field
			// output -> number of rows affected, if success
			if(isset($alert)){
				$update = array('alert' => $alert);
			}else{
				$update = array('value' => $explore_info);
			}

	        $output 	= $table->update($update)
									->where([
										'tab_id' => $tab_id,
										'section_id' => $section_id,
									])
									->execute();
		}else{
			// insert new record
			// output -> id of the new record entered, if success
			$output 	= $table->insert([
										'tab_id' => $tab_id, 
										'section_id' => $section_id,
										'value' => $explore_info
									])
									->execute();
		}

		if($output){
			return true;
		}else{
			return false;
		}
	}

	/**
     *
     * Function used for getting explore information from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return string $value explore value string
	 * 
     */
	static function actionGetExploreInfo($params=array()){
		$tab_id		= $params['tab_id'];
		$section_id = $params['section_id'];
		$value 		= '';

		if(empty($tab_id) && empty($section_id)){
			return;
		}

		$obj 					= DB::getInstance();
		$table 					= $obj->table('explore_items');
		
		$explore_info			= $table->select()
											->where([
												'tab_id' => $tab_id,
												'section_id' => $section_id,
											])
											->one();

		if(is_array($explore_info) && !empty($explore_info)){
			if(isset($params['alert']) && $params['alert'] == true){
				$value 			= $explore_info;
			}else{
				$value 			= $explore_info['value'];
			}
		}

		return $value;
	}

	/**
     *
     * Function used for saving review status of an ITVAWIP inspection by FI to the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return boolean save status
	 * 
     */
	static function actionSaveReviewByFI($params=array()){
		$inspection_id	= $params['inspection_id'];
		$section_id 	= $params['section_id'];
		$tab_id			= $params['tab_id'];
		$status 		= $params['status'];

		$obj 			= DB::getInstance();
		$table 			= $obj->table('inspection_review_status');

		
		$review_entry	= $table->select()
									->where([
										'inspection_id' => $inspection_id,
										'section_id' => $section_id,
										'tab_id' => $tab_id,
									])
									->one();

		if(is_array($review_entry) && !empty($review_entry)){
			// update the field
			// output -> number of rows affected, if success
			$output 	= $table->update(['review_status' => $status])
									->where([
										'inspection_id' => $inspection_id,
										'section_id' => $section_id,
										'tab_id' => $tab_id
 									])
									->execute();

		}else{
			// insert new record
			// output -> id of the new record entered, if success
			$output 	= $table->insert([
										'inspection_id' => $inspection_id, 
										'tab_id' => $tab_id,
										'section_id' => $section_id,
										'review_status' => $status
									])
									->execute();

		}

		if($output){
			return true;
		}else{
			return false;
		}
	}

	/**
     *
     * Function used for getting review status of an ITVAWIP inspection by FI from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return string $status saved status
	 * 
     */
	static function actionGetReviewStatus($params=array()){
		$inspection_id	= $params['inspection_id'];
		$section_id 	= $params['section_id'];
		$tab_id			= $params['tab_id'];
		$status 		= '';

		if(empty($inspection_id) && empty($section_id)){
			return;
		}

		$obj 					= DB::getInstance();
		$table 					= $obj->table('inspection_review_status');
		
		$review_status			= $table->select()
											->where([
												'inspection_id' => $inspection_id,
												'section_id' => $section_id,
											])
											->one();

		if(is_array($review_status) && !empty($review_status)){
			$status				= $review_status['review_status'];
		}

		return $status;
	}

	/**
     *
     * Function used for getting all reviewed sections from a tab from the database.
     *
     * @param array $params inspection informations.
	 *
	 * @return array $res All reviewed sections.
	 * 
     */
	static function actionGetAllReviewedSectionsFromTab($params=array()){
		$inspection_id	= $params['inspection_id'];
		$tab_id			= $params['tab_id'];
		

		if(empty($inspection_id) && empty($tab_id)){
			return;
		}

		$obj 			= DB::getInstance();
		$table 			= $obj->table('inspection_review_status');
		
		$res			= $table->select('section_id')
									->where([
										'inspection_id' => $inspection_id,
										'tab_id' => $tab_id,
										'review_status' => 1
									])
									->execute();

		return $res;
	}

}
?>

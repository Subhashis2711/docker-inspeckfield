<?php
/**
 * Controller class containing all the Insured info tab related functionalities.
 *
 * @since 1.0
 */
class InsuredInfoController extends InsuredInfoModel{

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
		$inspection_id = trim($params['inspection_id']);
		$tor_parent = RequestInspection::isTor($inspection_id);

		if($tor_parent){
			$params['tor_parent'] = $tor_parent;
		}
		
		return self::actionGetData($params);
	}
}
?>
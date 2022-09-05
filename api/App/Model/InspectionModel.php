<?php
/**
 * Model class containing all inspections modifications
 *
 * @since 1.0
 */
class InspectionModel{
    /**
     *
     * Function to update inspection status in database
     *
     * @param array $params 
	 * 
     * @return boolean
     *
     */
    static function actionupdateInspectionStatus($params=array()){
        $obj                = DB::getInstance();
        $inspection_id      = $params['inspection_id']; 
        $status             = $params['status'];
        $update_params      = ['status' => $status];
        if(isset($params['kt_status']) && !empty($kt_status)){
            $update_params['kt_status'] = $params['kt_status'];
        }

        try{
            $inspection     = $obj->table('inspection')->select()
                                    ->where('inspection_id', 'like', $inspection_id.'-Review-%')
                                    ->one();

            if($inspection){
                $inspection_id = $inspection['inspection_id'];
                
                $res        = $obj->table('inspection')->update()
                                    ->set($update_params)
                                    ->where('inspection_id', $inspection_id)
                                    ->execute();
                return true;
                
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
}



?>